<?php declare(strict_types=1);
namespace Albreis\DummyApp\Controllers;

use Albreis\Kurin\Database\MySQL;
use Albreis\Kurin\Interfaces\IProducer;
use Albreis\Kurin\Interfaces\IRepository;
use Albreis\Kurin\Interfaces\IStorageManager;
use Albreis\Kurin\Interfaces\IValidator;
use Albreis\Kurin\Traits\Cache;
use Albreis\Kurin\Traits\Database;
use Albreis\Kurin\Traits\Request;
use Albreis\Kurin\Traits\Response;
use Albreis\Kurin\Uploader;
use DateTimeZone;
use Exception;
use PDOException;
use ReflectionClass;
use ReflectionProperty;

/** @package Albreis\DummyApp\Controllers */
class DummyClasssController {

  protected $repository_class = '\Albreis\DummyApp\Repositories\DummyClasssRepository';
  protected $producer_class = '\Albreis\DummyApp\Producers\DummyClasssProducer';
  protected $storage_manager_class = '\Albreis\DummyApp\Managers\DummyClasssStorageManager';
  protected $validator_class = '\Albreis\DummyApp\Validators\DummyClassValidator';

  public function __construct(
    ?IRepository $repository = null, 
    ?IProducer $producer = null, 
    ?IStorageManager $storage_manager = null,
    ?IValidator $validator = null) 
  {
    $this->repository = $repository ?? new $this->repository_class;
    $this->producer = $producer ?? new $this->producer_class;
    $this->storage_manager = $storage_manager ?? new $this->storage_manager_class;
    $this->validator = $validator ?? new $this->validator_class;

    $this->producer->setTimezone(new DateTimeZone('America/Sao_Paulo'));

  }

  /**
   * @return void 
   * @throws Exception 
   */
  public function index() {
    $cache = Cache::file(md5(Request::uri()), 0, function () {
      $limit = Request::inputJson('limit', 20);
      $offset = Request::inputJson('offset', 0);
      try {
        $result = $this->repository->getAll($limit, $offset);   
      }catch(Exception $e) {
        $result = $e->getMessage();
      }  
      return Response::json($result);
    });
    echo $cache;
  }

  /**
   * @param mixed $id 
   * @return void 
   * @throws PDOException 
   * @throws Exception 
   */
  public function view($id) {
    $object = $this->repository->find($id);
    if(!$object) {
      throw new Exception('DummyClass not exists');
    }
    $cache = Cache::file(md5(Request::uri()), 60, Response::json($object));
    echo $cache;
  }
  /**
   * @return mixed 
   * @throws Exception 
   */
  public function create() {
    $request = json_decode(file_get_contents('php://input'));
    
    $object = $this->producer->create();
    
    $reflect = new ReflectionClass($object);
    $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

    array_map(function($prop) use ($object, $request){
      $this->producer->setObjectAttribute($object, $prop->name, $request->{$prop->name});
    }, $props);
    
    $this->producer->setObjectAttribute($object, 'created_by', AUTHENTICATED_ACCOUNT_ID);

    $files = [];
    
    if(count($request)) {
      foreach($request as $field => $value) {
        $init = trim(substr($value, 0, 5));
        if($init == 'data:') {
          if (isset($request->{$field}) && ${$field} = $request->{$field}) {
            $fileUrl = Uploader::createUrl("files/{$field}/". AUTHENTICATED_ACCOUNT_ID, ${$field});
            $this->producer->setObjectAttribute($object, $field, $fileUrl);
            $files[$field] = $fileUrl;
          }
        }
      }
    }
    
    $result = false;
    if($this->validator->validate($object)) {
      $result = $this->storage_manager->store($object);
    } else {
      throw new Exception('An error ocurred on try save post');
    }

    if (isset($result) && count($files)) {
      foreach($files as $field => $url) {
          Uploader::save($url, $request->{$field});
      }
    }

    echo $result;
  }

  /**
   * @return mixed 
   * @throws Exception 
   */
  public function update($id = null) {
    $request = json_decode(file_get_contents('php://input'));
    $object = $this->repository->find($id ?? AUTHENTICATED_ACCOUNT_ID);

    $reflect = new ReflectionClass($object);
    $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

    array_map(function($prop) use ($object, $request){
      $this->producer->setObjectAttribute($object, $prop->name, $request->{$prop->name});
    }, $props);
    
    $this->producer->setObjectAttribute($object, 'created_by', AUTHENTICATED_ACCOUNT_ID);

    $files = [];
    
    foreach($request as $field => $value) {
      $init = trim(substr($value, 0, 5));
      if($init == 'data:') {
        if (isset($request->{$field}) && ${$field} = $request->{$field}) {
          $fileUrl = Uploader::createUrl("files/{$field}/". AUTHENTICATED_ACCOUNT_ID, ${$field});
          $this->producer->setObjectAttribute($object, $field, $fileUrl);
          $files[$field] = $fileUrl;
        }
      }
    }

    if($this->validator->validate($object)) {
      $this->storage_manager->setStorage(Database::connect());
      $result = $this->storage_manager->update($object);
    }

    if (isset($result) && count($files)) {
      foreach($files as $field => $url) {
          Uploader::save($url, $request->{$field});
      }
    }

    if($result === false) {
      return $result;
    }

    echo json_encode($object);
  }

  /**
   * @param mixed $id 
   * @return void 
   */
  public function delete($id) {
    if(!$this->repository->find($id)) {
      throw new Exception('Post not exists');
    }
    
    $cache = Cache::file(md5(Request::uri()), 0, function () use ($id) {
      try {
        $result = $this->repository->deleteById($id);
      }catch(Exception $e) {
        $result = $e->getMessage();
      }  
      return Response::json($result);
    });
    echo $cache;
    
  }
}