<?php 

require_once 'core/init.php';
require_once 'includes/header.php'; 

$db = DB::getInstance();

$db->query('SELECT * FROM `task` WHERE deleted = 0');
$tasks = $db->results();

if(isset($_POST['delete'])){
$id = $_GET['id'] ;
	$db->query("UPDATE `task` SET deleted = 1 WHERE id ='".$id."'");
	Redirect::To('task.php');
}

if(!empty($_POST)){
			$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'taskName' => array(
            'required' => true,
            'min' => 2,
            'max' => 50
        ),
        'taskDate' => array(
          'required' => true
        ),
        'taskTime' => array(
          'required' => true
        )
	 ));

 if($validation->passed()) {

		try {					
			$db->insert('task', array(
          'taskName' => Input::get('taskName'),
          'taskDate' => Input::get('taskDate'),
          'taskTime' => Input::get('taskTime')
      ));


		} catch (Exception $e) {
			die($e->getMessage());
			}
		} else {
		foreach($validation->errors() as $error) {
			echo $error, '<br>';
          }
      }
      Redirect::To('task.php');
	} 



?>

<script>sortBy();</script>
<h1>Tasks</h1>



<div class="row">
	<div class="col-md-6 col-md-offset-3">
<form method="post" action="" class="form-inline form-group">
<div class="form-group">
	<label>Task Name</label>
	<input type="text" name="taskName" class="form-control" placeholder="Enter new Task">
</div>
<div class="form-group">
	<label>Date</label>
	<input type="date" name="taskDate" class="form-control" value="<?php escape(print(date("Y-m-d"))); ?>">
</div>
<div class="form-group">
	<label>Time</label>
	<input type="time" name="taskTime" class="form-control" value="<?php escape(print(date("h:i"))); ?>">
</div>
	<input type="submit" name="inserttask">
</form>
		<table class="table">
			<thead>
				<tr>
					<th>Task</th>
					<th>Time</th>
					<th>Date</th>
					<th>Button</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($tasks as $task): ?>
				<tr>
					<td><?php echo $task->taskName; ?></td>
					<td><?php escape(print(date("h:i", strtotime($task->taskTime)))); ?></td>
					<td><?php escape(print(date("F-D-d", strtotime($task->taskDate)))); ?></td>
					<!-- <td>Done<input type="checkbox" name="doneTask" id="doneTask" rel="<?php echo $task->id ;?>"></td> -->
					<td>
						<form action="task.php?id=<?php echo $task->id ;?>" method="post">
							<input class="btn btn-danger" type="submit" name="delete" value="X">
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php require_once 'includes/footer.php';  ?>