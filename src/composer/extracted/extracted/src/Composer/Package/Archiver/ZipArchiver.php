<?php


namespace Composer\Package\Archiver;

use Composer\Util\Filesystem;
use ZipArchive;


class ZipArchiver implements ArchiverInterface
{
    protected static $formats = array(
        'zip' => 1,
    );


    public function archive($sources, $target, $format, array $excludes = array(), $ignoreFilters = false)
    {
        $fs = new Filesystem();
        $sources = $fs->normalizePath($sources);

        $zip = new ZipArchive();
        $res = $zip->open($target, ZipArchive::CREATE);
        if ($res === true) {
            $files = new ArchivableFilesFinder($sources, $excludes, $ignoreFilters);
            foreach ($files as $file) {

                $filepath = strtr($file->getPath() . "/" . $file->getFilename(), '\\', '/');
                $localname = str_replace($sources . '/', '', $filepath);
                if ($file->isDir()) {
                    $zip->addEmptyDir($localname);
                } else {
                    $zip->addFile($filepath, $localname);
                }
            }
            if ($zip->close()) {
                return $target;
            }
        }
        $message = sprintf("Could not create archive '%s' from '%s': %s",
            $target,
            $sources,
            $zip->getStatusString()
        );
        throw new \RuntimeException($message);
    }


    public function supports($format, $sourceType)
    {
        return isset(static::$formats[$format]) && $this->compressionAvailable();
    }

    private function compressionAvailable()
    {
        return class_exists('ZipArchive');
    }
}
