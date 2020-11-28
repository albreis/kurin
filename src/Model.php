<?php namespace Albreis\Kurin;

use Albreis\Kurin\Interfaces\IModel;
use Albreis\Kurin\Traits\InformationExtractor;
use Albreis\Kurin\Traits\ObjectManager;

/** @package Albreis\Kurin */
abstract class Model implements IModel{

  use InformationExtractor, ObjectManager;
  
  public $created_at = null;
  public $updated_at = null;
  public $deleted_at = null;
  public $created_by = null;
  public $updated_by = null;
  public $deleted_by = null;

}