<?php namespace Albreis\Kurin\Database;

use PDO;

/** @package Albreis\Kurin\Database */
class PostgreSQL extends Connector {
  
  private $options = array(
  );

  /**
   * @param mixed $dsn 
   * @param mixed $user 
   * @param mixed $pass 
   * @param array $options 
   * @return PDO 
   */
  public function connect(?array $options = []): PDO {
    $connection = new PDO('pgsql:host=' . DATABASE_HOST . ';port=' . DATABASE_PORT . ';dbname=' . DATABASE_DB, DATABASE_USER, DATABASE_PASS, array_merge($this->options, $options));
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);
    return $connection;
  }
}