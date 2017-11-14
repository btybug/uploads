<?php


namespace Composer\Installer;

use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;


class SuggestedPackagesReporter
{


    protected $suggestedPackages = array();


    private $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function addSuggestionsFromPackage(PackageInterface $package)
    {
        $source = $package->getPrettyName();
        foreach ($package->getSuggests() as $target => $reason) {
            $this->addPackage(
                $source,
                $target,
                $reason
            );
        }

        return $this;
    }

    public function addPackage($source, $target, $reason)
    {
        $this->suggestedPackages[] = array(
            'source' => $source,
            'target' => $target,
            'reason' => $reason,
        );

        return $this;
    }

    public function output(RepositoryInterface $installedRepo = null)
    {
        $suggestedPackages = $this->getPackages();
        $installedPackages = array();
        if (null !== $installedRepo && !empty($suggestedPackages)) {
            foreach ($installedRepo->getPackages() as $package) {
                $installedPackages = array_merge(
                    $installedPackages,
                    $package->getNames()
                );
            }
        }

        foreach ($suggestedPackages as $suggestion) {
            if (in_array($suggestion['target'], $installedPackages)) {
                continue;
            }

            $this->io->writeError(sprintf(
                '%s suggests installing %s (%s)',
                $suggestion['source'],
                $this->escapeOutput($suggestion['target']),
                $this->escapeOutput($suggestion['reason'])
            ));
        }

        return $this;
    }

    public function getPackages()
    {
        return $this->suggestedPackages;
    }

    private function escapeOutput($string)
    {
        return OutputFormatter::escape(
            $this->removeControlCharacters($string)
        );
    }


    private function removeControlCharacters($string)
    {
        return preg_replace(
            '/[[:cntrl:]]/',
            '',
            str_replace("\n", ' ', $string)
        );
    }
}
