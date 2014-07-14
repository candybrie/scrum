<?php

class TasksController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session');

	public function index() {
		$this->set('tasks', $this->Task->find('all'));
	}
	
	public function edit($id = null) {
		_checkId($id);
		
		if($this->request->is('post') || $this->request->is('put')) {
			if($this->Task->save($this->request->data)) {
				$this->Session->setFlash(__('The task has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The task could not be saved. Please try again.'));
		}
		
		if(!$this->request->data) {
			$this->request->data = $post;
		}
	}
	
	public function delete($id = null) {
		$this->request->onlyAllow('post');
		
		_checkId($id);
		$this->Task->id = $id;
		
		if($this->Task->delete())
			$this->Session->setFlash(__('Task deleted'));
		else
			$this->Session->setFlash(__('Task was not deleted'));
			
		return $this->redirect(array('action'=>'index'));
	}
	
	private function _checkId($id) {
		if(!id) {
			throw new NotFoundException(__('Invalid task.'));
		}
		
		$task = $this->Task->findById($id);
		if(!$task) {
			throw new NotFoundException(__('Invalid task'));
		}
	}

}