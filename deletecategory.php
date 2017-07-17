<?php
require_once 'core/init.php';
     $user = new User();
$db = DB::getInstance();

$user_id = $user->data()->id;

$key = md5('aviisgreat');
$db->query("SELECT * FROM categories WHERE user_id = '".$user_id."' AND deleted = 0 ORDER BY status DESC");
$status = $db->results();

?>	

<form action="" method="post" id="deleteCategory">
		<select id="selectDeleteCat" class="form-control" style="margin-top:66.5px;">
		<option disabled="true" selected>Delete Category</option>
		<?php foreach ($status as $stat): ?>

			<option value="<?php echo $stat->id; ?>" name="catId"><?php echo ucfirst(decrypt($stat->categoryName, $key)); ?> 
			<?php if($stat->status == 1){
				echo '(income)';
			} else {
				echo '(outgoing)';
			}

			 ?>
				
			</option>
		<?php endforeach; ?>
		</select>
		<input type="hidden" name="categorydelete" value="1">
</form>