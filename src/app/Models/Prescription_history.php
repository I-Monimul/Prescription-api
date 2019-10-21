<?php

namespace Models;

use \Models\Investigation_history as Investigation_history;
use \Models\Diagnosis as Diagnosis;
use \Models\Medicine as Medicine;
use \Models\Time as Time;
use \Models\Comment as Comment;
use \Models\Duration as Duration;

class Prescription_history extends \Models\Base\Prescription_history
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

		$medicines = explode(',', $model->medicines);
		$times = explode(',', $model->times);
		$comments = explode(',', $model->comments);
		$durations = explode(',', $model->durations);
		for ($i = 0; $i < count($medicines); $i++) {
			$medicine = new Medicine;
			$result = $medicine->load(array('id = ? AND deleted = 0', $medicines[$i]));
			$result_medicine[$i] = $result->medicine;
			$time = new Time;
			$result = $time->load(array('id = ? AND deleted = 0', $times[$i]));
			$result_time[$i] = $result->time;
			$comment = new Comment;
			$result = $comment->load(array('id = ? AND deleted = 0', $comments[$i]));
			$result_comment[$i] = $result->comment;
			$duration = new Duration;
			$result = $duration->load(array('id = ? AND deleted = 0', $durations[$i]));
			$result_duration[$i] = $result->duration;
		}
		$model->medicines = implode(',', $result_medicine);
		$model->times = implode(',', $result_time);
		$model->comments = implode(',', $result_comment);
		$model->durations = implode(',', $result_duration);

		return empty($model) ? [] : $model->cast();
	}

	public static function post($data)
	{
		$model = new self;

		if (!empty($data['patient'])) {
			$model->patient = $data['patient']['id'];
		} else {
			$investigation_history = Investigation_history::getOne($data['investigation_history']);
			$model->patient = $investigation_history['patient'];
		}

		if (!empty($data['investigation_history'])) {
			$model->investigation_history = $data['investigation_history'];

			$investigation_history = new Investigation_history;
			$investigation_history->reset();
			$investigation_history->load(array('id = ? AND deleted = 0', $data['investigation_history']));
			$investigation_history->investigation_descriptions = $data['investigation_descriptions'];

			$diagnoses = explode(',', $data['diagnoses']);
			for ($i = 0; $i < count($diagnoses); $i++) {
				if ($diagnoses[$i] && isset($diagnoses[$i])) {
					$diagnosis = new Diagnosis;
					$result = $diagnosis->load(array('diagnosis = ? AND deleted = 0', $diagnoses[$i]));
					if (!$result->dry()) {
						$result_diagnosis[$i] = $result->id;
					} else {
						$diagnosis->reset();
						$diagnosis->diagnosis = $diagnoses[$i];
						$diagnosis->save();
						$result_diagnosis[$i] = $diagnosis->id;
					}
				} else {
					$result_diagnosis[$i] = null;
				}
			}
			$investigation_history->diagnoses = implode(',', $result_diagnosis);
			$investigation_history->diagnosis_descriptions = $data['diagnosis_descriptions'];

			$investigation_history->save();
		} else {
			$model->investigation_history = null;
		}

		$medicines = explode(',', $data['medicines']);
		$times = explode(',', $data['times']);
		$comments = explode(',', $data['comments']);
		$durations = explode(',', $data['durations']);
		for ($i = 0; $i < count($medicines); $i++) {
			if ($medicines[$i] && isset($medicines[$i])) {
				$medicine = new Medicine;
				$result = $medicine->load(array('medicine = ? AND deleted = 0', $medicines[$i]));
				if (!$result->dry()) {
					$result_medicine[$i] = $result->id;
				} else {
					$medicine->reset();
					$medicine->medicine = strtoupper($medicines[$i]);
					$medicine->save();
					$result_medicine[$i] = $medicine->id;
				}
			} else {
				$result_medicine[$i] = null;
			}

			if ($times[$i] && isset($times[$i])) {
				$time = new Time;
				$result = $time->load(array('time = ? AND deleted = 0', $times[$i]));
				if (!$result->dry()) {
					$result_time[$i] = $result->id;
				} else {
					$time->reset();
					$time->time = $times[$i];
					$time->save();
					$result_time[$i] = $time->id;
				}
			} else {
				$result_time[$i] = null;
			}

			if ($comments[$i] && isset($comments[$i])) {
				$comment = new Comment;
				$result = $comment->load(array('comment = ? AND deleted = 0', $comments[$i]));
				if (!$result->dry()) {
					$result_comment[$i] = $result->id;
				} else {
					$comment->reset();
					$comment->comment = $comments[$i];
					$comment->save();
					$result_comment[$i] = $comment->id;
				}
			} else {
				$result_comment[$i] = null;
			}

			if ($durations[$i] && isset($durations[$i])) {
				$duration = new Duration;
				$result = $duration->load(array('duration = ? AND deleted = 0', $durations[$i]));
				if (!$result->dry()) {
					$result_duration[$i] = $result->id;
				} else {
					$duration->reset();
					$duration->duration = $durations[$i];
					$duration->save();
					$result_duration[$i] = $duration->id;
				}
			} else {
				$result_duration[$i] = null;
			}
		}
		$model->medicines = implode(',', $result_medicine);
		$model->times = implode(',', $result_time);
		$model->comments = implode(',', $result_comment);
		$model->durations = implode(',', $result_duration);

		$model->date = date("Y-m-d");
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function put($id, $data)
	{
		$model = new self;
		$model->reset();
		$model->load(array('id = ? AND deleted = 0', $id));

		if (!empty($data['investigation_history'])) {
			$investigation_history = new Investigation_history;
			$investigation_history->reset();
			$investigation_history->load(array('id = ? AND deleted = 0', $data['investigation_history']['id']));
			$investigation_history->investigation_descriptions = $data['investigation_descriptions'];

			$diagnoses = explode(',', $data['diagnoses']);
			for ($i = 0; $i < count($diagnoses); $i++) {
				if ($diagnoses[$i] && isset($diagnoses[$i])) {
					$diagnosis = new Diagnosis;
					$result = $diagnosis->load(array('diagnosis = ? AND deleted = 0', $diagnoses[$i]));
					if (!$result->dry()) {
						$result_diagnosis[$i] = $result->id;
					} else {
						$diagnosis->reset();
						$diagnosis->diagnosis = $diagnoses[$i];
						$diagnosis->save();
						$result_diagnosis[$i] = $diagnosis->id;
					}
				} else {
					$result_diagnosis[$i] = null;
				}
			}
			$investigation_history->diagnoses = implode(',', $result_diagnosis);
			$investigation_history->diagnosis_descriptions = $data['diagnosis_descriptions'];

			$investigation_history->save();
		}

		$medicines = explode(',', $data['medicines']);
		$times = explode(',', $data['times']);
		$comments = explode(',', $data['comments']);
		$durations = explode(',', $data['durations']);
		for ($i = 0; $i < count($medicines); $i++) {
			if ($medicines[$i] && isset($medicines[$i])) {
				$medicine = new Medicine;
				$result = $medicine->load(array('medicine = ? AND deleted = 0', $medicines[$i]));
				if (!$result->dry()) {
					$result_medicine[$i] = $result->id;
				} else {
					$medicine->reset();
					$medicine->medicine = strtoupper($medicines[$i]);
					$medicine->save();
					$result_medicine[$i] = $medicine->id;
				}
			} else {
				$result_medicine[$i] = null;
			}

			if ($times[$i] && isset($times[$i])) {
				$time = new Time;
				$result = $time->load(array('time = ? AND deleted = 0', $times[$i]));
				if (!$result->dry()) {
					$result_time[$i] = $result->id;
				} else {
					$time->reset();
					$time->time = $times[$i];
					$time->save();
					$result_time[$i] = $time->id;
				}
			} else {
				$result_time[$i] = null;
			}

			if ($comments[$i] && isset($comments[$i])) {
				$comment = new Comment;
				$result = $comment->load(array('comment = ? AND deleted = 0', $comments[$i]));
				if (!$result->dry()) {
					$result_comment[$i] = $result->id;
				} else {
					$comment->reset();
					$comment->comment = $comments[$i];
					$comment->save();
					$result_comment[$i] = $comment->id;
				}
			} else {
				$result_comment[$i] = null;
			}

			if ($durations[$i] && isset($durations[$i])) {
				$duration = new Duration;
				$result = $duration->load(array('duration = ? AND deleted = 0', $durations[$i]));
				if (!$result->dry()) {
					$result_duration[$i] = $result->id;
				} else {
					$duration->reset();
					$duration->duration = $durations[$i];
					$duration->save();
					$result_duration[$i] = $duration->id;
				}
			} else {
				$result_duration[$i] = null;
			}
		}
		$model->medicines = implode(',', $result_medicine);
		$model->times = implode(',', $result_time);
		$model->comments = implode(',', $result_comment);
		$model->durations = implode(',', $result_duration);

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
