<?php

class BacklogsController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session');
	
	public function index() {
		$this->set('backlogs', $this->Backlog->find('all'));
	}
	
	public function view($id = null) {
		if(!$id) {
			//get current sprint id
			
			//if no current sprint, get current backlog
		}
		
		$backlog = $this->Backlog->findById($id);
		if(!$backlog) {
			throw new NotFoundException(__('Invalid Post'));
		}
		
		$this->set('backlog', $backlog);
		
		//can we sort associated tasks by their status?
	}
	
	public function add() {
		if($this->request->is('post')) {
			$this->Backlog->create();
			
			if($this->Backlog->save($this->request->data)) {
				$this->Session->setFlash(__('Your post has been saved.'));
				return $this->redirect(array('action' => 'addTasks', $this->Backlog->id));
			}
			
			$this->Session->setFlash(__('Unable to add your post.'))
		}
	}
	
	public function edit($id = null) {
		_checkId($id);
		$this->Backlog->id = $id;
		
		if($this->request->is('post') || $this->request->is('put')) {
			if($this->Backlog->save($this->request->data)) {
				$this->Session->setFlash(__('The backlog has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The backlog could not be saved. Please try again.'));
		}
		
		if(!$this->request->data) {
			$this->request->data = $post;
		}
	}
	
	public function addTasks($id = null) {
		_checkId($id);
		$this->Backlog->id = $id;
		
		//logic for adding tasks to backlog
		
		//logic to load tasks if no request
	}
	
	public function delete($id = null) {
		$this->request->onlyAllow('post');
		
		_checkId($id);
		$this->Backlog->id = $id;
		
		if($this->Backlog->delete())
			$this->Session->setFlash(__('Backlog deleted'));
		else
			$this->Session->setFlash(__('Backlog was not deleted'));
			
		return $this->redirect(array('action'=>'index'));
	}
	
	public function burndownList() {
		//get all items to make a list of charts to view
	}
	
	public function burndown(id = null) {
		_checkId($id);
		$this->Backlog->id = $id;
		
		//display burndown chart for this backlog
	}
	
	private function _checkId($id) {
		if(!id) {
			throw new NotFoundException(__('Invalid backlog.'));
		}
		
		$backlog = $this->Backlog->findById($id);
		if(!$backlog) {
			throw new NotFoundException(__('Invalid backlog'));
		}
	}
}