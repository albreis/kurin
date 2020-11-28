<?php namespace Albreis\Kurin\Database;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Query {

    public PDO $db;
    public $stmt;
    private ?string $model = 'stdClass';

    public function __construct(Connector $connector) {
      $this->db = $connector->connect();
    }

    public function setModel(string $model = null) {
      $this->model = $model;
      return $this;
    }
  
    /**
     * @param string $sql 
     * @param array $params 
     * @return array 
     */
    public function query(string $sql, array $params = []): PDOStatement 
    {     
      try {
        $this->stmt  = $this->db->prepare($sql);
        foreach($params as $key => $value) {
          if(is_numeric($value)) {
            $this->stmt->bindValue(":{$key}", $value, PDO::PARAM_INT);
          } else {
            $this->stmt->bindValue(":{$key}", $value);
          }
        }
        $this->stmt->execute();
      } catch(PDOException $e) {
        throw new Exception($e->getMessage());
      }
      return $this->stmt;
    }
  
    /**
     * @param string $sql 
     * @param array $params 
     * @return array 
     */
    public function queryOne(string $sql, array $params = []) 
    {    
      try {
        $this->stmt  = $this->db->prepare($sql);
        foreach($params as $key => $value) {
          if(is_numeric($value)) {
            $this->stmt->bindValue(":{$key}", $value, PDO::PARAM_INT);
          } else {
            $this->stmt->bindValue(":{$key}", $value);
          }
        }
        $this->stmt->execute();
      } catch(PDOException $e) {
        throw new Exception($e->getMessage());
      }
  
      $result = $this->stmt->fetchObject($this->model);

      if($result == false) {
        return null;
      }
  
      return $result;
    }
  
    /**
     * @param string $sql 
     * @param array $params 
     * @return array 
     */
    public function listQuery(string $sql, array $params = []): ?array 
    {    
      try {
        $this->stmt  = $this->db->prepare($sql);
        foreach($params as $key => $value) {
          if(is_numeric($value)) {
            $this->stmt->bindValue(":{$key}", $value, PDO::PARAM_INT);
          } else {
            $this->stmt->bindValue(":{$key}", $value);
          }
        }
        $this->stmt->execute();
      } catch(PDOException $e) {
        throw new Exception($e->getMessage());
      }
  
      $result = [];
      while($row = $this->stmt->fetchObject($this->model)) {
        $result[] = $row;
      }
  
      return $result;
    }

    public function lastInsertId(): ?int {
      return $this->db->lastInsertId();
    }
    
}