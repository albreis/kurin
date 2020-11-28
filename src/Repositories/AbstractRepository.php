<?php namespace Albreis\Kurin\Repositories;

use Albreis\Kurin\Database\MySQL;
use Albreis\Kurin\Database\Query;
use Albreis\Kurin\Interfaces\IRepository;
use Albreis\Kurin\Traits\Database;

/** @package Albreis\Kurin\Repositories */
abstract class AbstractRepository implements IRepository {  

  protected ?string $model = 'stdClass';

  protected object $connection;

  protected array $result = [];

  /**
   * @param null|Query $connection 
   * @return void 
   */
  public function __construct(?Query $connection = null) {
    if(!$connection) {
      $connection = Database::connect();
    }
    $connection->setModel($this->model);
    $this->connection = $connection;
  }

  /**
   * @param string $model 
   * @return $this 
   */
  public function setModel(?string $model) {
    $this->model = $model;
    return $this;
  }

  public function setOrderBy(string $field, string $pos) { }

  public function getOrderBy(): array { 
    return [];
  }

  public function findBy(string $field, string $value): ?object
  {
    $sql = "SELECT a.* FROM {$this->table} a WHERE {$field} = :value ";
    return $this->connection->queryOne($sql, ['value' => $value]);
  }

  public function exists(object $object, string $field): bool
  {
    $sql = "SELECT count(*) as total FROM {$this->table} a WHERE {$field} = :value AND id != :id";
    $result = $this->connection->queryOne($sql, ['value' => $object->{$field}, 'id' => $object->id]);
    return $result ? $result->total : false;
  }
  
}