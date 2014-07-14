<?php

class ChartsController extends AppController {
	public uses = array('Backlog', 'Tasks', 'Logs', 'Users');
	
	protected function _hoursComplete($backlog, $start_date = null, $end_date = null) {
		//check if a backlog is correct
		
		//get all tasks with that backlog
		
		//get all logs with that task's time
		
		//total values
	}
	
	protected function _hoursEstimated($backlog) {
		//check if a backlog is correct
		
		//get all tasks with that backlog
		
		//total time estimates
	}

}