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
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'name' => 'Password',
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
        ));

			if($validation->passed()) {
				$user = new User();

				$salt = Hash::salt(32); 
				

				try {					
					$user->create(array(
              'username' => encrypt(Input::get('username'), $key),
              'password' => Hash::make(Input::get('password'), $salt),
              'salt' => $salt,
              'joined' => date('Y-m-d H:i:s'),
              'groupNumber' => 1
          ));

          Session::flash('home', 'you have been registered and can log in');
          Redirect::to('index.php');

				} catch (Exception $e) {
					die($e->getMessage());
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
		<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
	</div>

	<div class="field">
		<label for="password">Choose a password</label>
		<input type="password" name="password"	id="password">	
	</div>

	<div class="field">
		<label for="password_again">Enter password Again</label>
		<input type="password" name="password_again"	id="password_again">	
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Register">
</form>

<?php require_once 'includes/footer.php'; ?>