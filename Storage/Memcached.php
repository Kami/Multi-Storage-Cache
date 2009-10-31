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

class Memcached implements IStorage
{
    protected $_memcached;
    
    public function __construct($ip = '127.0.0.1', $port = '11211')
    {
        if (!class_exists('Memcached'))
        {
            throw new \Exception('memcached extension not found.');
        }
        
        $this->_memcached = new \Memcached();
        $this->_memcached->addServer($ip, $port);
    }
    
    public function set($key, $value, $ttl = 0)
    {
        return $this->_memcached->set($key, $value, $ttl);
    }
    
    public function setMulti($items, $ttl = 0)
    {
        return $this->_memcached->setMulti($items, $ttl);
    }
    
    public function get($key)
    {
        return $this->_memcached->get($key);
    }
    
    public function getMulti($keys)
    {
        return $this->_memcached->getMulti($keys);
    }
    
    public function delete($key)
    {
        return $this->_memcached->delete($key);
    }
    
    public function flush()
    {
        return $this->_memcached->flush();
    }
    
    public function stats()
    {
        return $this->_memcached->getStats();
    }
}

?>