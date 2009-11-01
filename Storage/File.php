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

class File implements IStorage
{
    protected $_directory;
    protected $_filePrefix;
    protected $_keyPrefix;
    
    public function __construct($directory, $filePrefix = 'cache', $keyPrefix = 'key')
    {
        if (!is_writable($directory))
        {
            throw new \Exception('Directory ' . $directory . ' does not exist or it is not writable');
        }
        
        $this->_directory = $directory;
        $this->_filePrefix = (substr($filePrefix, -1, 1) != '_' ? $filePrefix . '_' : $filePrefix);
        $this->_keyPrefix = (substr($keyPrefix, -2, 2) != '::' ? $keyPrefix . '::' : $keyPrefix);
    }
    
    public function set($key, $value, $ttl = 0)
    {
        $file = $this->_getFilePathFromKey($this->_getKeyWithPrefix($key));
        $data = array('value' => $value, 'expire' => ($ttl == 0 ? NULL : (time() + $ttl)));
        
        if (file_put_contents($file, serialize($data)) === FALSE)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function setMulti($items, $ttl = 0)
    {
        $status = FALSE;
        $expire = ($ttl == 0 ? NULL : (time() + $ttl));
        
        foreach ($items as $key => $value)
        {
            $file = $this->_getFilePathFromKey($this->_getKeyWithPrefix($key));
            $data = array('value' => $value, 'expire' => $expire);
        
            $status = file_put_contents($file, serialize($data));
        }
        
        if ($status === FALSE)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function get($key)
    {
        $file = $this->_getFilePathFromKey($this->_getKeyWithPrefix($key));
        
        if (!file_exists($file))
        {
            return FALSE;
        }
        
        $data = unserialize(file_get_contents($file));

        if ($data['expire'] !== NULL && time() > $data['expire'])
        {
            unlink($file);
            return FALSE;
        }
        else
        {
            return $data['value'];
        }
    }
    
    public function getMulti($keys)
    {
        $items = array();
        foreach ($keys as $key)
        {
            $file = $this->_getFilePathFromKey($this->_getKeyWithPrefix($key));
            
            if (file_exists($file))
            {
                $data = unserialize(file_get_contents($file));
        
                if ($data['expire'] === NULL || time() < $data['expire'])
                {
                    $item[$key] = $data['value'];
                    array_push($items, $item);
                }
            }
        }
        
        return $items;
    }
    
    public function delete($key)
    {
        return unlink($this->_getFilePathFromKey($this->_getKeyWithPrefix($key)));
    }
    
    public function flush()
    {
        $files = scandir($this->_directory);
        
        foreach ($files as $file)
        {
            if (strpos($file, $this->_filePrefix, 0) !== FALSE)
            {
                unlink($this->_directory . $file);
            }
        }
        
        return TRUE;
    }
    
    public function stats()
    {
        $stats = array();
        
        return $stats;
    }
    
    protected function _getKeyWithPrefix($key)
    {
        return $this->_keyPrefix . $key;
    }
    
    protected function _getFilePathFromKey($key)
    {
        return $this->_directory . $this->_filePrefix . md5($key);
    }
}

?>