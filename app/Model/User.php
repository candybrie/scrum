<?php

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth')

class User extends AppModel{
	public $displayField = 'username';

	public $hasMany = array(
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'user_id'
		),
		'Tasks' => array(
			'className' => 'Task',
			'foreignKey' => 'user_id'
		)
	);
	
	public $hasAndBelongsToMany = array(
		'Task' => array(
			'className' => 'Task',
			'joinTable' => 'tasks_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'task_id'
		)
	);

	public $validate = array(
		'username' => array(
			'user1' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'message' => 'A username is required.'
			),
			'user2' => array(
				'rule' => array('between', 2, 30),
				'message' => 'Usernames must be between 2 and 30 characters.'
			)
			'user3' => array(
				'rule' => 'isUnique',
				'message' => 'That username has already been taken.'
			)
		),
		'password' => array(
			'pass1' => array(
				'rule' => array('notEmpty'),
				'message' => 'A password is required.'
			),
			'pass2' => array(
				'rule' => array('between', 6, 35),
				'message' => 'Passwords must be 6 and 35 characters long.'
			)
		),
		'email' => array(
			'email1' => array(
				'rule' => array('email', true),
				'allowEmpty' => false,
				'message' => 'Please enter a valid email.'
			),
			'email2' => array(
				'rule' => 'isUnique',
				'message' => 'That email is already in use.'
			)
		),
		'name' => array(
			'name1' => array(
				'rule' => 'alphaNumeric',
				'allowEmpty' => true,
				'message' => 'Please use alphaNumeric characters.'
			)
		)
		'role' => array(
			'valid' => array(
				'rule' => array('inList', array('admin', 'scrum', 'team', 'noob')),
				'message' => 'Please enter a valid role.',
				'allowEmpty' => false
			)
		)
	);
	
	public function beforeSave($options = array()) {
		if(isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new SimplePasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
					$this->data[$this->alias]['password']
				);
		}
		
		return true;
	}
}