<?php

class LogsController extends AppController {
	public $helpers = array('Html', 'Form', 'Time');
	public $components = array('Session');
	
	public function index() {
		$this->set('logs', $this->Log->find('all'));
	}
	
	public function add($taskId) {
		if($this->request->is('post')) {
			$this->request->data['Log']['user_id'] = $this->Auth->user('id');
			$this->request->data['Log']['task_id'] = $taskId;
			
			if($this->Log->save($this->request->data)) {
				$this->Session->setFlash(__('Your post has been saved.'));
				return $this->redirect(array('action' => 'index'));
			}
		}
	}
	
	public function edit(id=null){
		_checkId($id);
		
		if($this->request->is('post') || $this->request->is('put')) {
			if($this->Log->save($this->request->data)) {
				$this->Session->setFlash(__('The log has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The log could not be saved. Please try again.'));
		}
		
		if(!$this->request->data) {
			$this->request->data = $post;
		}
	}
	
	public function delete($id = null) {
		$this->request->onlyAllow('post');
		
		$this->Log->id = $id;
		if(!$this->Log->exists()) {
			throw new NotFoundException(__('Invalid log'));
		}
		
		if($this->Log->delete())
			$this->Session->setFlash(__('Log deleted'));
		else
			$this->Session->setFlash(__('Log was not deleted'));
			
		return $this->redirect(array('action'=>'index'));
	}
	
	public function isAuthorized($user) {
		App::uses('CakeTime', 'Utility');
		
		if($this->action === 'add') {
			//scrum can add log to any task
			if(isset($user['role']) && $user['role'] === 'scrum') {
				return true;
			}
			//team can only add to tasks they're assigned
			else{
				$taskId = (int) $this->request->params['pass'][0];
				
				//check if user owns this task
				if($this->Log->Task->isAssignedUser($task, $user)){
					return true;
				}
			}
		}
		elseif(in_array($this->action, array('edit', 'delete'))) {
			$logId = (int) $this->request->params['pass'][0];
			$logCreated = $this->Log->field('created', array('Log.id' => $logId));
			
			//Users can edit/delete own logs within 30 min of making them
			if($this->Log->isOwnedBy($logId, $user['id']) 
				&& CakeTime::wasWithinLast('30 minutes', $logCreated)) {
				return true;
			}
			
		}
		
		return parent::isAuthorized($user);
	}
	
	/**
	 * Determines if id given points to a actual item.
	 * Throws NotFoundException if it doesn't.
	 * 
	 * @param	int	id	The id to check against records
	 **/
	private function _checkId($id) {
		if(!id) {
			throw new NotFoundException(__('Invalid log.'));
		}
		
		$log = $this->Log->findById($id);
		if(!$log) {
			throw new NotFoundException(__('Invalid log'));
		}
	}
	
	
}