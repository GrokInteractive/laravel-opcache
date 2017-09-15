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

        $phpFiles = array_filter(File::allFiles(base_path()), function($file) {
            return File::extension($file) == 'php';
        });

        $compiledCount = array_reduce($phpFiles, function($phpFilesCompiled, $file) {
            if (@opcache_compile_file($file)) {
                return $phpFilesCompiled + 1;
            }

            return $phpFilesCompiled;
        }, 0);

        return [
            'php_files_discovered' => count($phpFiles),
            'php_files_compiled' => $compiledCount,
        ];
    }
}
