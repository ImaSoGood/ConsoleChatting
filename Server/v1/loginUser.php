<?php 

require_once '../srv/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		$db = new DbOperation(); 
        $id = $db->loginUser($_POST['username'], $_POST['password']);
        
		if($id !== -1 && $id !== false)
		{
		    $response['message'] = "You have entered in";
			$response['error'] = false; 
			$response['id'] = $id;
			$response['username'] = $_POST['username'];
		}
		else
		{
			$response['error'] = true;
			$response['code'] = -10;
			$response['message'] = "Invalid username or password";			
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
	$response['message'] = "Some shit";
}

echo json_encode($response);
?>

