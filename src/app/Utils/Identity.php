<?php

namespace Utils;

class Identity extends \Prefab
{

  const CONTEXT_ADMIN                   = 'A';

  protected $app;

  public $context         = null;
  public $inactiveKey     = false;
  public $isAdmin         = false;

  public function __construct()
  {
    $this->app = \Base::instance();

    $this->identify();
  }

  public function forget()
  {
    $this->user     = null;
    $this->client   = null;
    $this->context  = null;
    $this->isAdmin  = false;
  }

  public function reIdentify()
  {
    $this->identify();
  }

  private function identify()
  {

    $key = new \Models\Apikey;
    $key->load(['`key` = ?', $this->app->get('HEADERS.Api-Key')]);
    if ($key->dry()) {
      return;
    }

    if ($key->active != 1) {
      $this->inactiveKey = true;

      return;
    }

    $this->user = $key->user;
    $this->context = $key->user->role;

    switch ($this->context) {
      case self::CONTEXT_ADMIN:
        $this->isAdmin = true;
        break;
    }
  }
}
