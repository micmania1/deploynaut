<?php

class CapistranoDeploymentBackend implements DeploymentBackend {

	/**
	 * Return information about the current build on the given environment.
	 * Returns a map with keys:
	 * - 'buildname' - the non-simplified name of the build deployed
	 * - 'datetime' - the datetime when the deployment occurred, in 'Y-m-d H:i:s' format
	 */
	public function currentBuild($environment) {
		$file = DEPLOYNAUT_LOG_PATH . '/' . $environment . ".deploy-history.txt";

		if(file_exists($file)) {
			$lines = file($file);
			$lastLine = array_pop($lines);
			return $this->convertLine($lastLine);
		}
	}

	/**
	 * Deploy the given build to the given environment.
	 */
	public function deploy($environment, $sha, DeploynautLogFile $log, DNProject $project) {
		$repository = $project->LocalCVSPath;
		$projectName = $project->Name;
		$env = $project->getProcessEnv();

		$project = DNProject::get()->filter('Name', $projectName)->first();
		GraphiteDeploymentNotifier::notify_start($environment, $sha, null, $project);

		$log->write('Deploying "'.$sha.'" to "'.$projectName.':'.$environment.'"');

		$args = array(
			'branch' => $sha,
			'repository' => $repository
		);
		$command = $this->getCommand('deploy', $projectName.':'.$environment, $args, $env, $log);
		$command->run(function ($type, $buffer) use($log) {
			$log->write($buffer);
		});
		if(!$command->isSuccessful()) {
			throw new RuntimeException($command->getErrorOutput());
		}
		$log->write('Deploy done "'.$sha.'" to "'.$projectName.':'.$environment.'"');

		GraphiteDeploymentNotifier::notify_end($environment, $sha, null, $project);
	}
	
	/**
	 * Deploy the given build to the given environment.
	 * 
	 * @param DNProject $environment
	 * @param string $environment
	 */
	public function ping($environment, DeploynautLogFile $log, DNProject $project) {
		$projectName  = $project->Name;
		$env = $project->getProcessEnv();
		$command = $this->getCommand('deploy:check', $projectName.':'.$environment, null, $env, $log);
		$command->run(function ($type, $buffer) use($log) {
			$log->write($buffer);
			echo $buffer;
		});
	}

	/**
	 * @param string $action Capistrano action to be executed
	 * @param string $environment Capistrano identifier for the environment (see capistrano-multiconfig)
	 * @param array $args Additional arguments for process
	 * @param string $env Additional environment variables
	 * @param DeploynautLogFile $log
	 * @return \Symfony\Component\Process\Process
	 */
	protected function getCommand($action, $environment, $args = null, $env = null, DeploynautLogFile $log) {
		if(!$args) $args = array();
		$args['history_path'] = realpath(DEPLOYNAUT_LOG_PATH.'/');

		// Inject env string directly into the command.
		// Capistrano doesn't like the $process->setEnv($env) we'd normally do below.
		$envString = '';
		if (!empty($env)) {
			$envString .= 'env ';
			foreach ($env as $key => $value) {
				$envString .= "$key=\"$value\" ";
			}
		}

		$data = Injector::inst()->get('DNData');
		// Generate a capfile from a template
		$capTemplate = file_get_contents(BASE_PATH.'/deploynaut/Capfile.template');
		$cap = str_replace(
			array('<config root>', '<ssh key>', '<base path>'),
			array($data->getEnvironmentDir(), DEPLOYNAUT_SSH_KEY, BASE_PATH),
			$capTemplate
		);

		if(defined('DEPLOYNAUT_CAPFILE')) {
			$capFile = DEPLOYNAUT_CAPFILE;
		} else {
			$capFile = BASE_PATH.'/assets/Capfile';
		}
		file_put_contents($capFile, $cap);

		$command = "{$envString}cap -f " . escapeshellarg($capFile) . " -vv $environment $action";
		foreach($args as $argName => $argVal) {
			$command .= ' -s ' . escapeshellarg($argName) . '=' . escapeshellarg($argVal);
		}

		$log->write("Running command: $command");

		$process = new \Symfony\Component\Process\Process($command);
		// Capistrano doesn't like it - see comment above.
		//$process->setEnv($env);
		$process->setTimeout(3600);
		return $process;
	}

}
