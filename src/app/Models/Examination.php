<?php

namespace Models;

class Examination extends \Models\Base\Examination
{
	public static function getAll()
	{
		$model = new self;
		$results = $model->find('deleted = 0');
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
		$model->examination = ucwords($data['examination']);
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function put($id, $data)
	{
		$model = new self;
		$model->reset();
		$model->load(array('id = ? AND deleted = 0', $id));
		$model->examination = ucwords($data['examination']);
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
