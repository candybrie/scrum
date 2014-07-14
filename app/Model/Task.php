<?php

App::uses('AppModel', 'Model');

class Task extends AppModel{
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Backlog' => array(
			'className' => 'Backlog',
			'foreignKey' => 'backlog_id'
		)
	);
	
	public $hasMany = array(
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'task_id'
		)
	);
	
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'tasks_users',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'user_id'
		),
		'Dependent' => array(
			'className' => 'Task',
			'joinTable' => 'dependents_tasks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'dependent_id'
		),
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
				'message' => 'This title must be between 1 and 40 characters.'
			)
		),
		'description' = array(
			'description1'=>array(
				'rule' => 'alphaNumeric',
				'message' => 'Please only use alphaNumeric characters.'
			)
		),
		'priority' => array(
			'priority1' => array(
				'rule' => 'naturalNumber',
				'required' => true,
				'message' => 'Invalid priority.',
				'allowEmpty' => false
			)
			'priority2' => array(
				'rule' => array('comparison', '<=', 5),
				'message' => 'Invalid priority.'
			)
		),
		'estimated_time' => array(
			'time1' => array(
				'rule' => 'numeric',
				'message' => 'Please indicate the amount of time you think this task will take (in hours).'
			)
		)
	);

	/**
	 * Check if there a particular user is assigned to a particular task
	 *
	 * @param	int	$task	id of task
	 * @param	int	$user	id of user
	 * @return	bool		True if relationship exists, false if not ints or relationship doesn't exist
	 **/
	public function isAssignedUser($task, $user) {
		if(is_int($task) && is_int($user))
			$results = $this->query("SELECT id FROM tasks_users WHERE task_id = ? AND user_id = ?", array($task, $user));
		
		if(empty($results))
			return false;
		else
			return true;
	}
	
	public function hoursComplete($backlog, $start_date = null, $end_date = null) {
		//check if a backlog is correct
		
		//get all tasks with that backlog
		
		//get all logs with that task's time
		
		//total values
	}
	
	public function hoursEstimated($backlog) {
		//check if a backlog is correct
		
		//get all tasks with that backlog
		
		//total time estimates
	}

}