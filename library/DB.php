<?php
namespace library;

class DB {
    protected static $adapter;
    
    public static function setAdapter(\PDO $adapter){
        static::$adapter = $adapter;
    }
   
    public static function getAdapter(){
        if(isset(static::$adapter)) return static::$adapter;
        else throw new Exception('Adapter requested but not set');
    }
}

?>
