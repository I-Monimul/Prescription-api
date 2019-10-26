<?php

namespace Models;

use \Models\Prescription_history as Prescription_history;
use \Models\Medicine as Medicine;
use \Models\Time as Time;
use \Models\Comment as Comment;
use \Models\Duration as Duration;
use \Models\Investigation_history as Investigation_history;
use \Models\Complaint as Complaint;
use \Models\Examination as Examination;
use \Models\Investigation as Investigation;
use \Models\Diagnosis as Diagnosis;
use \Models\Advice as Advice;
use \Models\Next_visit as Next_visit;
use \Models\Referee as Referee;

class Pdf
{
	public static function getOne($id)
	{
		$id = explode(',', $id);
		if ($id[0] == 'p') {
			//prs
			$model = new Prescription_history;
			$model->load(array('id = ? AND deleted = 0', $id[1]));

			$medicines_html = "";
			$medicines = explode(',', $model->medicines);
			$comments = explode(',', $model->comments);
			$times = explode(',', $model->times);
			$durations = explode(',', $model->durations);
			for ($i = 0; $i < count($medicines); $i++) {
				$medicine = new Medicine;
				$result = $medicine->load(array('id = ? AND deleted = 0', $medicines[$i]));
				$result_medicine = $result->medicine;
				$time = new Time;
				$result = $time->load(array('id = ? AND deleted = 0', $times[$i]));
				if($result->alternative){
					$result_time = $result->alternative;
				}
				else{
					$result_time = $result->time;
				}
				$comment = new Comment;
				$result = $comment->load(array('id = ? AND deleted = 0', $comments[$i]));
				if($result->alternative){
					$result_comment = $result->alternative;
				}
				else{
					$result_comment = $result->comment;
				}
				$duration = new Duration;
				$result = $duration->load(array('id = ? AND deleted = 0', $durations[$i]));
				if($result->alternative){
					$result_duration = $result->alternative;
				}
				else{
					$result_duration = $result->duration;
				}

				$medicines_html .= '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $result_medicine . '</td><td>' . $result_time . '</td><td>' . $result_comment . '</td><td>' . $result_duration . '</td></tr>';
			}
			//prs

			//oth
			$advices_html = "<tr><td><h3>Advices</h3></td></tr>";
			if($model->advices){
				$advices = explode(',', $model->advices);
				for ($i = 0; $i < count($advices); $i++) {
					$advice = new Advice;
					$result = $advice->load(array('id = ? AND deleted = 0', $advices[$i]));
					if($result->alternative){
						$result_advice = $result->alternative;
					}
					else{
						$result_advice = $result->advice;
					}

					$advices_html .= '<tr><td>' . $result_advice . '</td></tr>';
				}
			}
			else{
				$advices_html = "";
			}

			if($model->next_visit){
					$next_visit = new Next_visit;
					$result = $next_visit->load(array('id = ? AND deleted = 0', $model->next_visit));
					if($result->alternative){
						$result_next_visit = $result->alternative;
					}
					else{
						$result_next_visit = $result->next_visit;
					}

					$next_visit_html = '<tr><td><h3>পরবর্তী সাক্ষাৎ</h3></td></tr><tr><td>' . $result_next_visit . '</td></tr>';
			}
			else{
				$next_visit_html = "";
			}

			if($model->referee){
					$referee = new Referee;
					$result = $referee->load(array('id = ? AND deleted = 0', $model->referee));
					$result_referee = $result->referee;

					$referee_html = '<tr><td><h3>Refered to</h3></td></tr><tr><td>' . $result_referee . '</td></tr>';
			}
			else{
				$referee_html = "";
			}
			//oth

			//inv
			if ($model->investigation_history) {
				if ($model->investigation_history->complaints) {
					$complaints_html = "";
					$complaints = explode(',', $model->investigation_history->complaints);
					$complaint_descriptions = explode(',', $model->investigation_history->complaint_descriptions);
					for ($i = 0; $i < count($complaints); $i++) {
						$complaint = new Complaint;
						$result = $complaint->load(array('id = ? AND deleted = 0', $complaints[$i]));
						$complaints_html .= '<tr><td><i>' . $result->complaint . '</i>';
						if($complaint_descriptions[$i]){
							$complaints_html .= ': ' . $complaint_descriptions[$i];
						}
						$complaints_html .= '</td></tr>';
					}
				} else {
					$complaints_html = "<tr><td>None</td></tr>";
				}

				if ($model->investigation_history->examinations) {
					$examinations_html = "";
					$examinations = explode(',', $model->investigation_history->examinations);
					$examination_descriptions = explode(',', $model->investigation_history->examination_descriptions);
					for ($i = 0; $i < count($examinations); $i++) {
						$examination = new Examination;
						$result = $examination->load(array('id = ? AND deleted = 0', $examinations[$i]));
						$examinations_html .= '<tr><td><i>' . $result->examination . '</i>';
						if($examination_descriptions[$i]){
							$examinations_html .= ': ' . $examination_descriptions[$i];
						}
						$examinations_html .= '</td></tr>';
					}
				} else {
					$examinations_html = "<tr><td>None</td></tr>";
				}

				if ($model->investigation_history->investigations) {
					$investigations_html = "";
					$investigations = explode(',', $model->investigation_history->investigations);
					$investigation_descriptions = explode(',', $model->investigation_history->investigation_descriptions);
					for ($i = 0; $i < count($investigations); $i++) {
						$investigation = new Investigation;
						$result = $investigation->load(array('id = ? AND deleted = 0', $investigations[$i]));
						$investigations_html .= '<tr><td><i>' . $result->investigation . '</i>';
						if($investigation_descriptions[$i]){
							$investigations_html .= ': ' . $investigation_descriptions[$i];
						}
						$investigations_html .= '</td></tr>';
					}
				} else {
					$investigations_html = "<tr><td>None</td></tr>";
				}

				if ($model->investigation_history->diagnoses) {
					$diagnoses_html = "";
					$diagnoses = explode(',', $model->investigation_history->diagnoses);
					$diagnosis_descriptions = explode(',', $model->investigation_history->diagnosis_descriptions);
					for ($i = 0; $i < count($diagnoses); $i++) {
						$diagnosis = new Diagnosis;
						$result = $diagnosis->load(array('id = ? AND deleted = 0', $diagnoses[$i]));
						$diagnoses_html .= '<tr><td><i>' . $result->diagnosis . '</i>';
						if($diagnosis_descriptions[$i]){
							$diagnoses_html .= ': ' . $diagnosis_descriptions[$i];
						}
						$diagnoses_html .= '</td></tr>';
					}
				} else {
					$diagnoses_html = "<tr><td>None</td></tr>";
				}
			} else {
				$complaints_html = "<tr><td>None</td></tr>";
				$examinations_html = "<tr><td>None</td></tr>";
				$investigations_html = "<tr><td>None</td></tr>";
				$diagnoses_html = "<tr><td>None</td></tr>";
			}
			//inv

			//ptn
			if ($model->patient->gender == 'M') {
				$patient_gender = "Male";
			} else if ($model->patient->gender == 'F') {
				$patient_gender = "Female";
			} else {
				$patient_gender = "Others";
			}
			//ptn

			$html = '
				<br/><br/><br/>
				<hr />
				<table width="700">
					<tr>
						<td colspan="4"><h3>Patient:</h3></td>
					</tr>
					<tr>
						<td>Name: <b>' . $model->patient->name . '</b></td>
						<td>Age: <b>' . $model->patient->age . '</b></td>
						<td>Gender: <b>' . $patient_gender . '</b></td>
						<td>Date: <b>' . date("d-m-Y", strtotime($model->date)) . '</b></td>
					</tr>
				</table>
				<hr style="height: 5px;" />
				<table width="700">
					<tr>
						<td style="border-right: 5px solid #dbdbdb;">
							<table width="250">
								<tr>
									<td><h3>Diagnoses</h3></td>
								</tr>
								' . $diagnoses_html . '<br/><br/>
								<tr>
									<td><h3>Complaints</h3></td>
								</tr>
								' . $complaints_html . '<br/><br/>
								<tr>
									<td><h3>Examinations</h3></td>
								</tr>
								' . $examinations_html . '<br/><br/>
								<tr>
									<td><h3>Investigations</h3></td>
								</tr>
								' . $investigations_html . '
							</table>
						</td>
						<td>
							<table width="450" style="font-family: solaimanlipi;">
								<tr>
									<td><h3>Rx</h3></td>
								</tr>
								' . $medicines_html . '
							</table>
							<br/>
							<table width="450" style="font-family: solaimanlipi;">
								' . $advices_html . '
								' . $next_visit_html . '
								' . $referee_html . '
							</table>
						</td>
					</tr>
				</table>
			';
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf->WriteHTML($html);
			return $mpdf->Output();
		} else if ($id[0] == 'i') {
			//inv
			$model = new Investigation_history;
			$model->load(array('id = ? AND deleted = 0', $id[1]));

			if ($model->investigations) {
				$investigations_html = "";
				$investigations = explode(',', $model->investigations);
				for ($i = 0; $i < count($investigations); $i++) {
					$investigation = new Investigation;
					$result = $investigation->load(array('id = ? AND deleted = 0', $investigations[$i]));
					$investigations_html .= '<tr><td><i>' . $result->investigation . '</i></td></tr>';
				}
			} else {
				$investigations_html = "<tr><td>None</td></tr>";
			}
			//inv

			//ptn
			if ($model->patient->gender == 'M') {
				$patient_gender = "Male";
			} else if ($model->patient->gender == 'F') {
				$patient_gender = "Female";
			} else {
				$patient_gender = "Others";
			}
			//ptn

			$html = '
				<br/><br/><br/>
				<hr />
				<table width="400">
					<tr>
						<td colspan="4"><h3>Patient:</h3></td>
					</tr>
					<tr>
						<td>Name: <b>' . $model->patient->name . '</b></td>
						<td>Age: <b>' . $model->patient->age . '</b></td>
					</tr>
					<tr>
						<td>Gender: <b>' . $patient_gender . '</b></td>
						<td>Date: <b>' . date("d-m-Y", strtotime($model->date)) . '</b></td>
					</tr>
				</table>
				<hr style="height: 5px;" />
				<table width="400">
					<tr>
						<td><h3>Investigations</h3></td>
					</tr>
					' . $investigations_html . '
				</table>
			';
			$mpdf = new \Mpdf\Mpdf(['mode' => 'UTF-8', 'format' => 'A5']);
			$mpdf->WriteHTML($html);
			return $mpdf->Output();
		}
	}
}
