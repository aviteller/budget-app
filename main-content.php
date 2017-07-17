<?php 

require_once 'core/init.php';
 $user = new User();

$db = DB::getInstance();

$key = md5('aviisgreat');

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


/*echo '<pre>' . var_export($all_dates, true) . '</pre>';*/

$db->query("SELECT * FROM categories WHERE status = 1 AND user_id = '".$user_id."' AND deleted = 0");
$statusIncome = $db->results();


$db->query("SELECT * FROM categories WHERE status = 2 AND user_id = '".$user_id."' AND deleted = 0");
$statusOutgoing = $db->results();

if(isset($_GET['selectMonth'])){

$date4 = $_GET['selectMonth'];

if(!empty($date4) && $date4 != 1){
	$date1 = new DateTime($date4);
	$date1->sub(new DateInterval('P1M'));
	$date2 = $date1->format('Y-m');

$db->query("SELECT sum(price) AS total FROM `budget` WHERE status = 1 AND userId = '".$user_id."' AND `date` LIKE '%{$date2}%' AND deleted = 0 ");
$totalIncomePrev = $db->first()->total;

$db->query("SELECT sum(price) AS total FROM `budget` WHERE status = 2 AND userId = '".$user_id."' AND `date` LIKE '%{$date2}%' AND deleted = 0");
$totalOutgoingPrev = $db->first()->total;

$prevTotalNet = $totalIncomePrev - $totalOutgoingPrev;

	}



$db->query("SELECT budget.id ,itemName, `date`, price, categoryName, categoryId FROM `budget`		
			INNER JOIN
			`categories`
			WHERE categories.id = categoryId AND budget.status = 1 AND userId = '".$user_id."' AND `date` LIKE '%{$_GET['selectMonth']}%' AND budget.deleted = 0");
$incomes = $db->results();

$db->query("SELECT budget.id ,itemName, `date`, price, categoryName, categoryId FROM `budget`		
			INNER JOIN
			`categories`
			WHERE categories.id = categoryId AND budget.status = 2 AND userId = '".$user_id."' AND `date` LIKE '%{$_GET['selectMonth']}%' AND budget.deleted = 0");
$outgoings = $db->results();

$db->query("SELECT sum(price) AS total FROM `budget` WHERE status = 1 AND userId = '".$user_id."' AND `date` LIKE '%{$_GET['selectMonth']}%' AND budget.deleted = 0");
$totalIncome = $db->first()->total;

$db->query("SELECT sum(price) AS total FROM `budget` WHERE status = 2 AND userId = '".$user_id."' AND `date` LIKE '%{$_GET['selectMonth']}%' AND budget.deleted = 0");
$totalOutgoing = $db->first()->total;


} elseif (!isset($_GET['selectMonth']) || $_GET['selectMonth'] == 1) {
	$db->query("SELECT budget.id ,itemName, `date`, price, categoryName, categoryId FROM `budget`		
			INNER JOIN
			`categories`
			WHERE categories.id = categoryId AND budget.status = 1 AND userId = '".$user_id."' AND budget.deleted = 0");
$incomes = $db->results();
$db->query("SELECT budget.id ,itemName, `date`, price, categoryName, categoryId FROM `budget`		
			INNER JOIN
			`categories`
			WHERE categories.id = categoryId AND budget.status = 2 AND userId = '".$user_id."' AND budget.deleted = 0");
$outgoings = $db->results();

$db->query("SELECT sum(price) AS total FROM `budget` WHERE status = 1 AND userId = '".$user_id."' AND budget.deleted = 0");
$totalIncome = $db->first()->total;

$db->query("SELECT sum(price) AS total FROM `budget` WHERE status = 2 AND userId = '".$user_id."' AND budget.deleted = 0");
$totalOutgoing = $db->first()->total;


}






$sums_array_in = array();

foreach ($incomes as $key => $value) {
$idstatus = $value->categoryId;

$db->query("SELECT sum(price) AS total
			FROM `budget` WHERE categoryId = $idstatus AND status = 1 AND userId = '".$user_id."' AND `date` LIKE '%{$_GET['selectMonth']}%'
			 AND budget.deleted = 0 ");
//echo '<pre>' . var_dump($value) . '</pre>';
$sums_array_in[$value->categoryName] = $db->first()->total;

}

$sums_array_out = array();

foreach ($outgoings as $key => $value) {
$idstatus = $value->categoryId;

$db->query("SELECT sum(price) AS total
			FROM `budget` WHERE categoryId = $idstatus AND status = 2 AND userId = '".$user_id."' AND `date` LIKE '%{$_GET['selectMonth']}%'
			AND budget.deleted = 0 ");
//echo '<pre>' . var_dump($value) . '</pre>';
$sums_array_out[$value->categoryName] = $db->first()->total;




$totalNet = $totalIncome - $totalOutgoing;


}
$key = md5('aviisgreat');



?>




<div class="row">
		<?php if(!empty($_GET['selectMonth']) && $_GET['selectMonth'] != 1): ?>
<div class="col-md-6">
			<?php if ($prevTotalNet >= 0): ?>
				
			Bonus start: &pound;<?php echo $prevTotalNet ?>
			<?php else : ?>
				Negative start :&pound;<?php echo $prevTotalNet ?>
			<?php endif; ?>
			<br>
			<em><small>Previous Month Net Balance</small></em>
</div>
	<?php endif; ?>
	<div class="col-md-6 text-center">
		<h3 id="overall">Net Balance : &pound;<?php 
		if(!empty($totalNet)){
				echo $totalNet; 
					}
				?>
		</h3>
	</div>
	</div>

<script>sortBy();
$('#recurringSelect').hide();
  $('body').on('click', '#recurringButton', function(){

    $('#recurringSelect').show();
    $('#reccuringButton').hide();
  });

</script>



<div class="row">
<div class="col-md-12">
<hr style="border-top: 1px solid black">
	<h2>Income</h2>
	<div id="message"></div>
	<div class="col-md-10" id="income">
	<form action="insert.php" method="post" class="form-inline insertForm" style="white-space: nowrap;">
		<div class="form-group">
			<label for="itemName">Item</label>
			<input type="text" name="itemName" class="form-control"	id="itemName">
		</div>
		<div class="form-group">
			<label for="categoryId">Category</label>
			<select name="categoryId" class="form-control">
				<?php foreach($statusIncome as $status) : ?>
					<option value="<?php echo $status->id ?>"><?php echo ucwords(decrypt($status->categoryName, $key)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="price">Price</label>
			<input type="text" name="price" class="form-control"	id="price">
		</div>
		<div class="form-group">
			<label for="date">date</label>
				<input type="date" class="form-control" name="date" value="<?php escape(print(date("Y-m-d"))); ?>">
		</div>
		<div class="form-group">
			<label>Recurring</label>
			<input id="recurringButton" type="checkbox" name="recurring" value="1">
		</div>
			<select id="recurringSelect">
				<option value="">Monthly</option>
				<option value="">4 Weekly</option>
				<option value="">2 Weekly</option>
				<option value="">NOT RECURRING</option>
			</select>
	
		<input type="hidden" value="1" name="status">
		<input type="hidden" value="1" name="submitinsert">
		<input type="hidden" value="<?php echo escape($user->data()->id); ?>" name="userId">
		<input type="submit" class="btn-default btn" name="submitinsert">
	</form>
	<hr>
	

	<table class="table table-striped">
	
	    <thead>
	      <tr>
	        <th>Item</th>
	        <th>Category</th>
	        <th>Price</th>
	        <th>Date</th>
	        <th colspan="2"></th>
	      </tr>
	    </thead>
	    <tbody>
	      <?php foreach($incomes as $income) : ?>
<!-- 	Edit form -->
	 <script>
			$("#editDiv<?php echo $income->id ;?>").hide();
	</script>
	<tr>
	<td colspan="6" id="editDiv<?php echo $income->id ;?>" class="form-inline">
		<h2>Edit Income</h2>
		<form action="insert.php?updateinsert=<?php echo $income->id; ?>" rel="<?php echo $income->id; ?>" method="post" class="updateForm" style="white-space: nowrap;">
			<div class="form-group">
				<label for="itemName">Item</label>
				<input type="text" name="itemName" class="form-control"	id="itemName" value="<?php echo ucwords(decrypt($income->itemName, $key)) ;?>">
			</div>
			<div class="form-group">
				<label for="categoryId">Category</label>
				<select name="categoryId" class="form-control">
					<?php foreach($statusIncome as $status) : ?>
						<option value="<?php echo $status->id ?>" <?php if ($status->id == $income->categoryId) {echo 'selected';}  ?>><?php echo ucwords(decrypt($status->categoryName, $key)); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="price">Price</label>
				<input type="text" name="price" class="form-control"	id="price" value="<?php echo $income->price ;?>">
			</div>
			<div class="form-group">
				<label for="date">date</label>
					<input type="date" class="form-control" name="date" value="<?php escape(print(date("Y-m-d", strtotime($income->date)))); ?>">
			</div>
			

			<input type="hidden" value="1" name="status">
			<input type="hidden" value="<?php echo $income->id; ?>" name="updateinsert">
			<input type="submit" class="btn-default btn" name="updateinsert">
		</form>
		<hr>
	</td>
	</tr>
	      <tr>
	        <td><?php echo ucwords(decrypt($income->itemName, $key)) ;?></td>
	        <td><?php echo ucwords(decrypt($income->categoryName, $key)) ;?></td>
	        <td>&pound;<?php echo $income->price ;?></td>
	        <td><?php echo date('d-F-Y',strtotime($income->date)) ;?></td>
	        <td>
	        <form action="insert.php?id=<?php echo $income->id ;?>" rel="<?php echo $income->id ;?>" class="delete" method="post">
	        <input type="hidden" name="delete" value="<?php echo $income->id ;?>">
	        	<input type="submit" class="btn btn-danger" name="delete" value="Delete">
	        </form>
	        </td>
	        <td><button id="editIncome" rel="<?php echo $income->id ;?>" class="btn btn-warning">Edit</button></td>
	      </tr>
	      <tr>
	      	
	      </tr>
	      <?php endforeach ;?>
	    </tbody>
	  </table>
	</div>
	<div class="col-md-1 col-md-offset-1 text-center">
	<?php foreach($statusIncome as $status): ?>
	<?php if(!empty($sums_array_in[$status->categoryName])) : ?>
		<div class="row" style="border:1px solid #596a7b;">
			<?php echo ucwords(decrypt($status->categoryName, $key)); ?>	
			<br>
			<span>&pound;<?php echo escape($sums_array_in[$status->categoryName]); ?></span>
		</div>
		<br>
	<?php endif; ?>
	<?php endforeach; ?>

	<div class="row" style="border:1px solid #596a7b;">
		Balance  &pound;<?php echo $totalIncome; ?>
	</div>


</div>
</div>
</div>

<hr>
<div class="row">
<div class="col-md-12">		
<h2>Outgoing</h2>
	<div class="col-md-10" id="income">
	<form action="insert.php" method="post" class="form-inline insertForm" style="white-space: nowrap;">
		<div class="form-group">
			<label for="itemName">Item</label>
			<input type="text" name="itemName" class="form-control"	id="itemName">
		</div>
		<div class="form-group">
			<label for="categoryId">Category</label>
			<select name="categoryId" class="form-control">
				<?php foreach($statusOutgoing as $status) : ?>
					<option value="<?php echo $status->id ?>"><?php echo ucwords(decrypt($status->categoryName, $key)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="price">Price</label>
			<input type="text" name="price" class="form-control"	id="price">
		</div>
		<div class="form-group">
			<label for="date">date</label>
				<input type="date" class="form-control" name="date" value="<?php escape(print(date("Y-m-d"))); ?>">
		</div>
		<div class="form-group">
			<label>Recurring</label>
			<input type="checkbox" name="recurring" value="1">
		</div>
	
		<input type="hidden" value="2" name="status">
		<input type="hidden" value="<?php echo escape($user->data()->id); ?>" name="userId">
		<input type="hidden" value="1" name="submitinsert">
		<input type="submit" class="btn-default btn" name="submitinsert">
	</form>
	<hr>
	

	<table class="table table-striped">
	
	    <thead>
	      <tr>
	        <th>Item</th>
	        <th>Category</th>
	        <th>Price</th>
	        <th>Date</th>
	        <th colspan="2"></th>
	      </tr>
	    </thead>
	    <tbody>
	      <?php foreach($outgoings as $income) : ?>
<!-- 	Edit form -->
	 <script>
			$("#editDiv<?php echo $income->id ;?>").hide();
	</script>
	<tr>
	<td colspan="6" id="editDiv<?php echo $income->id ;?>" class="form-inline">
		<h2>Edit Outgoing</h2>
		<form action="insert.php?updateinsert=<?php echo $income->id; ?>" rel="<?php echo $income->id; ?>" method="post" class="updateForm" style="white-space: nowrap;">
			<div class="form-group">
				<label for="itemName">Item</label>
				<input type="text" name="itemName" class="form-control"	id="itemName" value="<?php echo ucwords(decrypt($income->itemName, $key)) ;?>">
			</div>
			<div class="form-group">
				<label for="categoryId">Category</label>
				<select name="categoryId" class="form-control">
					<?php foreach($statusOutgoing as $status) : ?>
						<option value="<?php echo $status->id ?>" <?php if ($status->id == $income->categoryId) {echo 'selected';}  ?>><?php echo ucwords(decrypt($status->categoryName, $key)) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="price">Price</label>
				<input type="text" name="price" class="form-control"	id="price" value="<?php echo $income->price ;?>">
			</div>
			<div class="form-group">
				<label for="date">date</label>
					<input type="date" class="form-control" name="date" value="<?php escape(print(date("Y-m-d", strtotime($income->date)))); ?>">
			</div>
			

			<input type="hidden" value="1" name="status">
			<input type="hidden" value="<?php echo $income->id; ?>" name="updateinsert">
			<input type="submit" class="btn-default btn" name="updateinsert">
		</form>
		<hr>
	</td>
	</tr>
	      <tr>
	        <td><?php echo ucwords(decrypt($income->itemName, $key)) ;?></td>
	        <td><?php echo ucwords(decrypt($income->categoryName, $key)); ;?></td>
	        <td>&pound;<?php echo $income->price ;?></td>
	        <td><?php echo date('d-F-Y',strtotime($income->date)) ;?></td>
	        <td>
	        <form action="insert.php?id=<?php echo $income->id ;?>" rel="<?php echo $income->id ;?>" class="delete" method="post">
	        <input type="hidden" name="delete" value="<?php echo $income->id ;?>">
	        	<input type="submit" class="btn btn-danger" name="delete" value="Delete">
	        </form>
	        </td>
	        <td><button id="editIncome" rel="<?php echo $income->id ;?>" class="btn btn-warning">Edit</button></td>
	      </tr>
	      <tr>
	      	
	      </tr>
	      <?php endforeach ;?>
	    </tbody>
	  </table>
	</div>
	<div class="col-md-1 col-md-offset-1 text-center">
	<?php foreach($statusOutgoing as $status): ?>
		<?php if(!empty($sums_array_out[$status->categoryName])) : ?>
		<div class="row" style="border:1px solid #596a7b;">
			<?php echo ucwords(decrypt($status->categoryName, $key)); ?> 
			<br>
			<span>&pound;<?php echo escape($sums_array_out[$status->categoryName]); ?></span>
		</div>
		<br>
	<?php endif; ?>
	<?php endforeach; ?>

	<div class="row " style="border:1px solid #596a7b;">
		Balance  &pound;<?php echo $totalOutgoing; ?>
	</div>
</div></div>
</div>

