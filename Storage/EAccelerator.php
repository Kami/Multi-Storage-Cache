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

class EAccelerator implements IStorage
{
    public function __construct()
    {
        if (!function_exists('eaccelerator_info'))
        {
            throw new \Exception('eaccelerator extension not found.');
        }
    }
    
    public function set($key, $value, $ttl = 0)
    {
        return eaccelerator_put($key, $value, $ttl);
    }
    
    public function setMulti($items, $ttl = 0)
    {
        $status = FALSE;
        foreach ($items as $key => $value)
        {
            $status = eaccelerator_put($key, $value, $ttl);
        }
        
        return $status;
    }
    
    public function get($key)
    {
        $data =  eaccelerator_get($key);
        
        if ($data !== NULL)
        {
            return $data;
        }
        
        return FALSE;
    }
    
    public function getMulti($keys)
    {
        $items = array();
        foreach ($keys as $key)
        {
            $value = eaccelerator_get($key);
            if ($value !== NULL)
            {
                $items[$key] = $value;
            }
        }
        
        return $items;
    }
    
    public function delete($key)
    {
        return eaccelerator_rm($key);
    }
    
    public function flush()
    {
        return eaccelerator_clean();
    }
    
    public function stats()
    {
        return eaccelerator_info();
    }
}

?>