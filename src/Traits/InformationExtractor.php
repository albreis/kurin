<?php namespace Albreis\Kurin\Traits;

use DateTime;
use ReflectionObject;

trait InformationExtractor { 
  
  public static function getObjectAttribute(object $object, string $name) {
    $manipulator = new ReflectionObject($object);
    $prop = $manipulator->getProperty($name);
    $prop->setAccessible(true);    
    return $prop->getValue($object);
  }

  /**
   * @param null|object $object 
   * @return null|DateTime 
   */
  public function getCreatedAt(?object $object = null)
  {
    if($object) {
      return self::getObjectAttribute($object, 'created_at');
    }
    return self::getObjectAttribute($this, 'created_at');
  }

  /**
   * @param null|object $object 
   * @return null|DateTime 
   */
  public function getUpdatedAt(?object $object = null)
  {
    if($object) {
      return self::getObjectAttribute($object, 'updated_at');
    }
    return self::getObjectAttribute($this, 'updated_at');
  }

  /**
   * @param null|object $object 
   * @return null|DateTime 
   */
  public function getDeletedAt(?object $object = null)
  {
    if($object) {
      return self::getObjectAttribute($object, 'deleted_at');
    }
    return self::getObjectAttribute($this, 'deleted_at');
  }

  /**
   * @param null|object $object 
   * @return void 
   */
  public function getCreatedBy(?object $object = null): ?string 
  {
    if($object) {
      return self::getObjectAttribute($object, 'created_by');
    }
    return self::getObjectAttribute($this, 'created_by');
  }

  /**
   * @param null|object $object 
   * @return void 
   */
  public function getUpdatedBy(?object $object = null): ?string 
  {
    if($object) {
      return self::getObjectAttribute($object, 'updated_by');
    }
    return self::getObjectAttribute($this, 'updated_by');
  }

  /**
   * @param null|object $object 
   * @return void 
   */
  public function getDeletedBy(?object $object = null): ?string 
  {
    if($object) {
      return self::getObjectAttribute($object, 'deleted_by');
    }
    return self::getObjectAttribute($this, 'deleted_by');
  }
}