<?php

class Request {

	private $_ch;
	private $_options = array();

	private static $instance = null;

	public static function create($url, array $options = null) {
		if (null == self::$instance) {
			self::$instance = new self($url, $options);
		}

		return self::$instance;
	}

	private function __construct($url, array $options = null) {
		$this->_ch = curl_init($url);

		if ($options)
			$this->setOptions($options);

		return $this;
	}

	public function __destruct() {
		curl_close($this->_ch);
	}

	public function close() {
		self::$instance = null;
	}

	public function setOptions(array $options) {
		foreach ($options as $k => $v) {
			$this->_options[$k] = $v;
		}

		return $this;
	}

	public function setOption($option, $value) {
		$this->_options[$option] = $value;

		return $this;
	}

	public function sendRequest() {
		curl_setopt_array($this->_ch, $this->_options);

		$result = curl_exec($this->_ch);

		$errno = curl_errno($this->_ch);
		if ($errno)
			throw new RequestException(curl_error($this->_ch), $errno);

		return $result;
	}

	public function getInfo($option = null) {
		return curl_getinfo($this->_ch, $option ?: 0);
	}
}

class RequestException extends \Exception {
}
