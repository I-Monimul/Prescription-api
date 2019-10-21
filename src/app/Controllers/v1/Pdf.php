<?php

namespace Controllers\v1;

use Models\Pdf as Model;

class Pdf extends \Controllers\Base
{
  public function getOne()
  {
    $this->respond(Model::getOne($this->params['id']));
  }
}
