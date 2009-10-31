<?php 

/**
* Multi Storage Cache
*
* Simple caching abstraction library supporting multiple storage types (APC, XCache, eAccelerator, memcached and file)
*
* @package MultiStorageCache
* @author Tomaž Muraus
* @link http://www.tomaz-muraus.info
* @license GPL
* @version 1.0
*/

namespace MultiStorageCache\Storage;

class APC implements IStorage
{
    public function __construct()
    {
        if (!function_exists('apc_cache_info'))
        {
            throw new \Exception('APC extension not found.');
        }
    }
    
    public function set($key, $value, $ttl = 0)
    {
       return apc_add($key, $value, $ttl);
    }
    
    public function setMulti($items, $ttl = 0)
    {
        foreach ($items as $key => $value)
        {
            apc_add($key, $value, $ttl);
        }
    }
    
    public function get($key)
    {
        return apc_fetch($key);
    }
    
    public function getMulti($keys)
    {
        $items = array();
        foreach ($keys as $key)
        {
            $value = apc_fetch($key);
            
            if ($value !== FALSE)
            {
                $items[$key] = $value;
            }
        }
        
        return $items;
    }
    
    public function delete($key)
    {
        return apc_delete($key);
    }
    
    public function flush()
    {
        return apc_clear_cache('user');
    }
    
    public function stats()
    {
        return apc_cache_info();
    }
}

?>