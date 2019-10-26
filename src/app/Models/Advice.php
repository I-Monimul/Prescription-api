<?php

namespace Models;

class Advice extends \Models\Base\Advice
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
		$model->advice = ucwords($data['advice']);
		$model->alternative = ucwords($data['alternative']);
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function put($id, $data)
	{
		$model = new self;
		$model->reset();
		$model->load(array('id = ? AND deleted = 0', $id));
		$model->advice = ucwords($data['advice']);
		$model->alternative = ucwords($data['alternative']);
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
