<?php

namespace exceptions;

class HTTPException extends \Exception
{

	protected $app;

	public $devMessage;
	public $errorCode;
	public $response;
	public $additionalInfo;

	public function __construct(
		$message,
		$code = 500,
		$errorArray = array(
			'errorCode' => 500,
			'userMessage' => '',
			'dev' => '',
			'more' => '',
			'internalCode' => '',
		)
	) {

		$this->app = \Base::instance();

		$this->message = $message;
		$this->devMessage = @$errorArray['dev'];
		$this->errorCode = @$errorArray['internalCode'];
		$this->code = $code;
		$this->additionalInfo = @$errorArray['more'];
	}

	public function send()
	{
		$responder = $this->app->get('RESPONDER');
		if (empty($reponder)) {
			//fallback to JSONResponder
			$reponder = \Responses\JSONResponse::instance();
		}

		$this->app->status($this->getCode());

		$error = array(
			'errorCode' => $this->getCode(),
			'userMessage' => $this->getMessage(),
			'devMessage' => $this->devMessage,
			'more' => $this->additionalInfo,
			'applicationCode' => $this->errorCode,
		);

		$responder->send($error, true);

		return;
	}
}
