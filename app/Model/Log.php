<?php

App::uses('AppModel', 'Model');

class Log extends AppModel{
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'task_id'
		)
	);
	
	public $validate = array(
		'title' => array(
			'title1' => array(
				'rule' => 'alphaNumeric',
				'required' => 'create',
				'message' => 'Please only use alphaNumeric characters.',
				'allowEmpty' => false
			),
			'title2' => array(
				'rule' => array('between', 1, 40),
				'message' => 'This description must be between 2 and 40 characters.'
			)
		),
		'description' = array(
			'description1'=>array(
				'rule' => 'alphaNumeric',
				'message' => 'Please only use alphaNumeric characters.'
			)
		),
		'status' => array(
			'status1' => array(
				'rule' => array('inList', array('pre', 'progress', 'complete', 'limbo')),
				'message' => 'Please enter a valid status.',
				'allowEmpty' => false
			)
		),
		'worked' => array(
			'work1' => array(
				'rule' => 'numeric',
				'message' => 'Please indicate the amount of time worked (in hours).'
			)
		)
	);
	
	/**
	 * Used to check if a particular log is associated with a particular user
	 *
	 * @param	int	$log	The id of the log
	 * @param	int	$user	The id of the user
	 * @return	bool		True if the user owns the log, false otherwise
	 **/
	public function isOwnedBy($log, $user) {
		return $this->field('id', array('id' => $log, 'user_id' => $user)) !== false;
	}
	
	/**
	 * Used to check if a particular log is associated with a particular task
	 *
	 * @param	int	$log	The id of the log
	 * @param	int	$task	The id of the task
	 * @return	bool		True if the log is for that task, false otherwise
	 **/
	public function isALogOf($log, $task){
		return $this->field('id', array('id' => $log, 'task_id' => $task)) !== false;
	}
}