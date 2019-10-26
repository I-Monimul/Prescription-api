<?php

namespace Models;

class Next_visit extends \Models\Base\Next_visit
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
		$model->next_visit = ucwords($data['next_visit']);
		$model->alternative = ucwords($data['alternative']);
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function put($id, $data)
	{
		$model = new self;
		$model->reset();
		$model->load(array('id = ? AND deleted = 0', $id));
		$model->next_visit = ucwords($data['next_visit']);
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
