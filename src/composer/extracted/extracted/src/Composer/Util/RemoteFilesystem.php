<?php


namespace Composer\Util;

use Composer\CaBundle\CaBundle;
use Composer\Config;
use Composer\Downloader\TransportException;
use Composer\IO\IOInterface;
use Psr\Log\LoggerInterface;


class RemoteFilesystem
{
    private $io;
    private $config;
    private $scheme;
    private $bytesMax;
    private $originUrl;
    private $fileUrl;
    private $fileName;
    private $retry;
    private $progress;
    private $lastProgress;
    private $options = array();
    private $peerCertificateMap = array();
    private $disableTls = false;
    private $retryAuthFailure;
    private $lastHeaders;
    private $storeAuth;
    private $degradedMode = false;
    private $redirects;
    private $maxRedirects = 20;


    public function __construct(IOInterface $io, Config $config = null, array $options = array(), $disableTls = false)
    {
        $this->io = $io;


        if ($disableTls === false) {
            $this->options = $this->getTlsDefaults($options);
        } else {
            $this->disableTls = true;
        }


        $this->options = array_replace_recursive($this->options, $options);
        $this->config = $config;
    }

    private function getTlsDefaults(array $options)
    {
        $ciphers = implode(':', array(
            'ECDHE-RSA-AES128-GCM-SHA256',
            'ECDHE-ECDSA-AES128-GCM-SHA256',
            'ECDHE-RSA-AES256-GCM-SHA384',
            'ECDHE-ECDSA-AES256-GCM-SHA384',
            'DHE-RSA-AES128-GCM-SHA256',
            'DHE-DSS-AES128-GCM-SHA256',
            'kEDH+AESGCM',
            'ECDHE-RSA-AES128-SHA256',
            'ECDHE-ECDSA-AES128-SHA256',
            'ECDHE-RSA-AES128-SHA',
            'ECDHE-ECDSA-AES128-SHA',
            'ECDHE-RSA-AES256-SHA384',
            'ECDHE-ECDSA-AES256-SHA384',
            'ECDHE-RSA-AES256-SHA',
            'ECDHE-ECDSA-AES256-SHA',
            'DHE-RSA-AES128-SHA256',
            'DHE-RSA-AES128-SHA',
            'DHE-DSS-AES128-SHA256',
            'DHE-RSA-AES256-SHA256',
            'DHE-DSS-AES256-SHA',
            'DHE-RSA-AES256-SHA',
            'AES128-GCM-SHA256',
            'AES256-GCM-SHA384',
            'AES128-SHA256',
            'AES256-SHA256',
            'AES128-SHA',
            'AES256-SHA',
            'AES',
            'CAMELLIA',
            'DES-CBC3-SHA',
            '!aNULL',
            '!eNULL',
            '!EXPORT',
            '!DES',
            '!RC4',
            '!MD5',
            '!PSK',
            '!aECDH',
            '!EDH-DSS-DES-CBC3-SHA',
            '!EDH-RSA-DES-CBC3-SHA',
            '!KRB5-DES-CBC3-SHA',
        ));


        $defaults = array(
            'ssl' => array(
                'ciphers' => $ciphers,
                'verify_peer' => true,
                'verify_depth' => 7,
                'SNI_enabled' => true,
                'capture_peer_cert' => true,
            ),
        );

        if (isset($options['ssl'])) {
            $defaults['ssl'] = array_replace_recursive($defaults['ssl'], $options['ssl']);
        }

        $caBundleLogger = $this->io instanceof LoggerInterface ? $this->io : null;


        if (!isset($defaults['ssl']['cafile']) && !isset($defaults['ssl']['capath'])) {
            $result = CaBundle::getSystemCaRootBundlePath($caBundleLogger);

            if (preg_match('{^phar://}', $result)) {
                $hash = hash_file('sha256', $result);
                $targetPath = rtrim(sys_get_temp_dir(), '\\/') . '/composer-cacert-' . $hash . '.pem';

                if (!file_exists($targetPath) || $hash !== hash_file('sha256', $targetPath)) {
                    $this->streamCopy($result, $targetPath);
                    chmod($targetPath, 0666);
                }

                $defaults['ssl']['cafile'] = $targetPath;
            } elseif (is_dir($result)) {
                $defaults['ssl']['capath'] = $result;
            } else {
                $defaults['ssl']['cafile'] = $result;
            }
        }

        if (isset($defaults['ssl']['cafile']) && (!is_readable($defaults['ssl']['cafile']) || !CaBundle::validateCaFile($defaults['ssl']['cafile'], $caBundleLogger))) {
            throw new TransportException('The configured cafile was not valid or could not be read.');
        }

        if (isset($defaults['ssl']['capath']) && (!is_dir($defaults['ssl']['capath']) || !is_readable($defaults['ssl']['capath']))) {
            throw new TransportException('The configured capath was not valid or could not be read.');
        }


        if (PHP_VERSION_ID >= 50413) {
            $defaults['ssl']['disable_compression'] = true;
        }

        return $defaults;
    }

    private function streamCopy($source, $target)
    {
        $source = fopen($source, 'r');
        $target = fopen($target, 'w+');

        stream_copy_to_stream($source, $target);
        fclose($source);
        fclose($target);

        unset($source, $target);
    }

    public function copy($originUrl, $fileUrl, $fileName, $progress = true, $options = array())
    {
        return $this->get($originUrl, $fileUrl, $options, $fileName, $progress);
    }

    protected function get($originUrl, $fileUrl, $additionalOptions = array(), $fileName = null, $progress = true)
    {
        if (strpos($originUrl, '.github.com') === (strlen($originUrl) - 11)) {
            $originUrl = 'github.com';
        }

        $this->scheme = parse_url($fileUrl, PHP_URL_SCHEME);
        $this->bytesMax = 0;
        $this->originUrl = $originUrl;
        $this->fileUrl = $fileUrl;
        $this->fileName = $fileName;
        $this->progress = $progress;
        $this->lastProgress = null;
        $this->retryAuthFailure = true;
        $this->lastHeaders = array();
        $this->redirects = 1;


        if (preg_match('{^https?://(.+):(.+)@([^/]+)}i', $fileUrl, $match)) {
            $this->io->setAuthentication($originUrl, urldecode($match[1]), urldecode($match[2]));
        }

        $tempAdditionalOptions = $additionalOptions;
        if (isset($tempAdditionalOptions['retry-auth-failure'])) {
            $this->retryAuthFailure = (bool)$tempAdditionalOptions['retry-auth-failure'];

            unset($tempAdditionalOptions['retry-auth-failure']);
        }

        $isRedirect = false;
        if (isset($tempAdditionalOptions['redirects'])) {
            $this->redirects = $tempAdditionalOptions['redirects'];
            $isRedirect = true;

            unset($tempAdditionalOptions['redirects']);
        }

        $options = $this->getOptionsForUrl($originUrl, $tempAdditionalOptions);
        unset($tempAdditionalOptions);
        $userlandFollow = isset($options['http']['follow_location']) && !$options['http']['follow_location'];

        $origFileUrl = $fileUrl;

        if (isset($options['github-token'])) {

            if (preg_match('{^https?://([a-z0-9-]+\.)*github\.com/}', $fileUrl)) {
                $fileUrl .= (false === strpos($fileUrl, '?') ? '?' : '&') . 'access_token=' . $options['github-token'];
            }
            unset($options['github-token']);
        }

        if (isset($options['gitlab-token'])) {
            $fileUrl .= (false === strpos($fileUrl, '?') ? '?' : '&') . 'access_token=' . $options['gitlab-token'];
            unset($options['gitlab-token']);
        }

        if (isset($options['http'])) {
            $options['http']['ignore_errors'] = true;
        }

        if ($this->degradedMode && substr($fileUrl, 0, 21) === 'http://packagist.org/') {

            $fileUrl = 'http://' . gethostbyname('packagist.org') . substr($fileUrl, 20);
            $degradedPackagist = true;
        }

        $ctx = StreamContextFactory::getContext($fileUrl, $options, array('notification' => array($this, 'callbackGet')));

        $actualContextOptions = stream_context_get_options($ctx);
        $usingProxy = !empty($actualContextOptions['http']['proxy']) ? ' using proxy ' . $actualContextOptions['http']['proxy'] : '';
        $this->io->writeError((substr($origFileUrl, 0, 4) === 'http' ? 'Downloading ' : 'Reading ') . $origFileUrl . $usingProxy, true, IOInterface::DEBUG);
        unset($origFileUrl, $actualContextOptions);


        if ((substr($fileUrl, 0, 23) !== 'http://packagist.org/p/' || (false === strpos($fileUrl, '$') && false === strpos($fileUrl, '%24'))) && empty($degradedPackagist) && $this->config) {
            $this->config->prohibitUrlByConfig($fileUrl, $this->io);
        }

        if ($this->progress && !$isRedirect) {
            $this->io->writeError("Downloading (<comment>connecting...</comment>)", false);
        }

        $errorMessage = '';
        $errorCode = 0;
        $result = false;
        set_error_handler(function ($code, $msg) use (&$errorMessage) {
            if ($errorMessage) {
                $errorMessage .= "\n";
            }
            $errorMessage .= preg_replace('{^file_get_contents\(.*?\): }', '', $msg);
        });
        try {
            $result = file_get_contents($fileUrl, false, $ctx);

            $contentLength = !empty($http_response_header[0]) ? $this->findHeaderValue($http_response_header, 'content-length') : null;
            if ($contentLength && Platform::strlen($result) < $contentLength) {

                throw new TransportException('Content-Length mismatch');
            }

            if (PHP_VERSION_ID < 50600 && !empty($options['ssl']['peer_fingerprint'])) {

                $params = stream_context_get_params($ctx);
                $expectedPeerFingerprint = $options['ssl']['peer_fingerprint'];
                $peerFingerprint = TlsHelper::getCertificateFingerprint($params['options']['ssl']['peer_certificate']);


                if ($expectedPeerFingerprint !== $peerFingerprint) {
                    throw new TransportException('Peer fingerprint did not match');
                }
            }
        } catch (\Exception $e) {
            if ($e instanceof TransportException && !empty($http_response_header[0])) {
                $e->setHeaders($http_response_header);
                $e->setStatusCode($this->findStatusCode($http_response_header));
            }
            if ($e instanceof TransportException && $result !== false) {
                $e->setResponse($result);
            }
            $result = false;
        }
        if ($errorMessage && !ini_get('allow_url_fopen')) {
            $errorMessage = 'allow_url_fopen must be enabled in php.ini (' . $errorMessage . ')';
        }
        restore_error_handler();
        if (isset($e) && !$this->retry) {
            if (!$this->degradedMode && false !== strpos($e->getMessage(), 'Operation timed out')) {
                $this->degradedMode = true;
                $this->io->writeError('');
                $this->io->writeError(array(
                    '<error>' . $e->getMessage() . '</error>',
                    '<error>Retrying with degraded mode, check https://getcomposer.org/doc/articles/troubleshooting.md#degraded-mode for more info</error>',
                ));

                return $this->get($this->originUrl, $this->fileUrl, $additionalOptions, $this->fileName, $this->progress);
            }

            throw $e;
        }

        $statusCode = null;
        $contentType = null;
        if (!empty($http_response_header[0])) {
            $statusCode = $this->findStatusCode($http_response_header);
            $contentType = $this->findHeaderValue($http_response_header, 'content-type');
        }


        if ($originUrl === 'bitbucket.org'
            && !$this->isPublicBitBucketDownload($fileUrl)
            && substr($fileUrl, -4) === '.zip'
            && $contentType && preg_match('{^text/html\b}i', $contentType)
        ) {
            $result = false;
            if ($this->retryAuthFailure) {
                $this->promptAuthAndRetry(401);
            }
        }


        $hasFollowedRedirect = false;
        if ($userlandFollow && $statusCode >= 300 && $statusCode <= 399 && $statusCode !== 304 && $this->redirects < $this->maxRedirects) {
            $hasFollowedRedirect = true;
            $result = $this->handleRedirect($http_response_header, $additionalOptions, $result);
        }


        if ($statusCode && $statusCode >= 400 && $statusCode <= 599) {
            if (!$this->retry) {
                if ($this->progress && !$this->retry && !$isRedirect) {
                    $this->io->overwriteError("Downloading (<error>failed</error>)", false);
                }

                $e = new TransportException('The "' . $this->fileUrl . '" file could not be downloaded (' . $http_response_header[0] . ')', $statusCode);
                $e->setHeaders($http_response_header);
                $e->setResponse($result);
                $e->setStatusCode($statusCode);
                throw $e;
            }
            $result = false;
        }

        if ($this->progress && !$this->retry && !$isRedirect) {
            $this->io->overwriteError("Downloading (" . ($result === false ? '<error>failed</error>' : '<comment>100%</comment>') . ")", false);
        }


        if ($result && extension_loaded('zlib') && substr($fileUrl, 0, 4) === 'http' && !$hasFollowedRedirect) {
            $contentEncoding = $this->findHeaderValue($http_response_header, 'content-encoding');
            $decode = $contentEncoding && 'gzip' === strtolower($contentEncoding);

            if ($decode) {
                try {
                    if (PHP_VERSION_ID >= 50400) {
                        $result = zlib_decode($result);
                    } else {

                        $result = file_get_contents('compress.zlib://data:application/octet-stream;base64,' . base64_encode($result));
                    }

                    if (!$result) {
                        throw new TransportException('Failed to decode zlib stream');
                    }
                } catch (\Exception $e) {
                    if ($this->degradedMode) {
                        throw $e;
                    }

                    $this->degradedMode = true;
                    $this->io->writeError(array(
                        '',
                        '<error>Failed to decode response: ' . $e->getMessage() . '</error>',
                        '<error>Retrying with degraded mode, check https://getcomposer.org/doc/articles/troubleshooting.md#degraded-mode for more info</error>',
                    ));

                    return $this->get($this->originUrl, $this->fileUrl, $additionalOptions, $this->fileName, $this->progress);
                }
            }
        }


        if (false !== $result && null !== $fileName && !$isRedirect) {
            if ('' === $result) {
                throw new TransportException('"' . $this->fileUrl . '" appears broken, and returned an empty 200 response');
            }

            $errorMessage = '';
            set_error_handler(function ($code, $msg) use (&$errorMessage) {
                if ($errorMessage) {
                    $errorMessage .= "\n";
                }
                $errorMessage .= preg_replace('{^file_put_contents\(.*?\): }', '', $msg);
            });
            $result = (bool)file_put_contents($fileName, $result);
            restore_error_handler();
            if (false === $result) {
                throw new TransportException('The "' . $this->fileUrl . '" file could not be written to ' . $fileName . ': ' . $errorMessage);
            }
        }


        if (false === $result && false !== strpos($errorMessage, 'Peer certificate') && PHP_VERSION_ID < 50600) {


            if (CaBundle::isOpensslParseSafe()) {
                $certDetails = $this->getCertificateCnAndFp($this->fileUrl, $options);

                if ($certDetails) {
                    $this->peerCertificateMap[$this->getUrlAuthority($this->fileUrl)] = $certDetails;

                    $this->retry = true;
                }
            } else {
                $this->io->writeError('');
                $this->io->writeError(sprintf(
                    '<error>Your version of PHP, %s, is affected by CVE-2013-6420 and cannot safely perform certificate validation, we strongly suggest you upgrade.</error>',
                    PHP_VERSION
                ));
            }
        }

        if ($this->retry) {
            $this->retry = false;

            $result = $this->get($this->originUrl, $this->fileUrl, $additionalOptions, $this->fileName, $this->progress);

            if ($this->storeAuth && $this->config) {
                $authHelper = new AuthHelper($this->io, $this->config);
                $authHelper->storeAuth($this->originUrl, $this->storeAuth);
                $this->storeAuth = false;
            }

            return $result;
        }

        if (false === $result) {
            $e = new TransportException('The "' . $this->fileUrl . '" file could not be downloaded: ' . $errorMessage, $errorCode);
            if (!empty($http_response_header[0])) {
                $e->setHeaders($http_response_header);
            }

            if (!$this->degradedMode && false !== strpos($e->getMessage(), 'Operation timed out')) {
                $this->degradedMode = true;
                $this->io->writeError('');
                $this->io->writeError(array(
                    '<error>' . $e->getMessage() . '</error>',
                    '<error>Retrying with degraded mode, check https://getcomposer.org/doc/articles/troubleshooting.md#degraded-mode for more info</error>',
                ));

                return $this->get($this->originUrl, $this->fileUrl, $additionalOptions, $this->fileName, $this->progress);
            }

            throw $e;
        }

        if (!empty($http_response_header[0])) {
            $this->lastHeaders = $http_response_header;
        }

        return $result;
    }

    protected function getOptionsForUrl($originUrl, $additionalOptions)
    {
        $tlsOptions = array();


        if ($this->disableTls === false && PHP_VERSION_ID < 50600 && !stream_is_local($this->fileUrl)) {
            $host = parse_url($this->fileUrl, PHP_URL_HOST);

            if (PHP_VERSION_ID >= 50304) {


                $userlandFollow = true;
            } else {


                if ($host === 'github.com' || $host === 'api.github.com') {
                    $host = '*.github.com';
                }
            }

            $tlsOptions['ssl']['CN_match'] = $host;
            $tlsOptions['ssl']['SNI_server_name'] = $host;

            $urlAuthority = $this->getUrlAuthority($this->fileUrl);

            if (isset($this->peerCertificateMap[$urlAuthority])) {

                $certMap = $this->peerCertificateMap[$urlAuthority];

                $this->io->writeError('', true, IOInterface::DEBUG);
                $this->io->writeError(sprintf(
                    'Using <info>%s</info> as CN for subjectAltName enabled host <info>%s</info>',
                    $certMap['cn'],
                    $urlAuthority
                ), true, IOInterface::DEBUG);

                $tlsOptions['ssl']['CN_match'] = $certMap['cn'];
                $tlsOptions['ssl']['peer_fingerprint'] = $certMap['fp'];
            }
        }

        $headers = array();

        if (extension_loaded('zlib')) {
            $headers[] = 'Accept-Encoding: gzip';
        }

        $options = array_replace_recursive($this->options, $tlsOptions, $additionalOptions);
        if (!$this->degradedMode) {


            $options['http']['protocol_version'] = 1.1;
            $headers[] = 'Connection: close';
        }

        if (isset($userlandFollow)) {
            $options['http']['follow_location'] = 0;
        }

        if ($this->io->hasAuthentication($originUrl)) {
            $auth = $this->io->getAuthentication($originUrl);
            if ('github.com' === $originUrl && 'x-oauth-basic' === $auth['password']) {
                $options['github-token'] = $auth['username'];
            } elseif ($this->config && in_array($originUrl, $this->config->get('gitlab-domains'), true)) {
                if ($auth['password'] === 'oauth2') {
                    $headers[] = 'Authorization: Bearer ' . $auth['username'];
                } elseif ($auth['password'] === 'private-token') {
                    $headers[] = 'PRIVATE-TOKEN: ' . $auth['username'];
                }
            } elseif ('bitbucket.org' === $originUrl
                && $this->fileUrl !== Bitbucket::OAUTH2_ACCESS_TOKEN_URL && 'x-token-auth' === $auth['username']
            ) {
                if (!$this->isPublicBitBucketDownload($this->fileUrl)) {
                    $headers[] = 'Authorization: Bearer ' . $auth['password'];
                }
            } else {
                $authStr = base64_encode($auth['username'] . ':' . $auth['password']);
                $headers[] = 'Authorization: Basic ' . $authStr;
            }
        }

        if (isset($options['http']['header']) && !is_array($options['http']['header'])) {
            $options['http']['header'] = explode("\r\n", trim($options['http']['header'], "\r\n"));
        }
        foreach ($headers as $header) {
            $options['http']['header'][] = $header;
        }

        return $options;
    }

    private function getUrlAuthority($url)
    {
        $defaultPorts = array(
            'ftp' => 21,
            'http' => 80,
            'https' => 443,
            'ssh2.sftp' => 22,
            'ssh2.scp' => 22,
        );

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if (!isset($defaultPorts[$scheme])) {
            throw new \InvalidArgumentException(sprintf(
                'Could not get default port for unknown scheme: %s',
                $scheme
            ));
        }

        $defaultPort = $defaultPorts[$scheme];
        $port = parse_url($url, PHP_URL_PORT) ?: $defaultPort;

        return parse_url($url, PHP_URL_HOST) . ':' . $port;
    }

    private function isPublicBitBucketDownload($urlToBitBucketFile)
    {
        $domain = parse_url($urlToBitBucketFile, PHP_URL_HOST);
        if (strpos($domain, 'bitbucket.org') === false) {


            return true;
        }

        $path = parse_url($urlToBitBucketFile, PHP_URL_PATH);


        $pathParts = explode('/', $path);
        if (count($pathParts) >= 4 && $pathParts[3] == 'downloads') {
            return true;
        }

        return false;
    }

    public function findHeaderValue(array $headers, $name)
    {
        $value = null;
        foreach ($headers as $header) {
            if (preg_match('{^' . $name . ':\s*(.+?)\s*$}i', $header, $match)) {
                $value = $match[1];
            } elseif (preg_match('{^HTTP/}i', $header)) {


                $value = null;
            }
        }

        return $value;
    }

    public function findStatusCode(array $headers)
    {
        $value = null;
        foreach ($headers as $header) {
            if (preg_match('{^HTTP/\S+ (\d+)}i', $header, $match)) {


                $value = (int)$match[1];
            }
        }

        return $value;
    }

    protected function promptAuthAndRetry($httpStatus, $reason = null)
    {
        if ($this->config && in_array($this->originUrl, $this->config->get('github-domains'), true)) {
            $message = "\n" . 'Could not fetch ' . $this->fileUrl . ', please create a GitHub OAuth token ' . ($httpStatus === 404 ? 'to access private repos' : 'to go over the API rate limit');
            $gitHubUtil = new GitHub($this->io, $this->config, null);
            if (!$gitHubUtil->authorizeOAuth($this->originUrl)
                && (!$this->io->isInteractive() || !$gitHubUtil->authorizeOAuthInteractively($this->originUrl, $message))
            ) {
                throw new TransportException('Could not authenticate against ' . $this->originUrl, 401);
            }
        } elseif ($this->config && in_array($this->originUrl, $this->config->get('gitlab-domains'), true)) {
            $message = "\n" . 'Could not fetch ' . $this->fileUrl . ', enter your ' . $this->originUrl . ' credentials ' . ($httpStatus === 401 ? 'to access private repos' : 'to go over the API rate limit');
            $gitLabUtil = new GitLab($this->io, $this->config, null);

            if ($this->io->hasAuthentication($this->originUrl) && ($auth = $this->io->getAuthentication($this->originUrl)) && $auth['password'] === 'private-token') {
                throw new TransportException("Invalid credentials for '" . $this->fileUrl . "', aborting.", $httpStatus);
            }

            if (!$gitLabUtil->authorizeOAuth($this->originUrl)
                && (!$this->io->isInteractive() || !$gitLabUtil->authorizeOAuthInteractively($this->scheme, $this->originUrl, $message))
            ) {
                throw new TransportException('Could not authenticate against ' . $this->originUrl, 401);
            }
        } elseif ($this->config && $this->originUrl === 'bitbucket.org') {
            $askForOAuthToken = true;
            if ($this->io->hasAuthentication($this->originUrl)) {
                $auth = $this->io->getAuthentication($this->originUrl);
                if ($auth['username'] !== 'x-token-auth') {
                    $bitbucketUtil = new Bitbucket($this->io, $this->config);
                    $accessToken = $bitbucketUtil->requestToken($this->originUrl, $auth['username'], $auth['password']);
                    if (!empty($accessToken)) {
                        $this->io->setAuthentication($this->originUrl, 'x-token-auth', $accessToken);
                        $askForOAuthToken = false;
                    }
                } else {
                    throw new TransportException('Could not authenticate against ' . $this->originUrl, 401);
                }
            }

            if ($askForOAuthToken) {
                $message = "\n" . 'Could not fetch ' . $this->fileUrl . ', please create a bitbucket OAuth token to ' . (($httpStatus === 401 || $httpStatus === 403) ? 'access private repos' : 'go over the API rate limit');
                $bitBucketUtil = new Bitbucket($this->io, $this->config);
                if (!$bitBucketUtil->authorizeOAuth($this->originUrl)
                    && (!$this->io->isInteractive() || !$bitBucketUtil->authorizeOAuthInteractively($this->originUrl, $message))
                ) {
                    throw new TransportException('Could not authenticate against ' . $this->originUrl, 401);
                }
            }
        } else {

            if ($httpStatus === 404) {
                return;
            }


            if (!$this->io->isInteractive()) {
                if ($httpStatus === 401) {
                    $message = "The '" . $this->fileUrl . "' URL required authentication.\nYou must be using the interactive console to authenticate";
                }
                if ($httpStatus === 403) {
                    $message = "The '" . $this->fileUrl . "' URL could not be accessed: " . $reason;
                }

                throw new TransportException($message, $httpStatus);
            }

            if ($this->io->hasAuthentication($this->originUrl)) {
                throw new TransportException("Invalid credentials for '" . $this->fileUrl . "', aborting.", $httpStatus);
            }

            $this->io->overwriteError('');
            $this->io->writeError('    Authentication required (<info>' . parse_url($this->fileUrl, PHP_URL_HOST) . '</info>):');
            $username = $this->io->ask('      Username: ');
            $password = $this->io->askAndHideAnswer('      Password: ');
            $this->io->setAuthentication($this->originUrl, $username, $password);
            $this->storeAuth = $this->config->get('store-auths');
        }

        $this->retry = true;
        throw new TransportException('RETRY');
    }

    private function handleRedirect(array $http_response_header, array $additionalOptions, $result)
    {
        if ($locationHeader = $this->findHeaderValue($http_response_header, 'location')) {
            if (parse_url($locationHeader, PHP_URL_SCHEME)) {

                $targetUrl = $locationHeader;
            } elseif (parse_url($locationHeader, PHP_URL_HOST)) {

                $targetUrl = $this->scheme . ':' . $locationHeader;
            } elseif ('/' === $locationHeader[0]) {

                $urlHost = parse_url($this->fileUrl, PHP_URL_HOST);


                $targetUrl = preg_replace('{^(.+(?://|@)' . preg_quote($urlHost) . '(?::\d+)?)(?:[/\?].*)?$}', '\1' . $locationHeader, $this->fileUrl);
            } else {


                $targetUrl = preg_replace('{^(.+/)[^/?]*(?:\?.*)?$}', '\1' . $locationHeader, $this->fileUrl);
            }
        }

        if (!empty($targetUrl)) {
            $this->redirects++;

            $this->io->writeError('', true, IOInterface::DEBUG);
            $this->io->writeError(sprintf('Following redirect (%u) %s', $this->redirects, $targetUrl), true, IOInterface::DEBUG);

            $additionalOptions['redirects'] = $this->redirects;

            return $this->get($this->originUrl, $targetUrl, $additionalOptions, $this->fileName, $this->progress);
        }

        if (!$this->retry) {
            $e = new TransportException('The "' . $this->fileUrl . '" file could not be downloaded, got redirect without Location (' . $http_response_header[0] . ')');
            $e->setHeaders($http_response_header);
            $e->setResponse($result);

            throw $e;
        }

        return false;
    }

    private function getCertificateCnAndFp($url, $options)
    {
        if (PHP_VERSION_ID >= 50600) {
            throw new \BadMethodCallException(sprintf(
                '%s must not be used on PHP >= 5.6',
                __METHOD__
            ));
        }

        $context = StreamContextFactory::getContext($url, $options, array('options' => array(
            'ssl' => array(
                'capture_peer_cert' => true,
                'verify_peer' => false,
            ),),
        ));


        if (false === $handle = @fopen($url, 'rb', false, $context)) {
            return;
        }


        fclose($handle);
        $handle = null;

        $params = stream_context_get_params($context);

        if (!empty($params['options']['ssl']['peer_certificate'])) {
            $peerCertificate = $params['options']['ssl']['peer_certificate'];

            if (TlsHelper::checkCertificateHost($peerCertificate, parse_url($url, PHP_URL_HOST), $commonName)) {
                return array(
                    'cn' => $commonName,
                    'fp' => TlsHelper::getCertificateFingerprint($peerCertificate),
                );
            }
        }
    }

    public function getContents($originUrl, $fileUrl, $progress = true, $options = array())
    {
        return $this->get($originUrl, $fileUrl, $options, null, $progress);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = array_replace_recursive($this->options, $options);
    }

    public function isTlsDisabled()
    {
        return $this->disableTls === true;
    }

    public function getLastHeaders()
    {
        return $this->lastHeaders;
    }

    protected function callbackGet($notificationCode, $severity, $message, $messageCode, $bytesTransferred, $bytesMax)
    {
        switch ($notificationCode) {
            case STREAM_NOTIFY_FAILURE:
                if (400 === $messageCode) {


                    throw new TransportException("The '" . $this->fileUrl . "' URL could not be accessed: " . $message, $messageCode);
                }


            case STREAM_NOTIFY_AUTH_REQUIRED:
                if (401 === $messageCode) {

                    if (!$this->retryAuthFailure) {
                        break;
                    }

                    $this->promptAuthAndRetry($messageCode);
                }
                break;

            case STREAM_NOTIFY_AUTH_RESULT:
                if (403 === $messageCode) {

                    if (!$this->retryAuthFailure) {
                        break;
                    }

                    $this->promptAuthAndRetry($messageCode, $message);
                }
                break;

            case STREAM_NOTIFY_FILE_SIZE_IS:
                $this->bytesMax = $bytesMax;
                break;

            case STREAM_NOTIFY_PROGRESS:
                if ($this->bytesMax > 0 && $this->progress) {
                    $progression = min(100, round($bytesTransferred / $this->bytesMax * 100));

                    if ((0 === $progression % 5) && 100 !== $progression && $progression !== $this->lastProgress) {
                        $this->lastProgress = $progression;
                        $this->io->overwriteError("Downloading (<comment>$progression%</comment>)", false);
                    }
                }
                break;

            default:
                break;
        }
    }
}
