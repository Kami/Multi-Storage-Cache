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

class SQLite implements IStorage
{
    protected $_connection;
    
    public function __construct($fileName = 'cache.sqlite')
    { 
        if (!extension_loaded('sqlite3'))
        {
            throw new \Exception('SQLite3 extension not found.');
        }
        
        if (($this->_connection = new \SQLite3($fileName)) === FALSE)
        {
            throw new \Exception('Could not connect to SQLite database.');
        }

        $this->_createSchema();
    }
    
    public function set($key, $value, $ttl = 0)
    {
        $expire = ($ttl == 0 ? NULL : (time() + $ttl));
        if ($this->_connection->query(sprintf("INSERT OR REPLACE INTO cache (key, value, expire) VALUES('%s', '%s', '%s')", sqlite_escape_string($key), serialize($value), $expire)) === FALSE)
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
            $status = $this->_connection->query(sprintf("INSERT OR REPLACE INTO cache (key, value, expire) VALUES('%s', '%s', '%s')", sqlite_escape_string($key), serialize($value), $expire));
        }
        
        if ($status === FALSE)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function get($key)
    {
        if (($result = $this->_connection->querySingle(sprintf("SELECT value FROM cache WHERE key = '%s' AND expire >= '%s'", sqlite_escape_string($key), time()))) === FALSE)
        {
            return FALSE;
        }
        
        return unserialize($result);
    }
    
    public function getMulti($keys)
    {
        $result = $this->_connection->query(sprintf("SELECT key, value FROM cache WHERE key IN ('%s') AND expire >= '%s'", implode("', '", $keys), time()));
        
        $items = array();
        while ($row = $result->fetchArray())
        {
            $items[$row['key']] = unserialize($row['value']);
        }
        
        return $items;
    }
    
    public function delete($key)
    {
        if ($this->_connection->query(sprintf("DELETE FROM cache WHERE key = '%s'", sqlite_escape_string($key))) === FALSE)
        {
            return FALSE;
        }

        return TRUE;
    }
    
    public function purge()
    {
        if ($this->_connection->query(sprintf("DELETE FROM cache WHERE expire < %s", time())) === FALSE)
        {
            return FALSE;
        }

        return TRUE;
    }
    
    public function flush()
    {
        if ($this->_connection->query("DELETE FROM cache") === FALSE)
        {
            return FALSE;
        }

        return TRUE;
    }
    
    public function stats()
    {
        $stats = array();
        
        return $stats;
    }
    
    protected function _createSchema()
    {
        $statement = "CREATE TABLE IF NOT EXISTS cache
        (
        	key VARCHAR(200) PRIMARY KEY,
        	value TEXT NOT NULL,
        	expire INT NOT NULL
        )";
        
        if ($this->_connection->query($statement) === FALSE)
        {
            throw new \Exception("Could not create the table schema.");
        }
    }
}

?>