<?php namespace Albreis\Kurin\Interfaces;

use Albreis\Kurin\Communicator;
use Albreis\Kurin\Event;

/** @package Albreis\Kurin\Interfaces */
interface ICommunicator {

  /**
   * @param Event $event 
   * @param callable $callback 
   * @return mixed 
   */
  public function on(string $event, callable ...$callback);

  /**
   * @param Event $event 
   * @param mixed $params 
   * @return mixed 
   */
  public function dispatch(string $event, ...$params);

  /**
   * @param Communicator $communicator 
   * @param string $event 
   * @param mixed $params 
   * @return mixed 
   */
  public function notify(Communicator $communicator, string $event, ...$params);
}