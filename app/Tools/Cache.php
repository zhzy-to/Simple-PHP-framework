<?php

namespace App\Tools;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;

class Cache
{
    /**
     * @var Repository
     */
    private $cache;

    public function getCache()
    {
        if (! $this->cache) {
            // Filesystem
            $files = new Filesystem;
            // FileStore
            $store = new FileStore($files, BASE_PATH . '/storage/cache');
            // Repository
            $this->cache = new Repository($store);
        }

        return $this->cache;
    }
}