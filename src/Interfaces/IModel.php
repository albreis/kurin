<?php namespace Albreis\Kurin\Interfaces;

use DateTime;

interface IModel {  
  public function getCreatedAt(?object $object = NULL);
  public function getUpdatedAt(?object $object = NULL);
  public function getDeletedAt(?object $object = NULL);
  public function getCreatedBy(?object $object = NULL);
  public function getUpdatedBy(?object $object = NULL);
  public function getDeletedBy(?object $object = NULL);
}