<?php require_once 'includes/header.php';
require_once 'core/init.php';

$db = DB::getInstance();

$db->query("SELECT * FROM `workout`");
$workouts = $db->results();

$db->query("SELECT * FROM `workout-title`");
$workoutTitle = $db->results();

$db->query("SELECT workout.id ,reps, `time`, level, workoutId, newWorkout FROM `workout-list`		
			INNER JOIN
			`workout`
			WHERE workout.id = workoutId");
$workoutList = $db->results();



?>

<h2>Set a workout list</h2>


<div class="row">
<div class="col-md-10">
		<form action="" class="form-group form-inline">
			<div class="form-group">
				<label for="workout">New Program</label>
				<input type="text" name="workout" class="form-control">
			</div>
			<div class="form-group">
				<label for="workout">Day</label>
				<input type="text" name="workout" class="form-control">
			</div>
			<div class="form-group">
				<label for="workout">Muscle group</label>
				<input type="text" name="workout" class="form-control">
			</div>
			<input type="submit" class="btn">
		</form>
	</div>
	
	</div>
	<div class="row">
	<div class="col-md-3">
		<form action="" class="form-group">

			<div class="form-group">
				<label for="workout">Add to workout list</label>
				<input type="text" name="workout" class="form-control">
			</div>
			<input type="submit" class="btn">
		</form>
	</div>
	</div>
	<div class="row">
	<p>have 2 options if superset mutilple rows if exercise only 1 row</p>
	<div class="col-md-8 col-md-offset-2" >
	<form action="" class="form-group form-inline">
	<div class="form-group">
	<label for="">excercise</label>
		<input type="radio" value="excercise" name="type">
		<label for="">superset</label>
		<input type="radio" value="superset" name="type">
		<label for="">muliptle-excersice</label>
		<input type="radio" value="muliptle-excersice" name="type">
	<label for="">Workout</label>
		<select name="" id="" class="form-control">
	<?php foreach ($workouts as $workout) : ?>
			<option value="<?php echo $workout->id ?>"><?php echo $workout->newWorkout; ?></option>
	<?php  endforeach; ?>
		</select>
		</div>
		<div class="form-group">
		<label for="">Reps</label>
			<select name="" id="" class="form-control">
				<option value="">5</option>
				<option value="">10</option>
				<option value="">15</option>
				<option value="">20</option>
			</select>
		</div>
		<div class="form-group">
		<label for="">Time</label>
			<input type="text">
		</div>
				<div class="form-group">
		<label for="">level</label>
			<select name="" id="" class="form-control">
				<option value="">1</option>
				<option value="">2</option>
				<option value="">3</option>
				<option value="">4</option>
			</select>
		</div>
		<div>
		<label for="">Superset</label>
		<input type="checkbox">
		</div>
		<input type="submit" class="btn">
		</form>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3 col-md-offset-3">
		<ul class="list-group" style="list-style: none;">
		<li><h4>Superset: smith machine <strong>x 4</strong><small>(15 sec break)</small></h4></li>
			<li class="list-group-item">10 split squats (barbell underneath legs) <span style="text-align: right;">weight 15kg</span></li>
			<li class="list-group-item">8x close stance squats</li>
		</ul>
		<small>wait 10 seconds</small>
		<ul class="list-group" style="list-style: none;">
		<li><h4>Superset: <strong>x 4</strong></h4></li>
			<li class="list-group-item">8x curtsy lunges on each leg</li>
			<li class="list-group-item">10 good mornings</li>
		</ul>
		<ul class="list-group" style="list-style: none;">
		<li><h4>Superset:Leg extension machine</h4></li>
			<li class="list-group-item">10 toes up then decrease weight 10-20 lbs</li>
			<li class="list-group-item">10 toes pointed out then decrease weight 10-20 lbs</li>
			<li class="list-group-item">10 toes pointed in</li>
		</ul>
		<ul class="list-group" style="list-style: none;">
			<li><h4>Exercise <strong>x 4</strong></h4></li>
			<li  class="list-group-item">15 x sissy squats </li>
		</ul>
		<ul class="list-group" style="list-style: none;">
		<li><h4>Booty Complex: USE BAND<strong>x 3</strong></h4></li>
			<li class="list-group-item">10 weighted hip ups (platform optional)</li>
			<li class="list-group-item">10 right leg hip ups</li>
			<li class="list-group-item">10 left leg hip ups</li>
			<li class="list-group-item">15 unweighted hip ups </li>
		</ul>
	</div>
</div>








<?php require_once 'includes/footer.php'; ?>