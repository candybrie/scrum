<?php 

class UsersController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout');
	}
	
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
	
	public function view($id = null) {
		$this->User->id = $id;
		if(!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}
	
	public function add() {
		if($this->request->is('post')) {
			$this->User->create();
			if($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action'=>'index'));
			}
			$this->Session->setFlash(__('The user could not be saved. Please try again.'));
		}
	}
	
	public function edit($id = null) {
		$this->User->id = $id;
		if(!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if($this->request->is('post') || $this->request->is('put')) {
			if($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user could not be saved. Please try again.'));
		}
		else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
	}
	
	public function delete($id = null) {
		$this->request->onlyAllow('post');
		
		$this->User->id = $id;
		if(!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		if($this->User->delete())
			$this->Session->setFlash(__('User deleted'));
		else
			$this->Session->setFlash(__('User was not deleted'));
			
		return $this->redirect(array('action'=>'index'));
	}
	
	public function login() {
		if($this->request->is('post')){
			if($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			}
			
			$this->Session->setFlash(__('Invalid username or password.'));
		}
	}
	
	public function logout() {
		return $this->redirect($this-Auth->logout());
	}
	
	public function dashboard() {
		//make sure user is logged in
		//populate data: tasks, forum discussions, timeline, burndown chart
	}
	
	public function isAuthorized($user) {
		
		//Everyone can see their dashboard
		if($this->action === 'dashboard') {
			return true;
		}
		//Scrum can add users
		elseif($this->action === 'add') {
			if(isset($user['role']) && $user['role'] === 'scrum') {
				return true;
			}
		}
		//Users can edit/delete themselves
		elseif(in_array($this->action, array('edit', 'delete'))) {
			$userId = (int) $this->request->params['pass'][0];
			
			if($userId = $user['id'])) {
				return true;
			}
		}
		
		return parent::isAuthorized($user);
	}

}