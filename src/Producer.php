<?php declare(strict_types=1);

namespace Albreis\Kurin;

use Albreis\Kurin\Interfaces\IProducer;
use Albreis\Kurin\Traits\InformationExtractor;
use Albreis\Kurin\Traits\ObjectManager;
use DateTime;
use DateTimeZone;

/** @package Albreis\Kurin */
abstract class Producer extends Communicator implements IProducer {

  use InformationExtractor, ObjectManager;

  protected $model;
  protected ?Communicator $communicator = null;
  protected object $latest;
  protected array $objects;
  protected DateTimeZone $timezone;

  /** @return void  */
  public function __construct() {
    $this->timezone = new DateTimeZone('UTC');
  }

  /**
   * @param Communicator $communicator 
   * @return mixed 
   */
  public function setCommunicator(Communicator $communicator) {
    $this->communicator = $communicator;
    return $this;
  }

  /** @return null|Communicator  */
  public function getCommunicator(): ?Communicator
  {
    return $this->communicator;
  }

  /**
   * @param DateTimeZone $timezone 
   * @return $this 
   */
  public function setTimezone(DateTimeZone $timezone) {
    $this->timezone = $timezone;
    return $this;
  }

  /** @return object  */
  public function create(): object { 
    $this->latest = new $this->model;
    $this->setObjectAttribute($this->latest, 'created_at', new DateTime('now', $this->timezone));
    $this->objects[] = $this->latest;
    $this->dispatch('create.object', $this->latest);
    return $this->latest;
  }

  /**
   * @param string $model 
   * @return mixed 
   */
  public function setModel(string $model) {
    $this->model = $model;
    return $this;
  }

  /** @return null|string  */
  public function getModel(): ?string {
    return $this->model;
  }

}