<?php namespace Albreis\Kurin\Traits;

use Exception;

trait Cache {

  private static $cache_path;

  /**
   * @param string $key 
   * @param null|int $time 
   * @param mixed|null $value 
   * @return null|string 
   * @throws Exception 
   */
  public static function file(string $key, ?int $time, $value = null) {
    self::$cache_path = CACHE_DIR . "/{$key}";
    return self::store($time, $value);
  }

  /**
   * @param null|int $time 
   * @param mixed|null $value 
   * @return null|string 
   * @throws Exception 
   */
  public static function store(?int $time, $value = null): ?string {

    if(!defined('CACHE_DIR')) {
      throw new Exception('Constant CACHE_DIR is not defined');
    }

    if($time < 0 || (file_exists(self::$cache_path) && time() - filemtime(self::$cache_path) < $time)) {
      return self::getCache();
    }

    if(!file_exists(dirname(self::$cache_path))) {
      mkdir(dirname(self::$cache_path), 0755, true);
    }

    if (is_string($value) || is_numeric($value) || is_bool($value)) {
      self::setCache($value);
    } else if(is_callable($value)) {
      self::setCache(call_user_func($value));
    } else if(is_array($value) || is_object($value)) {
      self::setCache(json_encode($value));
    } else {
      self::setCache(serialize($value));
    }

    return self::getCache();
    
   }

  /**
   * @param mixed|null $value 
   * @return void 
   */
  private static function setCache($value = null) {
    $tmp = time(); 
    file_put_contents(self::$cache_path . ".{$tmp}", $value);
    rename(self::$cache_path . ".{$tmp}", self::$cache_path);
  }

  /** @return null|string  */
  private static function getCache(): ?string { 
    return file_get_contents(self::$cache_path);
  }
}