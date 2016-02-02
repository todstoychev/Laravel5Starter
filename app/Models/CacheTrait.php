<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * Cache trait
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
trait CacheTrait
{
    /**
     * Gets the cache engine instance. This method is used when the cache is referenced
     * with tags. In some cases like if the active cache driver is file, then the tags
     * method does not work and throws exception.
     *
     * @param array $cacheTags
     * @return mixed
     */
    protected static function getCacheInstance(array $cacheTags)
    {
        try {
            return Cache::tags($cacheTags);
        } catch (\BadMethodCallException $e) {
            return Cache::getFacadeRoot();
        }
    }
}