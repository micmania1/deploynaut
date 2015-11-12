<?php

/**
 * This is a way of consistently providing data between php and react. Its built to work with
 * Validator so that validators can be re-used and forms can easily be converted form php to
 * react.
 */
class ReactForm extends ViewableData {

	/**
	 * @var array
	 */
	protected $fields;

	/**
	 * @var ReactForm_Message
	 */
	protected $message;

	/**
	 * Where the form is going to be submitted.
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * @var Validator
	 */
	protected $validator;

	/**
	 * @param ArrayList $fields
	 */
	public function __construct(ArrayList $fields, $action) {
		foreach($fields as $field) {
			$this->fields[$field->getName()] = $field;
		}
		$this->action = $action;
	}

	/**
	 * This is needed to be compatible with Validator
	 *
	 * @return array
	 */
	public function getData() {
		$data = [];
		foreach($this->fields as $field) {
			$data[$field->getName()] = $field->getValue();
		}
		return $data;
	}

	/**
	 * Get a single field.
	 *
	 * @param string $name
	 */
	public function getField($name) {
		if(isset($this->fields[$name])) {
			return $this->fields[$name];
		}
		return null;
	}

	/**
	 * Fetch all fields
	 *
	 * @return ArrayList
	 */
	public function getFields() {
		return new ArrayList($this->fields);
	}

	/**
	 * Loads data form a given source.
	 *
	 * @param array $data
	 */
	public function loadDataFrom($data) {
		foreach($data as $key => $value) {
			if(isset($this->fields[$key])) {
				$this->fields[$key]->setValue($value);
			}
		}
	}

	/**
	 * @return Validator
	 */
	public function getValidator() {
		return $this->validator;
	}

	/**
	 * @param Validator $validator
	 */
	public function setValidator(Validator $validator) {
		$this->validator = $validator;
		$this->validator->setForm($this);
	}

	/**
	 * @return ReactForm_Message
	 */
	public function getMessage() {
		if(!$this->message) $this->message = new ReactForm_Message('', '');
		return $this->message;
	}

	/**
	 * @param string $text
	 * @param string $status
	 */
	public function setMessage($text, $status) {
		$this->message = new ReactForm_Message($text, $status);
	}

	public function toArray() {
		$fields = array();
		if(count($this->fields) > 0) {
			foreach($this->fields as $field) {
				$fields[$field->getName()] = $field->toArray();
			}
		}
		return [
			'action' => $this->action,
			'fields' => $fields,
			'message' => $this->getMessage()->toArray(),
		];
	}

	public function forTemplate() {
		return json_encode($this->toArray());
	}

}

class ReactForm_Field extends ViewableData {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $value;

	/**
	 * @var ReactForm_Message
	 */
	protected $message;

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getMessage() {
		if(!$this->message) $this->message = new ReactForm_Message('', '');
		return $this->message;
	}

	public function setMessage($text, $status) {
		$this->message = new ReactForm_Message($text, $status);
	}

	public function toArray() {
		return [
			'name' => Convert::raw2xml($this->getName()),
			'value' => Convert::raw2xml($this->getValue()),
			'message' => $this->getMessage()->toArray(),
		];
	}

	public function forTemplate() {
		return json_encode($this->toArray());
	}

}

class ReactForm_Message extends ViewableData {

	/**
	 * @var string
	 */
	protected $text;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @param string $text
	 * @param string $status
	 */
	public function __construct($text, $status) {
		$this->text = $text;
		$this->status = $status;
	}

	public function toArray() {
		return [
			'text' => Convert::raw2xml($this->text),
			'status' => Convert::raw2xml($this->status),
		];
	}

	/**
	 * @return string
	 */
	public function forTemplate() {
		return json_encode($this->toArray());
	}
}

