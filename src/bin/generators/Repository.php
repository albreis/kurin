<?php declare(strict_types=1);
namespace Albreis\DummyApp\Repositories;

use Albreis\Kurin\Repositories\AbstractRepository;

/** @package Albreis\Kurin\Repositories */
class DummyClasssRepository extends AbstractRepository {

  /**
   * Model usado pelo repositório
   */
  protected ?string $model = 'Albreis\DummyApp\Models\DummyClass';
  protected string $table = 'dummy_table';

  /**
   * @param int $limit 
   * @param int $offset 
   * @return array 
   */
  public function getAll(int $limit = 20, int $offset = 0): array {
    $sql = "SELECT * FROM `{$this->table}` WHERE deleted_at = '0000-00-00 00:00:00' ORDER BY id DESC LIMIT :rows_offset, :rows_count ";
    $this->result = $this->connection->listQuery($sql, ['rows_count' => $limit, 'rows_offset' => $offset]);
    return $this->result;
  }

  /**
   * @param int $user_id 
   * @param int $limit 
   * @param int $offset 
   * @return array 
   */
  public function getByUserId(int $user_id, int $limit = 20, int $offset = 0): array {
    $sql = "SELECT * FROM `{$this->table}` WHERE deleted_at = '0000-00-00 00:00:00' AND created_by = :created_by ORDER BY id DESC LIMIT :rows_offset, :rows_count ";
    $params = [
      'rows_count' => $limit, 
      'rows_offset' => $offset,
      'created_by' => $user_id
    ];
    $this->result = $this->connection->listQuery($sql, $params);
    return $this->result;
  }

  /**
   * @param mixed $id 
   * @return bool 
   */
  public function deleteById($id) {
    $sql = "UPDATE `{$this->table}` SET deleted_at = NOW() WHERE id = :id AND created_by = :created_by";
    return $this->connection->query($sql, ['id' => $id, 'created_by' => AUTHENTICATED_ACCOUNT_ID])->rowCount();
  }

  /**
   * @param mixed $id 
   * @return object 
   */
  public function find($id) {
    $sql = "SELECT * FROM `{$this->table}` WHERE id = :id AND deleted_at = '0000-00-00 00:00:00'";
    return $this->connection->queryOne($sql, ['id' => $id]);
  }
  
}