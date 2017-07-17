<?php 
require_once 'core/init.php';
     $user = new User();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Budget App</title>
  <link rel="icon" href="http://www.drodd.com/images15/letter-b25.jpg">
 
  <link rel="stylesheet" href="https://bootswatch.com/superhero/bootstrap.min.css">
  <link rel="stylesheet" href="includes/css/jquery-ui.min.css">
  <link rel="stylesheet" href="includes/css/style.css">
  <script src="includes/js/jquery.min.js"></script>
  <script src="includes/js/bootstrap.min.js"></script>
  <script src="includes/js/jquery-ui.min.js"></script>
  <script src="includes/js/app.js"></script>
</head>
<body>
  

    <div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Budget_app</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
<!--               <li><a href="index.php">Home</a></li> -->
              <?php if ($user->isLoggedIn()) : ?>
              <li><a href="dashboard.php">Budget</a></li>
            <!--   <li><a href="task.php">Tasks</a></li> -->
<!--               <li><a href="workout.php">Workout</a></li> -->
              <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav">
              <li><a href="" id="time"></a></li>
              <li><select name="" id="" class="form-control" style="background-color:#bf5a16; color: white;">
                <option value="">£</option>
                <option value="">$</option>
                <option value=""> &#8362;</option>
                <option value=""> &#8364;</option>
              </select></li>
              </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php 
         
              if ($user->isLoggedIn()) :
             ?>
              <li><a href="logout.php">Logout</a></li>
<!--               <li><a href="update.php">Update</a></li> -->
              <li><a href="changepassword.php">Change password</a></li>
<!--               <li><a href="profile.php?user=<?php echo escape($user->data()->username); ?>">My Profile</a></li> -->
            <?php else : ?>
              <li><a href="login.php">log in</a></li>
              <li><a href="register.php">Register</a></li>
            <?php endif ; ?>
            </ul>

          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>