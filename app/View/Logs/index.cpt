
<h1>Logs List</h1>
<table>
	<tr>
		<th>ID</th>
		<th>Date</th>
		<th>Title</th>
		<th>User</th>
	</tr>
	
	<?php foreach ($logs as $log): ?>
	
	<tr>
		<td><?php echo $log['Log']['id']; ?></td>
		<td><?php echo $log['Log']['created']; ?></td>
		<td>
			<?php echo $this->Html->link($log['Log']['title'],
				array('controller' => 'logs', 'action' => 'view', $log['Log']['id'])); ?>
		</td>
		<td><?php echo $log['User']['username']; ?></td>
	</tr>
	
	<?php endforeach; ?>
	<?php unset($log); ?>
</table>