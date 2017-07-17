<?php require_once 'includes/header.php';
require_once 'core/init.php';
$db = DB::getInstance();

$key = md5('aviisgreat');
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

$db->query("SELECT * FROM categories WHERE user_id = '".$user_id."' AND deleted = 0");
$status = $db->results();


 ?>


<div class="row">	
<div class="col-md-12 text-center">
<h1><?php echo ucwords(decrypt($user->data()->username, $key)); ?>'s Budget</h1>
</div>
<div class="form-inline col-md-6 col-xs-6" > 
<div id="messageCat"></div>
	<h2>New category</h2>
	<form action="insert.php" method="post" id="categoryInsert" class="form-inline">
	<div class="form-group">
		<label for=categoryName>Category Name</label>
		<input type="text" name="categoryName" class="form-control">
	</div>
		<div class="form-group">
			<select name="catStatus" class="form-control">
					<option value="1">Income</option>
					<option value="2">Outgoing</option>
			</select>
		</div>
		<input type="hidden" name="categoryinsert" value="1">
		<input type="hidden" name="user_id" value="<?php echo $user_id ?>">
		<input type="submit" name="categoryinsert" class="btn btn-info">
	</form>
</div>
<div class="col-md-3 col-xs-3 form-inline" style="margin-left:-87px;" id="deletecategoryfile">


	
</div>
<div class="col-md-3 col-xs-3" id="selectMonthFile">

	</div>
	
</div>

<div id="mainContent">

</div>










<?php require_once 'includes/footer.php'; ?>