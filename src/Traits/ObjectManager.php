<?php namespace Albreis\Kurin\Traits;

use DateTime;
use ReflectionObject;
use stdClass;

trait ObjectManager {
  
  /**
   * @param object $object 
   * @param string $name 
   * @param mixed $value 
   * @return $this 
   * @throws ReflectionException 
   */
  public static function setObjectAttribute(object $object, string $name, $value) {
    $manipulator = new ReflectionObject($object);
    if($object instanceof stdClass && !property_exists($object, $name)) {
      $object->{$name} = null;
    }
    $prop = $manipulator->getProperty($name);
    $prop->setAccessible(true);
    $prop->setValue($object, $value);
  }

  /**
   * @param null|object $object 
   * @return null|DateTime 
   */
  public function setCreatedAt(DateTime $date, ?object $object = null)
  {
    return self::setObjectAttribute($object??$this, 'created_at', $date);
  }

  /**
   * @param null|object $object 
   * @return null|DateTime 
   */
  public function setUpdatedAt(DateTime $date, ?object $object = null)
  {
    return self::setObjectAttribute($object??$this, 'updated_at', $date);
  }

  /**
   * @param null|object $object 
   * @return null|DateTime 
   */
  public function setDeletedAt(?object $object = null, DateTime $date)
  {
    return self::setObjectAttribute($object??$this, 'deleted_at', $date);
  }

  /**
   * @param null|object $object 
   * @return void 
   */
  public function setCreatedBy(?object $object = null, ?object $modifier = null) 
  {
    return self::setObjectAttribute($object??$this, 'created_by', $modifier);
  }

  /**
   * @param null|object $object 
   * @return void 
   */
  public function setUpdatedBy(?object $object = null, ?object $modifier = null) 
  {
    return self::setObjectAttribute($object??$this, 'updated_by', $modifier);
  }

  /**
   * @param null|object $object 
   * @return void 
   */
  public function setDeletedBy(?object $object = null, ?object $modifier = null) 
  {
    return self::setObjectAttribute($object??$this, 'deleted_by', $modifier);
  }
}