<h1> Add Backlog </h1>
<?php

echo $this->Form->create();
//title box
echo $this->Form->input('title');
//type drop down
echo $this->Form->input('backlog_type', 
            array(
			'options' => array(
				'wish' => 'Wishlist', 
				'product' => 'Product Backlog', 
				'sprint' => 'Sprint Backlog'
			),
			'empty' => '(select one)'
		)
	);
//start date picker
echo $this->Form->input('start_date');
//end date picker
echo $this->Form->input('end_date');
echo $this->Form->end('Save');
