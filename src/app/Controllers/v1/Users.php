<?php

namespace Controllers\v1;

use Utils\Identity;

class Users extends \Controllers\Base
{
	protected $accessList = [
		'get' => true,
		'getOne' => true,
		'post' => true,
		'put' => true,
		'delete' => true
	];

	protected $allowedSearchFields = ['name', 'surname', 'email', 'role'];

	protected $modelsMap = [
		Identity::CONTEXT_ADMIN => 'Models\User',
	];

	public function get()
	{
		$model = $this->getModel();
		$this->respond($model::listAll($this->offset, $this->limit, $this->filters));
	}

	public function getOne()
	{
		$model = $this->getModel();
		$this->respond($model::getOne($this->params['id']));
	}

	public function put()
	{
		$model = $this->getModel();
		$this->respond($model::put($this->params['id'], $this->requestBody));
	}

	public function post()
	{
		$model = $this->getModel();
		$this->respond($model::create($this->requestBody));
	}

	public function delete()
	{
		$model = $this->getModel();
		$this->respond($model::delete($this->params['id']));
	}

	public function uploadAvatar()
	{
		$model = $this->getModel();
		$this->respond($model::uploadAvatar($this->params['id']));
	}
}
