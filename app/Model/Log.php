<?php

App::uses('AppModel', 'Model');

class Log extends AppModel{
	public $belongsTo = array( 'User', 'Task');
	
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
	
	/**
	 * Determines the number of hours logged by a certain user
	 * with an optional requirement of on a certain task and date range
	 *
	 * @param	int		$user		The id of the user
	 * @param	int		$task		(Optional) The id of the task
	 * @param	String	$start_date	(Optional) The beginning point (YY-MM-DD), if left out get earliest
	 * @param	String	$end_date	(Optional) The end point (YY-MM-DD), though if you want all entries for the 4th you'd put the 5th, if left out get latest.
	 * @return	float				Number of hours worked
	 **/
	public function hoursWorked($user, $task = null, $start_date = null, $end_date = null) {
		$cond = array('user_id' => $user);
		
		if(!empty($task))		//check if task is present
			$cond['task_id'] = $task;
		
		if(!empty($start_date))	//check if start date is present
			$cond['created >='] = $start_date;
		
		if(!empty($end_date))	//check if end date is present
			$cond['created <'] = $end_date;
		
		//get all logs with that user (and task) (in that range)
		$logs = $this->find('list', array( 'conditions' => $cond, 'fields' => array('Log.worked')));
		
		//total time worked by user (on task) (in that range)
		$total = 0;
		foreach ($logs as $log)
			$total += $log;
	}
	
	/**
	 * Determines the number of hours logged on a certain task
	 * with an optional requirement of by a certain user and date range
	 *
	 * @param	int		$task		The id of the task
	 * @param	int		$user		(Optional) The id of the user
	 * @param	String	$start_date	(Optional) The beginning point (YY-MM-DD), if left out get earliest
	 * @param	String	$end_date	(Optional) The end point (YY-MM-DD), if left out get latest
	 * @return	float				Number of hours worked
	 **/
	public function hoursComplete($task, $user = null, $start_date = null, $end_date = null) {
		$cond = array('task_id' => $task);
		
		if(!empty($user))		//check if user is present
			$cond['user_id'] = $user;
		
		if(!empty($start_date))	//check if start date is present
			$cond['created >='] = $start_date;
		
		if(!empty($end_date))	//check if end date is present
			$cond['created <'] = $end_date;
		
		//get all logs with that task (and user) (in that range)
		$logs = $this->find('list', array( 'conditions' => $cond, 'fields' => array('Log.worked')));
		
		//total time worked by task (on user) (in that range)
		$total = 0;
		foreach ($logs as $log)
			$total += $log;
	}
	
}