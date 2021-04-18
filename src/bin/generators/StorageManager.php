<?php declare(strict_types=1);
namespace Albreis\DummyApp\Managers;

use Albreis\Kurin\Managers\StorageManager;
use Albreis\DummyApp\Models\DummyClass;
use DateTime;
use ReflectionClass;
use ReflectionProperty;

/** @package Albreis\Kurin\Managers */
class DummyClasssStorageManager extends StorageManager {

  protected $table = 'dummy_table';

  /**
   * @param bool $return 
   * @return null|object 
   * @throws PDOException 
   */
  public function store(object $object, bool $return =  true): ?int { 
    
    $reflect = new ReflectionClass($object);
    $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
    
    if($object->created_at instanceof DateTime) {
      $object->created_at = $object->created_at->format('Y-m-d H:i:s');
    }

    $fields = array_map(fn($prop) => "\"{$prop->name}\"", $props);
    $values = array_map(fn($prop) => ":$prop->name", $props);
    $fields = implode(', ', $fields);
    $values = implode(', ', $values);
    
    $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";

    $data = get_object_vars($object);
    
    $this->storage->query($sql, $data);
    if($return) {
      return $this->storage->lastInsertId();
    }
    return null;
  }

  public function update(DummyClass $object, bool $return =  true): ?int { 

    $reflect = new ReflectionClass($object);
    $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
    
    if($object->updated_at instanceof DateTime) {
      $object->updated_at = $object->updated_at->format('Y-m-d H:i:s');
    }

    $fields = array_map(fn($prop) => "\"{$prop->name}\"", $props);
    $values = array_map(fn($prop) => ":$prop->name", $props);
    $fields = implode(', ', $fields);
    $values = implode(', ', $values);
    
    $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";

    $data = get_object_vars($object);

    $this->storage->query($sql, $data);
    if($return) {
      return $this->storage->lastInsertId();
    }
    return null;
  }

  public function destroy(int $id) { 
    $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
    $data = ['id' => $id];
    return $this->storage->query($sql, $data);
  }

}