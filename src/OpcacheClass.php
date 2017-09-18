<?php

namespace Appstract\Opcache;

use File;

/**
 * Class OpcacheClass.
 */
class OpcacheClass
{
    /**
     * OpcacheClass constructor.
     */
    public function __construct()
    {
        // constructor body
    }

    /**
     * Clear the cache.
     *
     * @return bool
     */
    public function clear()
    {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }

        return false;
    }

    /**
     * Get configuration values.
     *
     * @return mixed
     */
    public function getConfig()
    {
        if (function_exists('opcache_get_configuration')) {
            $config = opcache_get_configuration();

            return $config ?: false;
        }

        return false;
    }

    /**
     * Get status info.
     *
     * @return mixed
     */
    public function getStatus()
    {
        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status(false);

            return $status ?: false;
        }

        return false;
    }

    /**
     * Precompile app.
     *
     * @return bool | array
     */
    public function optimize()
    {
        if (! function_exists('opcache_compile_file')) {
            throw new Exception("The function 'opcache_compile_file' is not present on this system");
        }

        $phpFilesCompiled = 0;

        $phpFilesIterator = $this->phpFiles();

        foreach($this->phpFiles() as $filepath => $dontUse) {
            if (in_array($filepath, get_included_files())) {
                continue;
            }

            if (@opcache_compile_file($filepath)) {
                $phpFilesCompiled = $phpFilesCompiled + 1;
            }
        }

        return [
            'php_files_compiled' => $phpFilesCompiled,
        ];
    }

    private function phpFiles() {
        $directory = new \RecursiveDirectoryIterator(base_path());
        $iterator = new \RecursiveIteratorIterator($directory);
        return new \RegexIterator($iterator, '/.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
    }
}
