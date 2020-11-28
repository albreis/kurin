<?php namespace Albreis\Kurin\Traits;

trait Request {

  /** @return mixed  */
  public static function uri() {
    return $_SERVER['REQUEST_URI'];
  }

  /** @return null|string  */
  public static function input(): ?string {
    return file_get_contents('php://input');
  }

  /**
   * @param null|string $key 
   * @param null|string $default 
   * @param null|string $type 
   * @return mixed 
   */
  public static function inputJson(?string $key, $default = null, ?string $type = null) {
    $request = json_decode(self::input(), true);
    return self::getValue($request, $key, $default, $type);
  }

  /**
   * @param null|string $key 
   * @param null|string $default 
   * @param null|string $type 
   * @return mixed 
   */
  public static function get(?string $key, ?string $default, ?string $type = null) {
    return self::getValue($_GET, $key, $default, $type);
  }

  /**
   * @param null|string $key 
   * @param null|string $default 
   * @param null|string $type 
   * @return mixed 
   */
  public static function post(?string $key, ?string $default, ?string $type = null) {
    return self::getValue($_POST, $key, $default, $type);
  }

  /**
   * @param null|array $request 
   * @param null|string $key 
   * @param null|string $default 
   * @param null|string $type 
   * @return mixed 
   */
  private static function getValue(?array $request, ?string $key, ?string $default, ?string $type = null) {
    if(!$key) {
      return $request;
    }
    if(isset($request[$key])) {
      $value = $request[$key] ?? $default;
      if ($type) {
        return settype($value, $type);
      }
      return $value;
    }
    return $default;
  }
  
}