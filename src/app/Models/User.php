<?php

namespace Models;

use \Utils\Identity;
use \Exceptions\HTTPException;

class User extends \Models\Base\User
{
  public $castDepth = ['*' => 0, 'avatar' => 0];

  public static function listAll($offset, $limit, $filters = null, $opts = [])
  {

    $model = new self;
    $identity = $model->app->get('IDENTITY');
    $metadata = $model->app->get('METADATAPROVIDER');
    $context = $identity->context;

    $query = '`' . $model->table . '`' . '.`deleted` <> 1 AND `role` IN (:roles)';
    $bindings = [':roles' => [
      Identity::CONTEXT_ADMIN
    ]];
    $results = [];
    $total = 0;

    $qobj = self::filteredQuery($filters, $query, $bindings);

    $total = $model->count($qobj); //this line does a count of how many rows total
    $results = $model->find($qobj, ['offset' => $offset, 'limit' => $limit, 'order' => 'id ASC']);

    $metadata->setTotal($total); //this line assigns that total to METADATAPROVIDER

    // //add custom filters to metadata
    // //------------------------------
    // $metadata->setCustomField('filters', [
    //   'role' => [
    //     ['name' => 'Admin', 'id' => Identity::CONTEXT_ADMIN],
    //     ['name' => 'User', 'id' => Identity::CONTEXT_USER],
    //     ['name' => 'Utility User', 'id' => Identity::CONTEXT_UTILITY_USER],
    //   ]
    // ]);
    // //------------------------------

    return empty($results) ? [] : $results->castAll($model->castDepth);
  }

  public static function getOne($id, $internal = false)
  {
    $model = new self;

    $model->load([$model->primary . ' = ? AND `deleted` <> 1', $id]);

    if (!$model->dry()) {
      return $internal ? $model : $model->cast(null, $model->castDepth);
    }

    throw new HTTPException('Not Found.', 404);
  }

  public static function put($id, $payload)
  {
    $model = new self;

    $valid = true;

    //this ensures you can edit entities that you have permission to see
    $existing = self::getOne($id, true);

    $fields = [
      'name',
      'surname',
      'email',
      'username',
      'password',
      'role'
    ];

    if ($existing->username != $payload['username']) {
      self::checkDuplicateUsername($payload['username']);
    }

    //normal props
    $existing->copyfrom($payload, $fields);

    //normal sanity checks
    $mandatoryFields = ['name', 'username', 'password', 'role'];

    $valid = self::checkMandatoryFields($existing, $mandatoryFields);

    if ($valid) {
      $existing->save();
      return $existing->cast(null, $model->castDepth);
    }

    throw new HTTPException(
      'Bad Request.',
      400,
      array(
        'dev' => 'All required fields may not have been filled in',
        'internalCode' => '',
        'more' => '',
      )
    );
  }

  public static function create($payload)
  {

    $model = new self;

    $valid = true;

    $fields = [
      'name',
      'surname',
      'email',
      'username',
      'password',
      'role'
    ];

    //Given username does not exist in db
    self::checkDuplicateUsername($payload['username']);

    //normal props
    $model->copyfrom($payload, $fields);

    //normal sanity checks
    $mandatoryFields = ['name', 'username', 'password', 'role'];

    $valid = self::checkMandatoryFields($model, $mandatoryFields);

    if ($valid) {
      $model->save();
      return $model->cast(null, $model->castDepth);
    }

    throw new HTTPException(
      'Bad Request.',
      400,
      array(
        'dev' => 'All required fields may not have been filled in',
        'internalCode' => '',
        'more' => '',
      )
    );
  }

  public static function delete($id)
  {
    //this ensures you can delete entities that you have permission to see
    $existing = self::getOne($id, true);
    $casted = $existing->cast(null, 0);
    $existing->erase();

    //invalidate all exisiting logged in sessions
    Apikey::deactivateAll($id);

    return $casted;
  }

  public static function uploadAvatar($id)
  {
    $model = new self;
    $identity = $model->app->get('IDENTITY');

    $existing = self::getOne($id, true);

    $folder = $model->app->get('CONFIG')['FOLDERS']['avatars'];

    $fileModel = \Models\File::createFromUpload($folder, null, function ($filePath) {
      \Utils\ImageResizer::resize($filePath, 128, 128);
    });

    $existing->avatar = $fileModel->id;
    $existing->save();

    return $existing->cast(null, $existing->castDepth);
  }

  public function set_password($val)
  {
    if (isset($val) && !empty($val)) {
      return md5($this->app->get('SALT') . $val);
    }

    throw new HTTPException(
      'Please enter a password.',
      406,
      array(
        'dev' => 'Password field should\'nt be empty',
        'internalCode' => '',
        'more' => '',
      )
    );
  }

  public function cast($obj = NULL, $rel_depths = 1)
  {
    $dt = parent::cast($obj, $rel_depths);

    //remove password from being casted
    unset($dt['password']);

    //add avatar image
    $dt['avatarurl'] = $dt['avatar'] ? $dt['avatar']['url'] : 'https://www.gravatar.com/avatar/' . md5($dt['email']) . '?d=identicon&s=128';

    return $dt;
  }

  public function get_avatarurl()
  {
    return $this->avatar ? $this->avatar->getUrl() : 'https://www.gravatar.com/avatar/' . md5($this->email) . '?d=identicon&s=128';
  }

  public static function checkDuplicateUsername($username)
  {
    $model = new self;
    $model->load(['username = ? AND deleted <> ?', $username, 1]);
    if (!$model->dry()) {
      throw new HTTPException(
        'Username already exists',
        406,
        array(
          'dev' => 'The Username you entered is already in use.',
          'internalCode' => '',
          'more' => '',
        )
      );
    }
  }
}
