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
				$result_time = $result->time;
				$comment = new Comment;
				$result = $comment->load(array('id = ? AND deleted = 0', $comments[$i]));
				$result_comment = $result->comment;
				$duration = new Duration;
				$result = $duration->load(array('id = ? AND deleted = 0', $durations[$i]));
				$result_duration = $result->duration;

				$medicines_html .= '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $result_medicine . '</td><td>' . $result_time . '</td><td>' . $result_comment . '</td><td>' . $result_duration . '</td></tr>';
			}
			//prs

			//inv
			if ($model->investigation_history) {
				if ($model->investigation_history->complaints) {
					$complaints_html = "";
					$complaints = explode(',', $model->investigation_history->complaints);
					$complaint_descriptions = explode(',', $model->investigation_history->complaint_descriptions);
					for ($i = 0; $i < count($complaints); $i++) {
						$complaint = new Complaint;
						$result = $complaint->load(array('id = ? AND deleted = 0', $complaints[$i]));
						if (!$complaint_descriptions[$i]) {
							$complaint_descriptions[$i] = "None";
						}
						$complaints_html .= '<tr><td><i>' . $result->complaint . '</i>: ' . $complaint_descriptions[$i] . '</td></tr>';
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
						if (!$examination_descriptions[$i]) {
							$examination_descriptions[$i] = "None";
						}
						$examinations_html .= '<tr><td><i>' . $result->examination . '</i>: ' . $examination_descriptions[$i] . '</td></tr>';
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
						if (!$investigation_descriptions[$i]) {
							$investigation_descriptions[$i] = "None";
						}
						$investigations_html .= '<tr><td><i>' . $result->investigation . '</i>: ' . $investigation_descriptions[$i] . '</td></tr>';
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
						if (!$diagnosis_descriptions[$i]) {
							$diagnosis_descriptions[$i] = "None";
						}
						$diagnoses_html .= '<tr><td><i>' . $result->diagnosis . '</i>: ' . $diagnosis_descriptions[$i] . '</td></tr>';
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
			} else if ($model->patient->age == 'F') {
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
							<table width="450">
								<tr>
									<td><h3>Rx</h3></td>
								</tr>
								' . $medicines_html . '
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
				$investigation_descriptions = explode(',', $model->investigation_descriptions);
				for ($i = 0; $i < count($investigations); $i++) {
					$investigation = new Investigation;
					$result = $investigation->load(array('id = ? AND deleted = 0', $investigations[$i]));
					if (!$investigation_descriptions[$i]) {
						$investigation_descriptions[$i] = "None";
					}
					$investigations_html .= '<tr><td><i>' . $result->investigation . '</i>: ' . $investigation_descriptions[$i] . '</td></tr>';
				}
			} else {
				$investigations_html = "<tr><td>None</td></tr>";
			}
			//inv

			//ptn
			if ($model->patient->gender == 'M') {
				$patient_gender = "Male";
			} else if ($model->patient->age == 'F') {
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
