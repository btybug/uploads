<?php


namespace Composer\Downloader;

use Composer\Cache;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Util\ProcessExecutor;
use Composer\Util\RemoteFilesystem;


class XzDownloader extends ArchiveDownloader
{
    protected $process;

    public function __construct(IOInterface $io, Config $config, EventDispatcher $eventDispatcher = null, Cache $cache = null, ProcessExecutor $process = null, RemoteFilesystem $rfs = null)
    {
        $this->process = $process ?: new ProcessExecutor($io);

        parent::__construct($io, $config, $eventDispatcher, $cache, $rfs);
    }

    protected function extract($file, $path)
    {
        $command = 'tar -xJf ' . ProcessExecutor::escape($file) . ' -C ' . ProcessExecutor::escape($path);

        if (0 === $this->process->execute($command, $ignoredOutput)) {
            return;
        }

        $processError = 'Failed to execute ' . $command . "\n\n" . $this->process->getErrorOutput();

        throw new \RuntimeException($processError);
    }


    protected function getFileName(PackageInterface $package, $path)
    {
        return $path . '/' . pathinfo(parse_url($package->getDistUrl(), PHP_URL_PATH), PATHINFO_BASENAME);
    }
}
