<?php namespace Albreis\Kurin;

use Albreis\Kurin\Interfaces\IEvent;

/** @package Albreis\Kurin */
abstract class Event implements IEvent {

  private ?array $callbacks = [];
  private $message;

  public function __construct(callable ...$callbacks) {
    $this->callbacks = $callbacks;
  }

  /**
   * @param string $message 
   * @return mixed 
   */
  public function setMessage(string $message) {
    $this->message = $message;
    return $this;
  }

  /** @return string  */
  public function getMessage(): string {
    return $this->message;
  }


}