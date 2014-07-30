<h1>Users List</h1>
<table>
	<tr>
		<th>ID</th>
        <th>username</th>
	</tr>
	
	<?php foreach ($users as $user): ?>
	
	<tr>
		<td><?php echo $user['User']['id']; ?></td>
		<td><?php echo $user['User']['username']; ?></td>
	</tr>
	
	<?php endforeach; ?>
	<?php unset($user); ?>
</table>