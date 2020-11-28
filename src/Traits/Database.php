<?php namespace Albreis\Kurin\Traits;

use Albreis\Kurin\Database\Connector;
use Albreis\Kurin\Database\MySQL;
use Albreis\Kurin\Database\PostgreSQL;
use Albreis\Kurin\Database\Query;
use Exception;

trait Database
{
  protected static $connection;
  

  protected function __construct(){

  }

  protected function __clone(){
      throw new Exception("Cannot clone a Singleton");
  }
    /**
     * @param Connector $connector 
     * @return Query 
     */
    public static function connect(Connector $connector = null): Query
    {        
      if(static::$connection) {
        return static::$connection;
      }
      if(!$connector) {
        if (DATABASE_DRIVER == 'mysql') {
          $connector = new MySQL;
        } elseif (DATABASE_DRIVER == 'postgre') {
          $connector = new PostgreSQL;
        }
      }
      static::$connection = new Query($connector);
      return static::$connection;
    }
    
}