<?php
require_once 'core/init.php';
require_once 'includes/header.php';

$db = DB::getInstance();

$db->query('SELECT * FROM `users`');
$users = $db->results();


if (Session::exists('home')) {
	echo '<p>' . Session::flash('home') . '</p>';
}

if ($user->isLoggedIn()) {
	$hello = $user->isLoggedIn();
	Redirect::To('dashboard.php');
?>
	 <p>Hello, <a href="profile.php?user=<?php echo escape($user->data()->username);?>"><?php echo escape($user->data()->username); ?></a></p>
		

	<ul class="list-group">
	<?php foreach($users as &$data): ?>
		<?php if($data == $user->isLoggedIn()){
			
		 ?>
		<li class="list-group-item"><a href="profile.php?user=<?php echo escape($data->username); ?>"><?php echo escape($data->username); ?></a></li>
	<?php } ?>
	<?php endforeach ; ?>
	</ul>
<?php	

if($user->hasPermission('admin')) {
        echo '<p>You are a Administrator!</p>';
    }

} else {
	echo '<p>You need to <a href="login.php">log in</a> to do that or <a href="register.php">Register</a></p>';
}


require_once 'includes/footer.php';
