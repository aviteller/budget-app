<?php
require_once 'core/init.php';
$db = DB::getInstance();

$user = new User;

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}



$user_id = $user->data()->id;

$db->query("SELECT date FROM `budget` WHERE userId = '".$user_id."' AND deleted = 0");
$dates = $db->results();

$all_dates = array();

foreach ($dates as $date) {
	$dateObj = new DateTime($date->date);
	$this_date = $dateObj->format('F Y');

	if (in_array_r($this_date, $all_dates)) {continue;}

	$all_dates[]['date'] =  $this_date;
}
usort($all_dates, "sortArrayByDates");

?>



<div class="form-inline form-group" id="selectedMonth">
<h2>Select Month</h2>
<?php if(isset($_GET['selectMonth'])): ?>

	<?php 
	 $selectedMonthY = $_GET['selectMonth'];
	 ?>

		<select id="selectMonth" class="form-control">
				<option value="0" disabled="true">Select a Month</option>
	<?php foreach ($all_dates as $month) : ?>
										<?php $dateO = new DateTime($month['date']);	

										 ?>
				<option value="<?php echo date_format($dateO, 'Y-m'); ?>" <?php if ($selectedMonthY == date_format($dateO, 'Y-m')) {echo 'selected';}  ?>><?php echo ucfirst($month['date']) ; ?></option>
			<?php endforeach ?>
				<option value="1">All dates</option>
	    	</select>
		<?php else : ?>
<?php $thisMonthY = strtolower(date('Y-m')); ?>

			<select id="selectMonth" class="form-control">
				<option value="0" disabled="true">Select a Month</option>
	<?php foreach ($all_dates as $month) : ?>
										<?php $dateO = new DateTime($month['date']);	

										 ?>
				<option value="<?php echo date_format($dateO, 'Y-m'); ?>" <?php if ($thisMonthY == date_format($dateO, 'Y-m')) {echo 'selected';}  ?>><?php echo ucfirst($month['date']) ; ?></option>
			<?php endforeach ?>
				<option value="1">All dates</option>
	    	</select>
	    	<?php endif; ?>
	</div>