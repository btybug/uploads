<?php


namespace Composer;

use Composer\Autoload\AutoloadGenerator;
use Composer\Downloader\DownloadManager;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\Package\Locker;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\PluginManager;
use Composer\Repository\RepositoryManager;


class Composer
{
    const VERSION = '1.4.2';
    const BRANCH_ALIAS_VERSION = '';
    const RELEASE_DATE = '2017-05-17 08:17:52';


    private $package;


    private $locker;


    private $repositoryManager;


    private $downloadManager;


    private $installationManager;


    private $pluginManager;


    private $config;


    private $eventDispatcher;


    private $autoloadGenerator;

    public function getPackage()
    {
        return $this->package;
    }

    public function setPackage(RootPackageInterface $package)
    {
        $this->package = $package;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    public function getLocker()
    {
        return $this->locker;
    }

    public function setLocker(Locker $locker)
    {
        $this->locker = $locker;
    }

    public function getRepositoryManager()
    {
        return $this->repositoryManager;
    }

    public function setRepositoryManager(RepositoryManager $manager)
    {
        $this->repositoryManager = $manager;
    }

    public function getDownloadManager()
    {
        return $this->downloadManager;
    }

    public function setDownloadManager(DownloadManager $manager)
    {
        $this->downloadManager = $manager;
    }

    public function getInstallationManager()
    {
        return $this->installationManager;
    }

    public function setInstallationManager(InstallationManager $manager)
    {
        $this->installationManager = $manager;
    }

    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    public function setPluginManager(PluginManager $manager)
    {
        $this->pluginManager = $manager;
    }

    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getAutoloadGenerator()
    {
        return $this->autoloadGenerator;
    }

    public function setAutoloadGenerator(AutoloadGenerator $autoloadGenerator)
    {
        $this->autoloadGenerator = $autoloadGenerator;
    }
}
