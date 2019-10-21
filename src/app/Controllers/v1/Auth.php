<?php

namespace Controllers\v1;

use \Exceptions\HTTPException;
use \Models\User;
use \Models\Apikey;
use \Utils\Tools;
use \Utils\Identity;

class Auth extends \Controllers\Base
{
	protected $accessList = [
		'get' => true,
		'post' => true
	];
	public function get()
	{
		$identity = $this->app->get('IDENTITY');
		$_context = $this->params['context'];
		$ret = [];
		$admin = [
			Identity::CONTEXT_ADMIN => 'Administrator'
		];


		if ($identity->context == Identity::CONTEXT_ADMIN) {
			$ret = $admin;
		}

		$this->respond($ret);
	}

	public function post()
	{
		$data = $this->requestBody;

		if (
			isset($data['username']) &&
			!empty($data['username']) &&
			isset($data['password']) &&
			!empty($data['password'])
		) {

			$this->login();
			return;
		}

		throw new HTTPException(
			'Invalid username / password combination',
			403
		);
	}

	private function login()
	{
		$data = $this->requestBody;

		$username = $data['username'];
		$password = md5($this->app->get('SALT') . $data['password']);

		$model = new User;
		$model->load(['username = ? AND password = ? and deleted <> ?', $username, $password, 1]);


		if (!$model->dry()) {

			Apikey::deactivateAll($model->id);

			$apikey = new Apikey();
			$apikey->key = Tools::generateAPIKey();
			$apikey->active = 1;
			$apikey->user = $model->id;
			$apikey->user_agent = $this->app->get('AGENT');

			if ($apikey->save()) {

				$user = $model->cast(null, ['avatar' => 0]);

				$ret = [
					'id' => $user['id'],
					'client' => $user['client'],
					'name' => $user['name'],
					'surname' => $user['surname'],
					'email' => $user['email'],
					'role' => $user['role'],
					'key' => $apikey['key'],
					'avatar' => $user['avatarurl']
				];

				$this->respond($ret);

				return;
			} else {
				throw new HTTPException('Error in saving data', 500);
			}
		}

		throw new HTTPException(
			'Invalid username / password combination',
			403
		);
	}
}
