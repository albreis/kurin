<?php declare(strict_types=1);
namespace Albreis\DummyApp\Validators;

use Albreis\Kurin\Validator;
use Exception;

/** @package Albreis\DummyApp\Models */
class DummyClassValidator extends Validator {

    public function validate(?object $object = null): bool { 
      $this->object = $object;
      return $this->validateCreatedBy();
    }

    public function validateCreatedAt(): bool { 
      if(empty($this->object->created_at)) {
        throw new Exception('Created at is required');
      }
      return true;
    }

    public function validateUpdatedAt(): bool { 
      return true;
    }

    public function validateDeletedAt(): bool { 
      return true;
    }

    public function validateCreatedBy(): bool { 
      if(empty($this->object->created_by)) {
        throw new Exception('Created by is required');
      }
      return true;
    }

    public function validateUpdatedBy(): bool { 
      return true;
    }

    public function validateDeletedBy(): bool { 
      return true;
    }

}