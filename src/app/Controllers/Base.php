<?php

namespace Controllers;

use Exceptions\HTTPException;
use Utils\Identity;
use Utils\AccessHandler;


class Base
{
	protected $app;

	protected $requestBody;

	protected $identity;

	protected $currentAction = null;

	//Route params
	protected $params = [];

	protected $isSearch = false;
	protected $limit = 20;
	protected $offset = 0;
	protected $filters = [];


	protected $searchFields = null;
	protected $allowedSearchFields = [];

	//for dynamically resolving models
	//depending on user type
	protected $modelsMap = [];

	// by default:
	// - all routes are allowed for all authenticated users
	// - except routes marked with Identity::CONTEXTs
	// - routes marked with TRUE are allowed for unauthenticated users too
	// example:
	// ['get' => true, 'post' => [Identity::CONTEXT_ADMIN, Identity::CONTEXT_FRANCHISEE_ADMIN]]
	// above, `get` is allowed for all unauthenticated users and
	// `post` is allowed for Admins and Franchisee Admins
	protected $accessList = [];

	private $parseQS = true;

	public function __construct(\Base $app, $parseQS = true)
	{
		$this->app = \Base::instance();

		$this->parseQS = $parseQS;

		$this->params      = $this->app->get('PARAMS');
		$this->identity    = $this->app->get('IDENTITY');

		$this->setCurrentAction();

		return;
	}

	//parses self->accessList to
	//work out how to handle method permissions
	public function beforeroute()
	{
		$ret = true;

		if ($ret) {
			if ($this->parseQS) {
				$this->parseRequest();
			}

			$this->requestBody = $this->app->get('REQUESTBODY')->parse();

			return true;
		}

		throw new HTTPException(
			'Unauthorized.',
			401,
			array(
				'dev' => 'Insufficient permissions to access this resource',
				'internalCode' => '',
				'more' => ''
			)
		);

		return false;
	}

	public function afterroute()
	{ }

	protected function setCurrentAction()
	{
		//work out method to be called
		$pattern = $this->app->get('PATTERN');
		$verb    = $this->app->get('VERB');
		$routes  = $this->app->get('ROUTES');

		if ($verb != 'OPTIONS') {
			if (isset($routes[$pattern])) {
				if (
					isset($routes[$pattern][3]) &&
					isset($routes[$pattern][3][$verb]) &&
					isset($routes[$pattern][3][$verb][0])
				) {
					$handler = explode('->', $routes[$pattern][3][$verb][0]);
					if (isset($handler[1])) {
						$this->currentAction = $handler[1];
					}
				}
			}
		}
	}

	protected function parseRequest()
	{
		$GETS = $this->app->get('GET');

		$searchParams = null;
		if (isset($GETS['q'])) {
			$searchParams = $GETS['q'];
		}

		// Set limits and offset, elsewise allow them to have defaults set in the Controller
		$currentLimit = $this->limit;
		$this->limit = isset($GETS['limit']) ? $GETS['limit'] : $this->limit;
		$this->offset = isset($GETS['offset']) ? $GETS['offset'] : $this->offset;
		if ($this->offset < 0) {
			$this->offset = 0;
		}
		if ($this->limit < 1) {
			$this->limit = $currentLimit;
		}

		// If there's a 'q' parameter, parse the fields, then determine that all the fields in the search
		// are allowed to be searched from $allowedFields['search']
		if ($searchParams) {
			$this->isSearch = true;

			$this->parseSearchParameters($searchParams);

			// This handy snippet determines if searchFields is a strict subset of allowedSearchFields
			if (array_diff(array_keys($this->searchFields), $this->allowedSearchFields)) {
				throw new HTTPException(
					"The fields you specified cannot be searched.",
					401,
					array(
						'dev' => 'You requested to search fields that are not available to be searched.',
						'internalCode' => '',
						'more' => ''
					)
				);
			}
		}
	}

	/**
	 * Parses out the search parameters from a request.
	 * And populates $this->searchFields and $this->filters
	 * $this->searchFields object is used to check whether a field searched on is actually searchable or not
	 * Unparsed, they will look like this:
	 *    (name:Benjamin Franklin,location:Philadelphia,age[gt]:12)
	 * searchFields object:
	 *     array('name'=>'Benjamin Franklin', 'location'=>'Philadelphia', 'age'=>12)
	 * filters object:
	 *    array('field'=>'name', 'value'=>'Benjamin Franklin', 'condition'=>'eq'),
	 *    array('field'=>'location', 'value'=>'Philadelphia', 'condition'=>'eq'),
	 *    array('field'=>'age', 'value'=>'12', 'condition'=>'gt')
	 * @param string $unparsed Unparsed search string
	 */
	protected function parseSearchParameters($unparsed)
	{
		// Strip parens that come with the request string
		$unparsed = trim($unparsed, '()');

		// Now we have an array of "key:value" strings.
		$splitFields = array_map('trim', explode(',', $unparsed));
		$mapped = array();

		// Split the strings at their colon, set left to key, and right to value.
		foreach ($splitFields as $field) {
			if (trim($field) != "") {
				$splitField = array_map('trim', explode(':', $field));
				//filter out the condition
				preg_match("/([^\[\]]*)(\[([^\[\]]+)\])?([|])?/", $splitField[0], $matches);
				if (isset($matches[1])) {
					$mapped[$matches[1]] = isset($splitField[1]) ? $splitField[1] : "";
					$filter = array(
						'field' => $matches[1],
						'value' => isset($splitField[1]) ? $splitField[1] : "",
						'condition' => 'eq',
						'jointype' => 'AND'
					);
					if (isset($matches[3])) {
						$filter['condition'] = $matches[3];
					}
					if (isset($matches[4])) {
						$filter['jointype'] = 'OR';
					}
					$this->filters[] = $filter;
				}
			}
		}

		$this->searchFields = $mapped;
	}

	protected function getModel($key = null)
	{
		if (!$key) {
			$identity = $this->app->get('IDENTITY');
			if (isset($this->modelsMap[$identity->context])) {
				return $this->modelsMap[$identity->context];
			} elseif (isset($this->modelsMap['default'])) {
				return $this->modelsMap['default'];
			}
		} else {
			if (isset($this->modelsMap[$key])) {
				return $this->modelsMap[$key];
			}
		}

		throw new HTTPException(
			"Unable to resolve model",
			500,
			array(
				'dev' => 'Check that the models are defined properly',
				'internalCode' => '',
				'more' => ''
			)
		);
	}

	protected function respond($data)
	{
		if (!is_array($data)) {
			// This is bad.  Throw a 500.  Responses should always be arrays.
			throw new HTTPException(
				"An error occured while retrieving records.",
				500,
				array(
					'dev' => 'The records returned were malformed.',
					'internalCode' => '',
					'more' => ''
				)
			);
		}

		$this->app->get('RESPONDER')->send($data);
	}
}
