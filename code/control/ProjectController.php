<?php

class ProjectController extends DNRoot {

	private static $allowed_actions = [
		'getDeployForm',
		'getDataTransferForm',
		'getDataTransferRestoreForm',
		'getDeleteForm',
		'getMoveForm',
		'getUploadSnapshotForm',
		'metrics',
		'pipeline',
		'deploySummary',
		'gitRevisions',
		'startDeploy',
		'deploylog',
		'deploy',
		'transferlog',
		'transfer',
		'environment',
		'createenvlog',
		'createenv',
		'getCreateEnvironmentForm',
		'branch',
		'build',
		'restoresnapshot',
		'deletesnapshot',
		'movesnapshot',
		'update',
		'snapshots',
		'createsnapshot',
		'uploadsnapshot',
		'snapshotlog',
		'postsnapshotsuccess',
		'toggleprojectstar',
		'project',
	];

	private static $url_handlers = [
		'$Project!/environment/$Environment/DeployForm' => 'getDeployForm',
		'$Project!/createsnapshot/DataTransferForm' => 'getDataTransferForm',
		'$Project!/DataTransferForm' => 'getDataTransferForm',
		'$Project!/DataTransferRestoreForm' => 'getDataTransferRestoreForm',
		'$Project!/DeleteForm' => 'getDeleteForm',
		'$Project!/MoveForm' => 'getMoveForm',
		'$Project!/UploadSnapshotForm' => 'getUploadSnapshotForm',
		'$Project!/PostSnapshotForm' => 'getPostSnapshotForm',
		'$Project!/environment/$Environment/metrics' => 'metrics',
		'$Project!/environment/$Environment/pipeline/$Identifier//$Action/$ID/$OtherID' => 'pipeline',
		'$Project!/environment/$Environment/deploy_summary' => 'deploySummary',
		'$Project!/environment/$Environment/git_revisions' => 'gitRevisions',
		'$Project!/environment/$Environment/start-deploy' => 'startDeploy',
		'$Project!/environment/$Environment/deploy/$Identifier/log' => 'deploylog',
		'$Project!/environment/$Environment/deploy/$Identifier' => 'deploy',
		'$Project!/transfer/$Identifier/log' => 'transferlog',
		'$Project!/transfer/$Identifier' => 'transfer',
		'$Project!/environment/$Environment' => 'environment',
		'$Project!/createenv/$Identifier/log' => 'createenvlog',
		'$Project!/createenv/$Identifier' => 'createenv',
		'$Project!/CreateEnvironmentForm' => 'getCreateEnvironmentForm',
		'$Project!/branch' => 'branch',
		'$Project!/build/$Build' => 'build',
		'$Project!/restoresnapshot/$DataArchiveID' => 'restoresnapshot',
		'$Project!/deletesnapshot/$DataArchiveID' => 'deletesnapshot',
		'$Project!/movesnapshot/$DataArchiveID' => 'movesnapshot',
		'$Project!/update' => 'update',
		'$Project!/snapshots' => 'snapshots',
		'$Project!/createsnapshot' => 'createsnapshot',
		'$Project!/uploadsnapshot' => 'uploadsnapshot',
		'$Project!/snapshotslog' => 'snapshotslog',
		'$Project!/postsnapshotsuccess/$DataArchiveID' => 'postsnapshotsuccess',
		'$Project!/star' => 'toggleprojectstar',
		'$Project!' => 'project',
	];

//	public function handleRequest(SS_HTTPRequest $request, DataModel $dataModel = null) {
//		var_dump($request); exit;
//	}

	public function index(SS_HTTPRequest $request) {
		return $this->renderWith(["DNRoot_projects", "DNRoot"]);
	}

}

