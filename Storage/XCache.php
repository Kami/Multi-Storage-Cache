<?php 

/**
* Multi Storage Cache
*
* Simple caching abstraction library supporting multiple storage types
*
* @package MultiStorageCache
* @author Tomaž Muraus
* @link http://www.tomaz-muraus.info
* @license GPL
*/

namespace MultiStorageCache\Storage;

class XCache implements IStorage
{
    public function __construct()
    {
        if (!function_exists('xcache_info'))
        {
            throw new \Exception('XCache extension not found.');
        }
    }
    
    public function set($key, $value, $ttl = 0)
    {
        return xcache_set($key, $value, $ttl);
    }
    
    public function setMulti($items, $ttl = 0)
    {
        $status = FALSE;
        foreach ($items as $key => $value)
        {
            $status = xcache_set($key, $value, $ttl);
        }
        
        return $status;
    }
    
    public function get($key)
    {
        if (xcache_isset($key))
        {
            return xcache_get($key);
        }
        
        return FALSE;
    }
    
    public function getMulti($keys)
    {
        $items = array();
        foreach ($keys as $key)
        {
            if (xcache_isset($key))
            {
                $items[$key] = $value;
            }
        }
        
        return $items;
    }
    
    public function delete($key)
    {
        return xcache_unset($key);
    }
    
    public function flush()
    {
        $count = xcache_count(XC_TYPE_VAR);
        for ($i = 0; $i < $count; $i++)
        {
            if (!xcache_clear_cache(XC_TYPE_VAR, $i))
            {
                return FALSE;
            }
            
            xcache_clear_cache(XC_TYPE_VAR, $i);
        }
        
        return TRUE;
    }
    
    public function stats()
    {
        return xcache_info();
    }
}

?>