<?php

namespace Controllers\v1;

use Models\Medicine as Model;



class Medicine extends \Controllers\Base
{
	public function get()
	{
		$this->respond(Model::getAll($this->requestBody));
	}

	public function getOne()
	{
		$this->respond(Model::getOne($this->params['id']));
	}

	public function post()
	{
		$this->respond(Model::post($this->requestBody));
	}

	public function put()
	{
		$this->respond(Model::put($this->params['id'], $this->requestBody));
	}

	public function delete()
	{
		$this->respond(Model::delete($this->params['id']));
	}
}
