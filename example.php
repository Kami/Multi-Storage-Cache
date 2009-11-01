<?php

namespace MultiStorageCache;

require_once('MultiStorageCache.php');

// Create a new MultiStorageCache instance (the parameter is the instance of the storage object you want to use - in this example a SQLite3 database)
$cache = new MultiStorageCache(new Storage\SQLite('cache.sqlite'));

// Store array in the cache (with TTL of 60 seconds)
var_dump($cache->set('dummyArray', array('item_1' => 'value 1', 'item_2' => 'value 2'), 60));

// Store object in the cache
class Picture
{
    public $_id;
    public $_title;
    public $_description;
    public $_dateAdded;
    
    public function __construct($id, $title, $description, $dateAdded)
    {
        $this->_id = $id;
        $this->_title = $title;
        $this->_description = $description;
        $this->_dateAdded = $dateAdded;   
    }
}

var_dump($cache->set('dummyObject', new Picture(3, 'Picture #1', 'Sample picture', '2009-30-10')));

// Store multiple items in the cache in one call
var_dump($cache->setMulti(array('item_4' => 'Item 4', 'item_5' => array(0, 1, 2, 3, 4))));

// Read single item from the cache
var_dump($cache->get('item_5'));

// Read multiple items from the cache
var_dump($cache->getMulti(array('dummyArray', 'notExists', 'dummyObject')));

// Delete single item from the cache
var_dump($cache->delete('item_5'));

// Clear the cache
var_dump($cache->flush());

?>