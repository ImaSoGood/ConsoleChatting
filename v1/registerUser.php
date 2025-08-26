<?php

require_once '../srv/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['password']) && isset($_POST['username']))
	{
		$db = new DbOperation();
		$ans = $db->createUser($_POST['username'], $_POST['password']);
		
		if($ans === 0)
		{
		    $response['error'] = true;
            $response['message'] = "User already exist";
		}
		if($ans !== -100 && $ans !== 0)
		{
		    $response['error'] = false;
            $response['message'] = "User registered succesfully";
            $response['id'] = $ans;
            $response['username'] = $_POST['username'];
		}
		if($ans == -100)
		{
		    $response['error'] = true;
            $response['message'] = "DB error";
		}
	}
	else
	{
	    $response['error'] = true;
	    $response['message'] = "Required fields are missing";
	}
}
else
{
	$response['error'] = true;
	$response['message'] = "Some error occurred";
}
echo json_encode($response);
?>

