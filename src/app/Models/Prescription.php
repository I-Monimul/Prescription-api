<?php

namespace Models;

use \Models\Prescription_history as Prescription_history;

class Prescription
{
	public static function getOne($id)
	{
		$model = new Prescription_history;
		$model->load(array('patient = ? AND deleted = 0 order by id desc', $id));

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
}
