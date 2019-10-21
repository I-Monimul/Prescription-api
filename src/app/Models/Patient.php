<?php

namespace Models;

class Patient extends \Models\Base\Patient
{
	public static function getAll()
	{
		$model = new self;
		$results = $model->find('deleted = 0 order by id desc');
		return empty($results) ? [] : $results->castAll();
	}

	public static function getOne($id)
	{
		$model = new self;
		$model->load(array('id = ? AND deleted = 0', $id));
		return empty($model) ? [] : $model->cast();
	}

	public static function post($data)
	{
		$model = new self;
		$model->name = $data['name'];
		$model->age = $data['age'];
		$model->gender = $data['gender'];
		$model->phone = $data['phone'];
		$model->email = $data['email'];
		$model->address = $data['address'];
		$model->reference = $data['reference'];
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function put($id, $data)
	{
		$model = new self;
		$model->reset();
		$model->load(array('id = ? AND deleted = 0', $id));
		$model->name = $data['name'];
		$model->age = $data['age'];
		$model->gender = $data['gender'];
		$model->phone = $data['phone'];
		$model->email = $data['email'];
		$model->address = $data['address'];
		$model->reference = $data['reference'];
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function delete($id)
	{
		$model = new self;
		$model->load(array('id = ?', $id));
		$model->erase();
		return empty($model) ? [] : $model->cast();
	}
}
