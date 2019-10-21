<?php

namespace Responses;

class JSONResponse extends Response
{
	protected $snake = true;
	protected $envelope = true;

	public function __construct()
	{
		parent::__construct();
	}

	public function send($records, $error = false)
	{

		$success = $error == true ? parent::ERROR : parent::SUCCESS;

		$metadata = $this->app->get('METADATAPROVIDER');
		$metadata->setStatus($success);
		$metadata->setCount(count($records));

		// Convert to snake_case if the flag is set
		if (!$this->snake) {
			$records = $this->arrayKeysToCamel($records);
		}

		$etag = md5(serialize($records));
		$this->sendHeader('E-Tag: ' . $etag);

		if (explode('/', $_SERVER[REQUEST_URI])[6] == 'pdf')
			$this->sendHeader('Content-Type: application/pdf; ' . 'charset=' . $this->app->get('ENCODING'));
		else
			$this->sendHeader('Content-Type: application/json; ' . 'charset=' . $this->app->get('ENCODING'));

		if ((!$error && $this->app->get('VERB') == 'POST') || explode('/', $_SERVER[REQUEST_URI])[6] == 'pdf') {
			$this->sendHeader("HTTP/1.0 201 Created");
		}

		$message = array();
		$message['_meta'] = $metadata->getArray();

		// Handle 0 record responses, or assign the records
		if ($metadata->getCount() === 0) {
			// This is required to make the response JSON return an empty JS object.
			// Without this, the JSON return an empty array: [] instead of {}
			$message['records'] = new \stdClass();
		} else {
			$message['records'] = $records;
		}

		if (!$this->head) {
			$response = json_encode($message);
			if (!defined('AUTOMATED_TESTING')) {
				echo $response;
			} else {
				$this->app->set('APP_RESPONSE', $response);
			}
		}

		return true;
	}

	public function convertSnakeCase($snake)
	{
		$this->snake = (bool) $snake;

		return $this;
	}

	public function useEnvelope($envelope)
	{
		$this->envelope = (bool) $envelope;

		return $this;
	}
}
