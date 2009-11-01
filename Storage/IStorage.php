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

interface IStorage
{
    /**
     * Store single item in the cache.
     * 
     * @param string $key Key
     * @param mixed $value Data
     * @param integer $ttl Time to live (in seconds)
     * 
     * @return boolean TRUE on success, FALSE otherwise
     */
    public function set($key, $value, $ttl = 0);
    
    /**
     * Store multiple items in the cache.
     * 
     * @param array $items Items (key-value pairs)
     * @param integer $ttl Time to live (in seconds)
     * 
     * @return boolean TRUE on success, FALSE otherwise
     */
    public function setMulti($items, $ttl = 0);
    
    /**
     * Read single item from the cache.
     * 
     * @param string $key Key
     * 
     * @return mixed
     */
    public function get($key);
    
    /**
     * Read multiple items from the cache.
     * 
     * @param array $keys Keys
     * 
     * @return array Key-value pairs
     */
    public function getMulti($keys);
    
    /**
     * Delete single item from the cache.
     * 
     * @param string $key Key
     * 
     * @return boolean TRUE on success, FALSE otherwise.
     */
    public function delete($key);
    
    /**
     * Delete all items from the cache.
     * 
     * @return boolean TRUE on success, FALSE otherwise.
     */
    public function flush();
    
    /**
     * Read cache info.
     * 
     * @return array
     */
    public function stats();
}

?>