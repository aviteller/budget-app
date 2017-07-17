<?php 
require_once 'core/init.php';
require_once 'includes/header.php';


$key = md5('aviisgreat');

if (Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$validate = new Validate();
        $validation = $validate->check($_POST, array(
        	'username' => array(
                'name' => 'Username',
                'required' => true,
                'min' => 2,
                'max' => 20            ),
            'password' => array(
                'name' => 'Password',
                'required' => true,
                'min' => 6
            )
        ));

        if ($validation->passed()) {
        	$user = new User();

        	$remember = (Input::get('remember') === 'on') ? true : false;
        	$login = $user->login(encrypt(Input::get('username'), $key),Input::get('password'), $remember);
           /* var_dump($login);*/
        	if ($login) {
        		Redirect::to('dashboard.php');
        	} else {
        		echo "failed";
        	}
        } else {
        	foreach($validation->errors() as $error) {
        		echo $error, '<br>';
        	}
        }
	}
}

?>

<form action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username">
	</div>

	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" id="password">		
	</div>

	<div class="field">
		<label for="remember">
		  <input type="checkbox" name="remember" id="remember"> Remember me
        </label>
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="log in">
</form>

<?php require_once 'includes/footer.php'; ?>