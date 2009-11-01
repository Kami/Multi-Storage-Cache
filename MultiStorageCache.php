<?php

/**
* Multi Storage Cache
*
* Simple caching abstraction library supporting multiple storage types (APC, XCache, eAccelerator, memcached, SQLite3 and file)
*
* @package MultiStorageCache
* @author Tomaž Muraus
* @link http://www.tomaz-muraus.info
* @license GPL
* @version 1.1
*/

namespace MultiStorageCache;

function classLoader($className)
{
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $className = str_replace(__NAMESPACE__ . DIRECTORY_SEPARATOR, '', $className);
    require_once($className . '.php');
}

spl_autoload_register(__NAMESPACE__ . '\classLoader');

class MultiStorageCache
{
    protected $_cacheType;
    
    public function __construct(Storage\IStorage $cacheType)
    {
        $this->_cacheType = $cacheType;    
    }
    
    public function __call($method, $args)
    {
        if (method_exists($this->_cacheType, $method) && is_callable(array($this->_cacheType, $method)))
        {
            return call_user_func_array(array($this->_cacheType, $method), $args);
        }
        else
        {
            throw new \Exception('This method does not exist.');
        }
    }
}

?>