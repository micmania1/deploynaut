<?php
/*
 * DNProjectList picks up namespaces in the envs directory.
 * Namespace is capistrano-multiconfig way of saying "directory".
 * They can be nested, but we only use single level here to
 * specify projects.
 */

class DNProjectList extends ArrayList {

	protected $data;

	protected $projects;

	function __construct(DNData $data) {
		$this->data = $data;

		$projects = $this->getProjects();

		// Build the projects array that can be referenced by name.
		$this->projects = array();
		foreach($projects as $project) $this->projects[$project->getName()] = $project;

		parent::__construct($projects);
	}

	/**
	 * Grabs a list of projects from the env directory. The projects
	 * in the builds directory alone will not be picked up.
	 */
	function getProjects() {
		$projects = array();
		foreach(scandir($this->data->getEnvironmentDir()) as $project) {
			$path = $this->data->getEnvironmentDir().'/'.$project;
			if(is_dir($path) && $project!='.' && $project!='..') {
				$projects[$project] = new DNProject($project, $this->data);
			}
		}
		ksort($projects);
		return array_values($projects);
	}

	function byName($name) {
		return $this->projects[$name];
	}
	
}