<?php

class ChartsController extends AppController {
	public uses = array('Backlog', 'Tasks', 'Logs', 'Users');
	
	/**
	 * Gets the cumulative time worked for each day from start to end
	 *
	 * @param	int		$backlog	the id of the backlog that the tasks are in
	 * @param	String	$start		the beginning date (Y-m-d) inclusive
	 * @param	String	$end		the ending date (Y-m-d) exclusive
	 * @return	array				an array with the form (string) $end_date => (float) $cumulative_time,
	 **/
	protected function _timeWorked($backlog, $start, $end) {
		//create date objects (easier for comparison and decrement)
		$start_date = strtotime($start);
		$end_date = strtotime($end);
		
		//decrement end_date until start_date = end_date
		while($start_date < $end_date) {
			//get correct string representation
			$end = $end_date->format('Y-m-d');
			
			//get time worked on that task up til that day
			$worked[$end] = $this->Task->hoursComplete($backlog, $start, $end);
			
			//decrement the date
			$end_date->modify('-1 day');
		}
		
		return $worked;
	}
	
	/**
	 * Gets the estimated time remaining for each day from start to end
	 *
	 * @param	int		$backlog	the id of the backlog that the tasks are in
	 * @param	String	$start		the beginning date (Y-m-d) inclusive
	 * @param	String	$end		the ending date (Y-m-d) exclusive
	 * @return	array				an array with the form (string) $end_date => (float) $remaining_time,
	 **/
	protected function _timeRemaining($backlog, $start, $end) {
		//check backlog exists
		_checkId($backlog, 'Backlog');
	
		//get time worked and total time estimated
		$worked = $this->_timeWorkedSeries($backlog, $start, $end);
		$estimated = $this->Task->hoursEstimated($backlog);
		
		foreach($worked as $day => $time){
			$remaining[$day] = $estimated - $time;
		}
		
		return $remaining;
	}
	
	/**
	 * Determines if id given points to a actual item.
	 * Throws NotFoundException if it doesn't.
	 * 
	 * @param	int	id	The id to check against records
	 * @throws	NotFoundException	if the id does not point to an actual item
	 **/
	protected function _checkId($id, $class) {
		if(!$id) {
			throw new NotFoundException(__('Invalid '.$class.'.'));
		}
		
		$item = $this->$class->findById($id);		//may not work
		if(!$item) {
			throw new NotFoundException(__('Invalid '.$class.'.'));
		}
	}

}