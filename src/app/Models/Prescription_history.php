<?php

namespace Models;

use \Models\Investigation_history as Investigation_history;
use \Models\Diagnosis as Diagnosis;
use \Models\Medicine as Medicine;
use \Models\Time as Time;
use \Models\Comment as Comment;
use \Models\Duration as Duration;
use \Models\Advice as Advice;
use \Models\Next_visit as Next_visit;
use \Models\Referee as Referee;

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

		if($model->diagnoses){
			$diagnoses = explode(',', $model->diagnoses);
			for ($i = 0; $i < count($diagnoses); $i++) {
				$diagnosis = new Diagnosis;
				$result = $diagnosis->load(array('id = ? AND deleted = 0', $diagnoses[$i]));
				$result_diagnosis[$i] = $result->diagnosis;
			}
			$model->diagnoses = implode(',', $result_diagnosis);
		}
		else{
			$model->diagnoses = "";
		}

		if($model->medicines){
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
		}
		else{
			$model->medicines = "";
			$model->times = "";
			$model->comments = "";
			$model->durations = "";
		}

		if($model->advices){
			$advices = explode(',', $model->advices);
			for ($i = 0; $i < count($advices); $i++) {
				$advice = new Advice;
				$result = $advice->load(array('id = ? AND deleted = 0', $advices[$i]));
				$result_advice[$i] = $result->advice;
			}
			$model->advices = implode(',', $result_advice);
		}
		else{
			$model->advices = "";
		}
		
		if($model->next_visit){
			$next_visit = new Next_visit;
			$result = $next_visit->load(array('id = ? AND deleted = 0', $model->next_visit));
			$model->next_visit = $result->next_visit;
		}
		else{
			$model->next_visit = "";
		}

		if($model->referee){
			$referee = new Referee;
			$result = $referee->load(array('id = ? AND deleted = 0', $model->referee));
			$model->referee = $result->referee;
		}
		else{
			$model->referee = "";
		}

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
						$diagnosis->diagnosis = ucwords($diagnoses[$i]);
						$diagnosis->save();
						$result_diagnosis[$i] = $diagnosis->id;
					}
				} else {
					$result_diagnosis[$i] = null;
				}
			}
			$investigation_history->diagnoses = implode(',', $result_diagnosis);
			$investigation_history->diagnosis_descriptions = ucwords($data['diagnosis_descriptions']);

			$investigation_history->save();
		} else {
			$model->investigation_history = null;

			if(!empty($data['diagnoses'])){
				$diagnoses = explode(',', $data['diagnoses']);
				for ($i = 0; $i < count($diagnoses); $i++) {
					if ($diagnoses[$i] && isset($diagnoses[$i])) {
						$diagnosis = new Diagnosis;
						$result = $diagnosis->load(array('diagnosis = ? AND deleted = 0', $diagnoses[$i]));
						if (!$result->dry()) {
							$result_diagnosis[$i] = $result->id;
						} else {
							$diagnosis->reset();
							$diagnosis->diagnosis = ucwords($diagnoses[$i]);
							$diagnosis->save();
							$result_diagnosis[$i] = $diagnosis->id;
						}
					} else {
						$result_diagnosis[$i] = null;
					}
				}
				$model->diagnoses = implode(',', $result_diagnosis);
				$model->diagnosis_descriptions = ucwords($data['diagnosis_descriptions']);
			}
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
					$time->time = ucwords($times[$i]);
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
					$duration->duration = ucwords($durations[$i]);
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
	
		$advices = explode(',', $data['advices']);
		for ($i = 0; $i < count($advices); $i++) {
			if ($advices[$i] && isset($advices[$i])) {
				$advice = new Advice;
				$result = $advice->load(array('advice = ? AND deleted = 0', $advices[$i]));
				if (!$result->dry()) {
					$result_advice[$i] = $result->id;
				} else {
					$advice->reset();
					$advice->advice = ucwords($advices[$i]);
					$advice->save();
					$result_advice[$i] = $advice->id;
				}
			} else {
				$result_advice[$i] = null;
			}
		}
		$model->advices = implode(',', $result_advice);
		
		if ($data['next_visit'] && isset($data['next_visit'])) {
			$next_visit = new Next_visit;
			$result = $next_visit->load(array('next_visit = ? AND deleted = 0', $data['next_visit']));
			if (!$result->dry()) {
				$result_next_visit = $result->id;
			} else {
				$next_visit->reset();
				$next_visit->next_visit = ucwords($data['next_visit']);
				$next_visit->save();
				$result_next_visit = $next_visit->id;
			}
		} else {
			$result_next_visit = null;
		}
		$model->next_visit = $result_next_visit;
		
		if ($data['referee'] && isset($data['referee'])) {
			$referee = new Referee;
			$result = $referee->load(array('referee = ? AND deleted = 0', $data['referee']));
			if (!$result->dry()) {
				$result_referee = $result->id;
			} else {
				$referee->reset();
				$referee->referee = ucwords($data['referee']);
				$referee->save();
				$result_referee = $referee->id;
			}
		} else {
			$result_referee = null;
		}
		$model->referee = $result_referee;

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
						$diagnosis->diagnosis = ucwords($diagnoses[$i]);
						$diagnosis->save();
						$result_diagnosis[$i] = $diagnosis->id;
					}
				} else {
					$result_diagnosis[$i] = null;
				}
			}
			$investigation_history->diagnoses = implode(',', $result_diagnosis);
			$investigation_history->diagnosis_descriptions = ucwords($data['diagnosis_descriptions']);

			$investigation_history->save();
		}
		else{
			if(!empty($data['diagnoses'])){
				$diagnoses = explode(',', $data['diagnoses']);
				for ($i = 0; $i < count($diagnoses); $i++) {
					if ($diagnoses[$i] && isset($diagnoses[$i])) {
						$diagnosis = new Diagnosis;
						$result = $diagnosis->load(array('diagnosis = ? AND deleted = 0', $diagnoses[$i]));
						if (!$result->dry()) {
							$result_diagnosis[$i] = $result->id;
						} else {
							$diagnosis->reset();
							$diagnosis->diagnosis = ucwords($diagnoses[$i]);
							$diagnosis->save();
							$result_diagnosis[$i] = $diagnosis->id;
						}
					} else {
						$result_diagnosis[$i] = null;
					}
				}
				$model->diagnoses = implode(',', $result_diagnosis);
				$model->diagnosis_descriptions = ucwords($data['diagnosis_descriptions']);
			}
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
					$time->time = ucwords($times[$i]);
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
					$comment->comment = ucwords($comments[$i]);
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
					$duration->duration = ucwords($durations[$i]);
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
	
		$advices = explode(',', $data['advices']);
		for ($i = 0; $i < count($advices); $i++) {
			if ($advices[$i] && isset($advices[$i])) {
				$advice = new Advice;
				$result = $advice->load(array('advice = ? AND deleted = 0', $advices[$i]));
				if (!$result->dry()) {
					$result_advice[$i] = $result->id;
				} else {
					$advice->reset();
					$advice->advice = ucwords($advices[$i]);
					$advice->save();
					$result_advice[$i] = $advice->id;
				}
			} else {
				$result_advice[$i] = null;
			}
		}
		$model->advices = implode(',', $result_advice);
		
		if ($data['next_visit'] && isset($data['next_visit'])) {
			$next_visit = new Next_visit;
			$result = $next_visit->load(array('next_visit = ? AND deleted = 0', $data['next_visit']));
			if (!$result->dry()) {
				$result_next_visit = $result->id;
			} else {
				$next_visit->reset();
				$next_visit->next_visit = ucwords($data['next_visit']);
				$next_visit->save();
				$result_next_visit = $next_visit->id;
			}
		} else {
			$result_next_visit = null;
		}
		$model->next_visit = $result_next_visit;
		
		if ($data['referee'] && isset($data['referee'])) {
			$referee = new Referee;
			$result = $referee->load(array('referee = ? AND deleted = 0', $data['referee']));
			if (!$result->dry()) {
				$result_referee = $result->id;
			} else {
				$referee->reset();
				$referee->referee = ucwords($data['referee']);
				$referee->save();
				$result_referee = $referee->id;
			}
		} else {
			$result_referee = null;
		}
		$model->referee = $result_referee;

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
