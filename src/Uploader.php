<?php namespace Albreis\Kurin;

class Uploader {
  public static function createUrl(string $directory, $file, $isDataUri = true): string 
  {    
    $ext = 'jpg';
    if(stripos($isDataUri ? '/gif' : '.gif', substr($file, 0, 20)) !== false) {
      $ext = 'gif';
    }
    if(stripos($isDataUri ? '/webp' : '.webp', substr($file, 0, 20)) !== false) {
      $ext = 'webp';
    }
    if(stripos($isDataUri ? '/png' : '.png', substr($file, 0, 20)) !== false) {
      $ext = 'png';
    }
    if(stripos($isDataUri ? '/svg' : '.svg', substr($file, 0, 20)) !== false) {
      $ext = 'svg';
    }
    return trim($directory, '/') . '/' . uniqid() . '.' . $ext;
  }

  public static function save(string $path, $data, $isDataUri = true): ?string 
  {    
    $directory = dirname(UPLOAD_DIR . trim($path, '/'));
    if(!file_exists($directory)) {
      mkdir($directory, 0755, true);
    }
    $success = '';
    if ($isDataUri) {
        $data = str_replace('data:image/webp;base64,', '', $data);
        $data = str_replace('data:image/jpg;base64,', '', $data);
        $data = str_replace('data:image/gif;base64,', '', $data);
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace('data:image/svg;base64,', '', $data);
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);
        $success = file_put_contents(UPLOAD_DIR . trim($path, '/'), $data);
    }
    return $success;
  }
}