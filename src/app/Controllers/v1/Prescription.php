<?php

namespace Controllers\v1;

use Models\Prescription as Model;

class Prescription extends \Controllers\Base
{
  public function getOne()
  {
    $this->respond(Model::getOne($this->params['id']));
  }
}
