<?php namespace Albreis\Kurin\Interfaces;

/** @package Albreis\Kurin\Interfaces */
interface IEvent {

  /**
   * @param string $message 
   * @return mixed 
   */
  public function setMessage(string $message);

  /** @return null|string  */
  public function getMessage(): ?string;

  public function addCallback(callable ...$callback);
}