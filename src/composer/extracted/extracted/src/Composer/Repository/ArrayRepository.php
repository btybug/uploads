<?php


namespace Composer\Repository;

use Composer\Package\AliasPackage;
use Composer\Package\CompletePackageInterface;
use Composer\Package\PackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\ConstraintInterface;


class ArrayRepository extends BaseRepository
{

    protected $packages;

    public function __construct(array $packages = array())
    {
        foreach ($packages as $package) {
            $this->addPackage($package);
        }
    }

    public function addPackage(PackageInterface $package)
    {
        if (null === $this->packages) {
            $this->initialize();
        }
        $package->setRepository($this);
        $this->packages[] = $package;

        if ($package instanceof AliasPackage) {
            $aliasedPackage = $package->getAliasOf();
            if (null === $aliasedPackage->getRepository()) {
                $this->addPackage($aliasedPackage);
            }
        }
    }

    protected function initialize()
    {
        $this->packages = array();
    }

    public function findPackage($name, $constraint)
    {
        $name = strtolower($name);

        if (!$constraint instanceof ConstraintInterface) {
            $versionParser = new VersionParser();
            $constraint = $versionParser->parseConstraints($constraint);
        }

        foreach ($this->getPackages() as $package) {
            if ($name === $package->getName()) {
                $pkgConstraint = new Constraint('==', $package->getVersion());
                if ($constraint->matches($pkgConstraint)) {
                    return $package;
                }
            }
        }

        return null;
    }

    public function getPackages()
    {
        if (null === $this->packages) {
            $this->initialize();
        }

        return $this->packages;
    }

    public function findPackages($name, $constraint = null)
    {

        $name = strtolower($name);
        $packages = array();

        if (null !== $constraint && !$constraint instanceof ConstraintInterface) {
            $versionParser = new VersionParser();
            $constraint = $versionParser->parseConstraints($constraint);
        }

        foreach ($this->getPackages() as $package) {
            if ($name === $package->getName()) {
                $pkgConstraint = new Constraint('==', $package->getVersion());
                if (null === $constraint || $constraint->matches($pkgConstraint)) {
                    $packages[] = $package;
                }
            }
        }

        return $packages;
    }

    public function search($query, $mode = 0, $type = null)
    {
        $regex = '{(?:' . implode('|', preg_split('{\s+}', $query)) . ')}i';

        $matches = array();
        foreach ($this->getPackages() as $package) {
            $name = $package->getName();
            if (isset($matches[$name])) {
                continue;
            }
            if (preg_match($regex, $name)
                || ($mode === self::SEARCH_FULLTEXT && $package instanceof CompletePackageInterface && preg_match($regex, implode(' ', (array)$package->getKeywords()) . ' ' . $package->getDescription()))
            ) {
                if (null !== $type && $package->getType() !== $type) {
                    continue;
                }

                $matches[$name] = array(
                    'name' => $package->getPrettyName(),
                    'description' => $package instanceof CompletePackageInterface ? $package->getDescription() : null,
                );
            }
        }

        return array_values($matches);
    }

    public function hasPackage(PackageInterface $package)
    {
        $packageId = $package->getUniqueName();

        foreach ($this->getPackages() as $repoPackage) {
            if ($packageId === $repoPackage->getUniqueName()) {
                return true;
            }
        }

        return false;
    }

    public function removePackage(PackageInterface $package)
    {
        $packageId = $package->getUniqueName();

        foreach ($this->getPackages() as $key => $repoPackage) {
            if ($packageId === $repoPackage->getUniqueName()) {
                array_splice($this->packages, $key, 1);

                return;
            }
        }
    }

    public function count()
    {
        return count($this->packages);
    }

    protected function createAliasPackage(PackageInterface $package, $alias, $prettyAlias)
    {
        return new AliasPackage($package instanceof AliasPackage ? $package->getAliasOf() : $package, $alias, $prettyAlias);
    }
}
