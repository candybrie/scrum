<?php

App::uses('AppModel', 'Model');

class Backlog extends AppModel{
	public $belongsTo = array(
		'Parent' => array(
			'className' => 'Backlog',
			'foreignKey' => 'parent_id'
		)
	);
	
	public $hasMany = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'backlog_id'
		),
		'Child' => array(
			'className' => 'Backlog'
			'foreignKey' => 'parent_id'
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
				'message' => 'This title must be between 1 and 40 characters.'
			)
		),
		'start_date' => array(			//use jQuery UI to restrict date stuff
			'start1' => array (
				'rule' => array('datetime', 'mdy'),
				'message' => 'Please enter a valid date (MM-DD-YY)'
			)
		),
		'end_date' => array(				//use jQuery UI to restrict date stuff
			'end1' => array (
				'rule' => array('datetime', 'mdy'),
				'message' => 'Please enter a valid date (MM-DD-YY)'
			)
		),
		'backlog_type' => array(
			'type1' => array(
				'rule' => array('inList', array('wish', 'product', 'sprint')),
				'message' => 'Please enter a valid type.',
				'allowEmpty' => false
			)
		),
	);
	
}