<?php namespace Albreis\Kurin;

use Albreis\Kurin\Interfaces\IModel;
use Albreis\Kurin\Traits\InformationExtractor;
use Albreis\Kurin\Traits\ObjectManager;
use DateTime;

/** @package Albreis\Kurin */
abstract class Model implements IModel{

  use InformationExtractor, ObjectManager;
  
  protected $created_at = null;
  protected $updated_at = null;
  protected $deleted_at = null;
  protected $created_by = null;
  protected $updated_by = null;
  protected $deleted_by = null;

}