

<?php
include('core/init.php'); 

$db = DB::getInstance();

$key = md5('aviisgreat');


if(isset($_POST['taskDone'])){
$id = $_GET['taskDone'] ;
	$db->query("UPDATE `task` SET taskDone = 1 WHERE id ='".$id."'");
	die($id);
}



if(isset($_POST['delete'])){
$id = $_GET['id'] ;
	$db->query("DELETE FROM `budget` WHERE id ='".$id."'");
}

if(isset($_POST['categorydelete'])){
  echo "hi";

$id = $_GET['categorydelete'];
var_dump($id);
  $db->query("UPDATE `categories` SET deleted = 1 WHERE id ='".$id."'");
  $db->query("UPDATE `budget` SET deleted = 1 WHERE categoryId ='".$id."'");
}

if(isset($_POST['categoryinsert'])){
$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'categoryName' => array(
            'required' => true,
            'min' => 2,
            'max' => 50

        )
	 ));

 if($validation->passed()) {
		try {					
			$db->insert('categories', array(
          'categoryName' => encrypt(Input::get('categoryName'), $key),
          'status' => Input::get('catStatus'),
          'user_id' => Input::get('user_id')
      ));


		} catch (Exception $e) {
			die($e->getMessage());
			}
		} else {
		foreach($validation->errors() as $error) {
			echo $error, '<br>';
          }
      }
      echo Input::get('categoryName'). ' has been inserted';
}


if(isset($_POST['submitinsert'])){
	if(isset($_POST['recurring']) == 1) {
			$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'itemName' => array(
            'required' => true,
            'min' => 2,
            'max' => 50
        ),
        'categoryId' => array(
            'required' => true
        ),
        'price' => array(
            'required' => true,
            'number' => true
        ),
        'date' => array(
          'required' => true
        )
	 ));

 if($validation->passed()) {

      try {         
      $db->insert('budget', array(
          'itemName' => encrypt(Input::get('itemName'), $key),
          'categoryId' => Input::get('categoryId'),
          'price' => Input::get('price'),
          'date' => Input::get('date'),
          'status' => Input::get('status'),
          'userId' => Input::get('userId')
      ));
    } catch (Exception $e) {
      die($e->getMessage());
      }

    $date = Input::get('date');

  for ($i=0; $i < 11 ; $i++) { 
    
    $dates[] = $date;
    $time = date('Y-m-d', strtotime('+4 week', strtotime($date)));
    $date = $time;

		try {					
			$db->insert('budget', array(
          'itemName' => encrypt(Input::get('itemName'), $key),
          'categoryId' => Input::get('categoryId'),
          'price' => Input::get('price'),
					'date' => $time,
					'status' => Input::get('status'),
          'userId' => Input::get('userId')
      ));
		} catch (Exception $e) {
			die($e->getMessage());
			}
  }



		} else {
		foreach($validation->errors() as $error) {
			echo $error, '<br>';
          }
      }
	} else {



	$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'itemName' => array(
            'required' => true,
            'min' => 2,
            'max' => 50
        ),
        'categoryId' => array(
            'required' => true
        ),
        'price' => array(
            'required' => true,
            'number' => true
        ),
        'date' => array(
          'required' => true
        )
	 ));

 if($validation->passed()) {
/*$secure = "AES_ENCRYPT(".$data.", $key)";*/
		try {					
			$db->insert('budget', array(
          'itemName' => encrypt(Input::get('itemName'), $key),
          'categoryId' => Input::get('categoryId'),
          'price' => Input::get('price'),
					'date' => Input::get('date'),
					'status' => Input::get('status'),
          'userId' => Input::get('userId')
      ));


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

if(isset($_POST['updateinsert'])){
	$id = $_GET['updateinsert'];
	
	$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'itemName' => array(
            'required' => true,
            'min' => 2,
            'max' => 255
        ),
        'categoryId' => array(
            'required' => true
        ),
        'price' => array(
            'required' => true,
            'number' => true
        ),
        'date' => array(
          'required' => true
        )
	 ));

 if($validation->passed()) {

		try {					
			$db->query("UPDATE `budget` 
		SET itemName = '".encrypt(Input::get('itemName'), $key)."', categoryId = '".Input::get('categoryId')."', price = '".Input::get('price')."', date = '".Input::get('date')."' WHERE id ='".$id."'");
      


		} catch (Exception $e) {
			die($e->getMessage());
			}
		} else {
		foreach($validation->errors() as $error) {
			echo $error, '<br>';
          }
      }
      Redirect::To('dashboard.php');
}


