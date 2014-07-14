<?php

App::uses('AppModel', 'Model');

class Task extends AppModel{
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Backlog'
	);
	
	public $hasMany = 'Log';
	
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
	
	public $actsAs = array('Containable');
	
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
	 * Check if there is a particular user is assigned to a particular task
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
	
	/**
	 * Gets the hours completed in a particular backlog 
	 *
	 * @param	int		$backlog	id of backlog
	 * @param	String	$start		(Optional) The beginning point (Y-m-d) inclusive
	 * @param	String	$end		(Optional) The end point (Y-m-d) exclusive
	 * @return	float				The total time (in hours) the backlog has been worked on
	 **/
	public function hoursComplete($backlog, $start = null, $end = null) {
		//get all tasks with that backlog
		$tasks = $this->find('list', array( 'conditions' => array('backlog_id' => $backlog)));
		
		$totalTime = 0;
		foreach ($tasks as $id => $task) {
			//get all logs with that task's time
			$time = $this->Log->hoursComplete($id, '', $start, $end);
			
			//total values
			$totalTime += $time;
		}
		
		return $totalTime;
	}
	
	/**
	 * The sum of the estimated time of all tasks in a particular backlog
	 *
	 * @param	int		$backlog	id of backlog
	 * @return	float				total hours estimated in backlog
	 **/
	public function hoursEstimated($backlog) {
		//get all tasks with that backlog
		$tasks = $this->find('list', array( 
					'conditions' => array('backlog_id' => $backlog),
					'fields' => array('Task.estimated_time')
				)
			);
		
		//total time estimates
		foreach ($tasks as $task) {
			$totalTime += $task; 
		}
		
		return $totalTime;
	}

}