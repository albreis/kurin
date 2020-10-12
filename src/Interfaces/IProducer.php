<?php namespace Albreis\Kurin\Interfaces;

use Albreis\Kurin\Communicator;
use DateTime;

/** @package Albreis\Kurin\Interfaces */
interface IProducer {

  /** @return object  */
  public function create(): object;

  /**
   * @param string $model 
   * @return mixed 
   */
  public function setModel(string $model);
  
  /** @return null|string  */
  public function getModel(): ?string;

  /**
   * @param object $object 
   * @param string $name 
   * @param mixed $value 
   * @return mixed 
   */
  public static function setObjectAttribute(object $object, string $name, $value);
  
  /**
   * @param object $object 
   * @param string $name 
   * @return mixed 
   */
  public static function getObjectAttribute(object $object, string $name);

  /**
   * @param Communicator $communicator 
   * @return mixed 
   */
  public function setCommunicator(Communicator $communicator);

  /** @return null|Communicator  */
  public function getCommunicator(): ?Communicator;
  
}