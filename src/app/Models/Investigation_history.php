<?php

namespace Models;

use \Models\Complaint as Complaint;
use \Models\Examination as Examination;
use \Models\Investigation as Investigation;
use \Models\Diagnosis as Diagnosis;

class Investigation_history extends \Models\Base\Investigation_history
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

		if($model->complaints){
			$complaints = explode(',', $model->complaints);
			for ($i = 0; $i < count($complaints); $i++) {
				$complaint = new Complaint;
				$result = $complaint->load(array('id = ? AND deleted = 0', $complaints[$i]));
				$result_complaint[$i] = $result->complaint;
			}
			$model->complaints = implode(',', $result_complaint);
		}
		else{
			$model->complaints = "";
		}

		if($model->examinations){
			$examinations = explode(',', $model->examinations);
			for ($i = 0; $i < count($examinations); $i++) {
				$examination = new Examination;
				$result = $examination->load(array('id = ? AND deleted = 0', $examinations[$i]));
				$result_examination[$i] = $result->examination;
			}
			$model->examinations = implode(',', $result_examination);
		}
		else{
			$model->examinations = "";
		}

		if($model->investigations){
			$investigations = explode(',', $model->investigations);
			for ($i = 0; $i < count($investigations); $i++) {
				$investigation = new Investigation;
				$result = $investigation->load(array('id = ? AND deleted = 0', $investigations[$i]));
				$result_investigation[$i] = $result->investigation;
			}
			$model->investigations = implode(',', $result_investigation);
		}
		else{
			$model->investigations = "";
		}

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

		return empty($model) ? [] : $model->cast();
	}

	public static function post($data)
	{
		$model = new self;
		$model->patient = $data['patient']['id'];

		if ($data['complaints']) {
			$complaints = explode(',', $data['complaints']);
			for ($i = 0; $i < count($complaints); $i++) {
				if ($complaints[$i] && isset($complaints[$i])) {
					$complaint = new Complaint;
					$result = $complaint->load(array('complaint = ? AND deleted = 0', $complaints[$i]));
					if (!$result->dry()) {
						$result_complaint[$i] = $result->id;
					} else {
						$complaint->reset();
						$complaint->complaint = ucwords($complaints[$i]);
						$complaint->save();
						$result_complaint[$i] = $complaint->id;
					}
				} else {
					$result_complaint[$i] = null;
				}
			}
			$model->complaints = implode(',', $result_complaint);

			$model->complaint_descriptions = ucwords($data['complaint_descriptions']);
		}

		if ($data['examinations']) {
			$examinations = explode(',', $data['examinations']);
			for ($i = 0; $i < count($examinations); $i++) {
				if ($examinations[$i] && isset($examinations[$i])) {
					$examination = new Examination;
					$result = $examination->load(array('examination = ? AND deleted = 0', $examinations[$i]));
					if (!$result->dry()) {
						$result_examination[$i] = $result->id;
					} else {
						$examination->reset();
						$examination->examination = ucwords($examinations[$i]);
						$examination->save();
						$result_examination[$i] = $examination->id;
					}
				} else {
					$result_examination[$i] = null;
				}
			}
			$model->examinations = implode(',', $result_examination);

			$model->examination_descriptions = ucwords($data['examination_descriptions']);
		}

		if ($data['investigations']) {
			$investigations = explode(',', $data['investigations']);
			for ($i = 0; $i < count($investigations); $i++) {
				if ($investigations[$i] && isset($investigations[$i])) {
					$investigation = new Investigation;
					$result = $investigation->load(array('investigation = ? AND deleted = 0', $investigations[$i]));
					if (!$result->dry()) {
						$result_investigation[$i] = $result->id;
					} else {
						$investigation->reset();
						$investigation->investigation = ucwords($investigations[$i]);
						$investigation->save();
						$result_investigation[$i] = $investigation->id;
					}
				} else {
					$result_investigation[$i] = null;
				}
			}
			$model->investigations = implode(',', $result_investigation);
		}

		$model->date = date("Y-m-d");
		$model->save();
		return empty($model) ? [] : $model->cast();
	}

	public static function put($id, $data)
	{
		$model = new self;
		$model->reset();
		$model->load(array('id = ? AND deleted = 0', $id));

		if ($data['complaints']) {
			$complaints = explode(',', $data['complaints']);
			for ($i = 0; $i < count($complaints); $i++) {
				if ($complaints[$i] && isset($complaints[$i])) {
					$complaint = new Complaint;
					$result = $complaint->load(array('complaint = ? AND deleted = 0', $complaints[$i]));
					if (!$result->dry()) {
						$result_complaint[$i] = $result->id;
					} else {
						$complaint->reset();
						$complaint->complaint = ucwords($complaints[$i]);
						$complaint->save();
						$result_complaint[$i] = $complaint->id;
					}
				} else {
					$result_complaint[$i] = null;
				}
			}
			$model->complaints = implode(',', $result_complaint);

			$model->complaint_descriptions = ucwords($data['complaint_descriptions']);
		}

		if ($data['examinations']) {
			$examinations = explode(',', $data['examinations']);
			for ($i = 0; $i < count($examinations); $i++) {
				if ($examinations[$i] && isset($examinations[$i])) {
					$examination = new Examination;
					$result = $examination->load(array('examination = ? AND deleted = 0', $examinations[$i]));
					if (!$result->dry()) {
						$result_examination[$i] = $result->id;
					} else {
						$examination->reset();
						$examination->examination = ucwords($examinations[$i]);
						$examination->save();
						$result_examination[$i] = $examination->id;
					}
				} else {
					$result_examination[$i] = null;
				}
			}
			$model->examinations = implode(',', $result_examination);

			$model->examination_descriptions = ucwords($data['examination_descriptions']);
		}

		if ($data['investigations']) {
			$investigations = explode(',', $data['investigations']);
			for ($i = 0; $i < count($investigations); $i++) {
				if ($investigations[$i] && isset($investigations[$i])) {
					$investigation = new Investigation;
					$result = $investigation->load(array('investigation = ? AND deleted = 0', $investigations[$i]));
					if (!$result->dry()) {
						$result_investigation[$i] = $result->id;
					} else {
						$investigation->reset();
						$investigation->investigation = ucwords($investigations[$i]);
						$investigation->save();
						$result_investigation[$i] = $investigation->id;
					}
				} else {
					$result_investigation[$i] = null;
				}
			}
			$model->investigations = implode(',', $result_investigation);
		}

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
