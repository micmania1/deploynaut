<?php

class DeploymentStrategy {

	/**
	 * @var DeployForm
	 */
	protected $form;

	/**
	 * @var DNEnvironment
	 */
	protected $environment;

	/**
	 * @var string
	 */
	protected $sha;

	/**
	 * @var DNProject
	 */
	protected $project;

	/**
	 * @var string
	 */
	protected $actionTitle;

	/**
	 * @var string
	 */
	protected $actionCode;

	/**
	 * @var string
	 */
	protected $estimatedTime;

	/**
	 * @var string
	 */
	protected $changes;

	/**
	 * @var string
	 */
	protected $options;

	/**
	 * @var string
	 */
	protected $validator;

	function __construct(DeployForm $form, DNEnvironment $environment, $sha, DNProject $project) {
		$this->form = $form;
		$this->environment = $environment;
		$this->sha = $sha;
		$this->project = $project;
		$this->validator = new ValidationResult();
	}

	/**
	 * @param string $title
	 */
	public function setActionTitle($title) {
		$this->actionTitle = $title;
	}

	/**
	 * @return string
	 */
	public function getActionTitle() {
		return $this->actionTitle;
	}

	/**
	 * @param string $title
	 */
	public function setActionCode($code) {
		$this->actionCode = $code;
	}

	/**
	 * @return string
	 */
	public function getActionCode() {
		return $this->actionCode;
	}

	/**
	 * @param int
	 */
	public function setEstimatedTime($seconds) {
		$this->estimatedTime = $seconds;
	}

	/**
	 * @return int Time in seconds
	 */
	public function getEstimatedTime() {
		return $this->estimatedTime;
	}

	/**
	 * @param string $title
	 * @param string $from
	 * @param string $to
	 */
	public function setChange($title, $from, $to) {
		return $this->changes[$title] = array(
			'from' => $from,
			'to' => $to
		);
	}

	/**
	 * @return array Associative array of changes, e.g.
	 *	array(
	 *		'SHA' => array(
	 *			'from' => 'abc',
	 *			'to' => 'def'
	 *		)
	 *	)
	 */
	public function getChanges() {
		return $this->changes;
	}

	/**
	 * @param string $option
	 * @param string $value
	 */
	function setOption($option, $value) {
		$this->options[$option] = $value;
	}

	/**
	 * @return array
	 */
	function getOptions() {
		return $this->options;
	}

	/**
	 * @return bool
	 */
	public function hasErrors() {
		return !$this->validator->valid();
	}

	/**
	 * @return ValidationResult
	 */
	public function getValidationResult() {
		return $this->validator;
	}

	/**
	 * @return DNDeployment
	 */
	public function createDeployment() {
		$deployment = DNDeployment::create();
		$deployment->EnvironmentID = $this->environment->ID;
		$deployment->SHA = $this->sha;
		$deployment->Options = json_encode($options);
		$deployment->write();

		return $deployment;
	}
}

