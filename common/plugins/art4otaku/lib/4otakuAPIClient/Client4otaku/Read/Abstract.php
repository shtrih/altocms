<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 26.10.13
 * Time: 18:24
 */
namespace Client4otaku;

abstract class ReadAbstract {
	const API_BASE_URL = 'http://api.4otaku.org/';
	const IMAGE_BASE_URL = 'http://images.4otaku.org/art/';

	/**
	 * @var $_request Содержит экземпляр \Request
	 */
	protected $_request;

	/**
	 * @var array $_request_fields Содержит поля запроса к 4otaku API
	 */
	protected $_request_fields = array();

	public function __construct() {
		$classname = explode('\\', get_class($this));
		$request_url = self::API_BASE_URL
			. join(
				'/',
				preg_split(
					'/(?<!^)(?=[A-Z])/',
					end($classname)
				)
			)
		;
		$this->_request = \Request::create($request_url);
		$this->_request->setOptions(
			array(
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true
			)
		);
	}

	/**
	 * @throws \RequestException, \Client4otaku\ReadException
	 */
	public function getResponse() {
		if ($this->_request_fields) {
			$this->_request->setOption(
				CURLOPT_POSTFIELDS,
				http_build_query($this->_request_fields)
			);
		}
#var_dump(urldecode(http_build_query($this->_request_fields)));
		$response = json_decode($this->_request->sendRequest());

		if (!$response->success) {
			throw new ReadException($response->errors[0]->message, $response->errors[0]->code);
		}

		return $response;
	}

	/**
	 * @param array $fields
	 * @return $this
	 */
	protected function setFields(array $fields) {
		$this->_request_fields = array_merge($this->_request_fields, $fields);

		return $this;
	}

	/**
	 * @param $field_name
	 * @param $value
	 * @return $this
	 */
	protected function setField($field_name, $value) {
		$this->_request_fields[$field_name] = $value;

		return $this;
	}

	/**
	 * Указывает максимум элементов который надо вернуть, имеет верхний потолок для каждого api, обычно в 100 элементов
	 * @param int $value
	 * @return $this
	 */
	public function setPerPage($value) {
		$this->setField('per_page', (int)$value);

		return $this;
	}

	/**
	 * Указывает с какого по счету элемента начать возвращать
	 * @param int $value
	 * @return $this
	 */
	public function setOffset($value) {
		$this->setField('offset', (int)$value);

		return $this;
	}

	/**
	 * Работает только если не указан offset, и обозначает что надо возвращать с per_page * (page - 1) элемента, то есть обозначает номер страницы.
	 * @param int $value
	 * @return $this
	 * @see setOffset()
	 * @see setPerPage()
	 */
	public function setPage($value) {
		$this->setField('page', (int)$value);

		return $this;
	}

	/**
	 * Указывает по какому полю сортировать элементы. Сортировка влияет не только на отобранные через per_page и offset сущности, но и на всю выборку.
	 *
	 * @link http://wiki.4otaku.org/Api:%D0%A7%D1%82%D0%B5%D0%BD%D0%B8%D0%B5:%D0%90%D1%80%D1%82:%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA#.D0.9F.D0.BE.D0.BB.D0.B5_sort_value
	 * @param string $value
	 * @return $this
	 */
	public function setSortBy($value) {
		$this->setField('sort_by', $value);

		return $this;
	}

	/**
	 * Указывает в каком порядке сортировать, по возрастанию или по убыванию.
	 * desc обозначает сортировку по убыванию, asc по возрастанию. По умолчанию desc.
	 * @param string $value
	 * @return $this
	 */
	public function setSortOrder($value) {
		$this->setField('sort_order', $value);

		return $this;
	}
}

class ReadException extends \Exception {}