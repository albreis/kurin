<?php namespace Albreis\Kurin\Managers;

use Albreis\Kurin\Database\MySQL;
use Albreis\Kurin\Database\Query;
use Albreis\Kurin\Interfaces\IStorageManager;
use Albreis\Kurin\Traits\Database;

/** @package Albreis\Kurin\Managers */
abstract class StorageManager implements IStorageManager {

  protected $storage;
  protected $objects;

  /**
   * @param null|object $storage 
   * @return void 
   */
  public function __construct(?object $storage = null) {
    $this->setStorage($storage??Database::connect());
  }

  /**
   * @param object $object 
   * @return mixed 
   */
  public function addObject(object $object) { 
    $this->objects[] = $object;
    return $this;
  }

  /**
   * @param array $objects 
   * @return mixed 
   */
  public function addObjects(array $objects) { 
    $this->objects = array_merge($this->objects, $objects);
    return $this;
  }

  /**
   * @param null|Query $storage 
   * @return $this 
   */
  public function setStorage(?Query $storage = null) {
    if(!$storage) {
      $storage = Database::connect();
    }
    $this->storage = $storage;
    return $this;
  }

  /** @return mixed  */
  abstract function store(object $project, bool $return =  true): ?int;

  /**
   * @param bool $return 
   * @return null|int 
   * @throws PDOException 
   */
  public function storeAll(bool $return =  true): ?array 
  {
    $ids = [];
    while($this->objects) {
      $object = array_shift($this->objects);
      $ids[] = $this->store($object, $return);
    }
    if($return) {
      return $ids;
    }
    return null;
  }

}