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

class Memcache implements IStorage
{
    protected $_memcache;
    
    public function __construct($ip = '127.0.0.1', $port = '11211', $persistent = TRUE)
    {
        if (!class_exists('Memcache'))
        {
            throw new \Exception('memcache extension not found.');
        }
        
        $this->_memcache = new \Memcache();
        $this->_memcache->addServer($ip, $port, $persistent);
    }
    
    public function set($key, $value, $ttl = 0)
    {
        return $this->_memcache->set($key, $value, 0, $ttl);
    }
    
    public function setMulti($items, $ttl = 0)
    {
        $status = FALSE;
        foreach ($items as $key => $value)
        {
            $status = $this->_memcache->set($key, $value, 0, $ttl);
        }
        
        return $status;
    }
    
    public function get($key)
    {
        return $this->_memcache->get($key);
    }
    
    public function getMulti($keys)
    {
        return $this->_memcache->get($keys);
    }
    
    public function delete($key)
    {
        return $this->_memcache->delete($key);
    }
    
    public function flush()
    {
        return $this->_memcache->flush();
    }
    
    public function stats()
    {
        return $this->_memcache->getStats();
    }
}

?>