<?php namespace Albreis\Kurin\Traits;

use Albreis\Kurin\Communicator;
use Exception;
use ReflectionMethod;

trait EventCommunicator {
  protected array $events;

  /**
   * @param string $event_name 
   * @param callable $callback 
   * @return $this 
   */
  public function on(string $event_name, callable ...$callback) { 
    $this->events[$event_name][] = $callback;

    return $this;
  }

  /**
   * @param string $event_name 
   * @param mixed $params 
   * @return $this 
   */
  public function dispatch(string $event_name, ...$params) { 
    
    if(!isset($this->events[$event_name])) {
      return;
    }

    do {
      $callback = array_shift($this->events[$event_name]);
      if(is_callable($callback)) {
        if(is_string($callback) && count($call = explode('::', $callback)) == 2) {
          $method = new ReflectionMethod($call[0], $call[1]);
          if (!$method->isStatic()) {
              $callback = [new $call[0], $call[1]];
          }
        }
        call_user_func($callback, ...$params);
      } else if (is_array($callback)) {
        do {
          $sub_callback = array_shift($callback);
          if(is_string($sub_callback) && count($call = explode('::', $sub_callback)) == 2) {
            $method = new ReflectionMethod($call[0], $call[1]);
            if (!$method->isStatic()) {
                $sub_callback = [new $call[0], $call[1]];
            }
          }
          if (is_callable($sub_callback)) {
              call_user_func($sub_callback, ...$params);
          }
        }
        while($sub_callback);
      } else {
        throw new Exception('Unexpected event type');
      }
    } while($this->events[$event_name]);

    return $this;
  }

  public function notify(Communicator $communicator, string $event_name, ...$params) {
    $communicator->dispatch($event_name, ...$params);
    return $this;
  }
}