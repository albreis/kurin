<?php namespace Albreis\Kurin;

use Albreis\Kurin\Interfaces\ICommunicator;
use Albreis\Kurin\Traits\EventCommunicator;

/** @package Albreis\Kurin */
abstract class Communicator implements ICommunicator {
  use EventCommunicator;
}