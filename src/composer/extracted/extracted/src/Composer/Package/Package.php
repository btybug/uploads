<?php


namespace Composer\Package;

use Composer\Package\Version\VersionParser;
use Composer\Util\ComposerMirror;


class Package extends BasePackage
{
    protected $type;
    protected $targetDir;
    protected $installationSource;
    protected $sourceType;
    protected $sourceUrl;
    protected $sourceReference;
    protected $sourceMirrors;
    protected $distType;
    protected $distUrl;
    protected $distReference;
    protected $distSha1Checksum;
    protected $distMirrors;
    protected $version;
    protected $prettyVersion;
    protected $releaseDate;
    protected $extra = array();
    protected $binaries = array();
    protected $dev;
    protected $stability;
    protected $notificationUrl;


    protected $requires = array();

    protected $conflicts = array();

    protected $provides = array();

    protected $replaces = array();

    protected $devRequires = array();
    protected $suggests = array();
    protected $autoload = array();
    protected $devAutoload = array();
    protected $includePaths = array();
    protected $archiveExcludes = array();


    public function __construct($name, $version, $prettyVersion)
    {
        parent::__construct($name);

        $this->version = $version;
        $this->prettyVersion = $prettyVersion;

        $this->stability = VersionParser::parseStability($version);
        $this->dev = $this->stability === 'dev';
    }


    public function isDev()
    {
        return $this->dev;
    }

    public function getType()
    {
        return $this->type ?: 'library';
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getStability()
    {
        return $this->stability;
    }

    public function getTargetDir()
    {
        if (null === $this->targetDir) {
            return;
        }

        return ltrim(preg_replace('{ (?:^|[\\\\/]+) \.\.? (?:[\\\\/]+|$) (?:\.\.? (?:[\\\\/]+|$) )*}x', '/', $this->targetDir), '/');
    }

    public function setTargetDir($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function setExtra(array $extra)
    {
        $this->extra = $extra;
    }

    public function getBinaries()
    {
        return $this->binaries;
    }

    public function setBinaries(array $binaries)
    {
        $this->binaries = $binaries;
    }

    public function getInstallationSource()
    {
        return $this->installationSource;
    }

    public function setInstallationSource($type)
    {
        $this->installationSource = $type;
    }

    public function getSourceType()
    {
        return $this->sourceType;
    }

    public function setSourceType($type)
    {
        $this->sourceType = $type;
    }

    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl($url)
    {
        $this->sourceUrl = $url;
    }

    public function getSourceReference()
    {
        return $this->sourceReference;
    }

    public function setSourceReference($reference)
    {
        $this->sourceReference = $reference;
    }

    public function getSourceMirrors()
    {
        return $this->sourceMirrors;
    }

    public function setSourceMirrors($mirrors)
    {
        $this->sourceMirrors = $mirrors;
    }

    public function getSourceUrls()
    {
        return $this->getUrls($this->sourceUrl, $this->sourceMirrors, $this->sourceReference, $this->sourceType, 'source');
    }

    protected function getUrls($url, $mirrors, $ref, $type, $urlType)
    {
        if (!$url) {
            return array();
        }
        $urls = array($url);
        if ($mirrors) {
            foreach ($mirrors as $mirror) {
                if ($urlType === 'dist') {
                    $mirrorUrl = ComposerMirror::processUrl($mirror['url'], $this->name, $this->version, $ref, $type);
                } elseif ($urlType === 'source' && $type === 'git') {
                    $mirrorUrl = ComposerMirror::processGitUrl($mirror['url'], $this->name, $url, $type);
                } elseif ($urlType === 'source' && $type === 'hg') {
                    $mirrorUrl = ComposerMirror::processHgUrl($mirror['url'], $this->name, $url, $type);
                }
                if (!in_array($mirrorUrl, $urls)) {
                    $func = $mirror['preferred'] ? 'array_unshift' : 'array_push';
                    $func($urls, $mirrorUrl);
                }
            }
        }

        return $urls;
    }

    public function getDistType()
    {
        return $this->distType;
    }

    public function setDistType($type)
    {
        $this->distType = $type;
    }

    public function getDistUrl()
    {
        return $this->distUrl;
    }

    public function setDistUrl($url)
    {
        $this->distUrl = $url;
    }

    public function getDistReference()
    {
        return $this->distReference;
    }

    public function setDistReference($reference)
    {
        $this->distReference = $reference;
    }

    public function getDistSha1Checksum()
    {
        return $this->distSha1Checksum;
    }

    public function setDistSha1Checksum($sha1checksum)
    {
        $this->distSha1Checksum = $sha1checksum;
    }

    public function getDistMirrors()
    {
        return $this->distMirrors;
    }

    public function setDistMirrors($mirrors)
    {
        $this->distMirrors = $mirrors;
    }

    public function getDistUrls()
    {
        return $this->getUrls($this->distUrl, $this->distMirrors, $this->distReference, $this->distType, 'dist');
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getPrettyVersion()
    {
        return $this->prettyVersion;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    public function getRequires()
    {
        return $this->requires;
    }

    public function setRequires(array $requires)
    {
        $this->requires = $requires;
    }

    public function getConflicts()
    {
        return $this->conflicts;
    }

    public function setConflicts(array $conflicts)
    {
        $this->conflicts = $conflicts;
    }

    public function getProvides()
    {
        return $this->provides;
    }

    public function setProvides(array $provides)
    {
        $this->provides = $provides;
    }

    public function getReplaces()
    {
        return $this->replaces;
    }

    public function setReplaces(array $replaces)
    {
        $this->replaces = $replaces;
    }

    public function getDevRequires()
    {
        return $this->devRequires;
    }

    public function setDevRequires(array $devRequires)
    {
        $this->devRequires = $devRequires;
    }

    public function getSuggests()
    {
        return $this->suggests;
    }

    public function setSuggests(array $suggests)
    {
        $this->suggests = $suggests;
    }

    public function getAutoload()
    {
        return $this->autoload;
    }

    public function setAutoload(array $autoload)
    {
        $this->autoload = $autoload;
    }

    public function getDevAutoload()
    {
        return $this->devAutoload;
    }

    public function setDevAutoload(array $devAutoload)
    {
        $this->devAutoload = $devAutoload;
    }

    public function getIncludePaths()
    {
        return $this->includePaths;
    }

    public function setIncludePaths(array $includePaths)
    {
        $this->includePaths = $includePaths;
    }

    public function getNotificationUrl()
    {
        return $this->notificationUrl;
    }

    public function setNotificationUrl($notificationUrl)
    {
        $this->notificationUrl = $notificationUrl;
    }

    public function getArchiveExcludes()
    {
        return $this->archiveExcludes;
    }

    public function setArchiveExcludes(array $excludes)
    {
        $this->archiveExcludes = $excludes;
    }

    public function replaceVersion($version, $prettyVersion)
    {
        $this->version = $version;
        $this->prettyVersion = $prettyVersion;

        $this->stability = VersionParser::parseStability($version);
        $this->dev = $this->stability === 'dev';
    }
}
