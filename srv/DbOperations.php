<?php

class DbOperation
{
    private $response = array(); 
    private $con;
    
    function __construct()
    {
        require_once dirname(__FILE__).'/DbConnect.php';//getUserWorkTypesArray//DbOperations.php
		$db = new DbConnect();

		$this->con = $db->connect(); 
    }
    
    private function closeStmt($stmt) 
    {
        $stmt->close();
        //$this->con->close();
    }
	
    public function createUser($username, $password)
    {
        if($this->isUserExist($username))
        {
            return 0;
        }
        else
        {
            $password = md5($password);
            $stmt = $this->con->prepare("INSERT INTO Users(password, username) VALUES (?, ?)");
            
            $stmt->bind_param("ss", $password, $username);
            if($stmt->execute())
            {
                $user_id = $this->returnId($username, $password);
          
                $response['error'] = false;
                $response['message'] = "User register successfully";
                $response['id'] = (string)$user_id;
                
                $this->closeStmt($stmt); 
                //echo json_encode($response);
                return 1; 
            }
            else
            {
                $this->closeStmt($stmt); 
                return 2; 
            }
        }
    }
    
    private function isUserExist($username)
    {
        $stmt = $this->con->prepare("SELECT id FROM Users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute(); 
        $stmt->store_result(); 
        $result = $stmt->num_rows > 0;
    
        if($result)
        {
            $response['error'] = true;
            $response['message'] = "Username already exist";
            //echo json_encode($response);
        }
        
        $this->closeStmt($stmt); 
        return $result; 
    }
    
    public function returnId($username, $password)
    {
        $stmt = $this->con->prepare("SELECT id FROM Users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $user_id = $row["id"];
            $this->closeStmt($stmt); 
            return $user_id;
        }
    }
    
    public function loginUser($username, $password)
    {
        $password = md5($password);
        $stmt = $this->con->prepare("SELECT id FROM Users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0)
        {
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();
            return $id;
        } 
        else 
        {
            $stmt->close();
            return false;
        }
    }
    


//_________________________________FIND CHAT_______________________________________________
//_________________________________FIND CHAT_______________________________________________
//_________________________________FIND CHAT_______________________________________________

    
    
    public function startChat($user_id, $username)
    {
        $chat_id = $this->getExistingChatId($user_id, $username);
    
        if ($chat_id === 0) 
        {
            $target_user_id = $this->getUserIdByUsername($username);
        
            if ($target_user_id > 0) 
            {
                return $this->createNewChat($user_id, $target_user_id);
            }
            
            return 0;
        }
        
        return $chat_id;
    }

    public function getExistingChatId($user_id, $username)
    {
        $target_user_id = $this->getUserIdByUsername($username);
    
        if ($target_user_id <= 0) 
        {
            return -1;
        }
    
        // Проверяем оба направления чата
        $chat_id = $this->findChatBetweenUsers($user_id, $target_user_id);
        if ($chat_id > 0) 
        {
            return $chat_id;
        }
    
        $chat_id = $this->findChatBetweenUsers($target_user_id, $user_id);
        if ($chat_id > 0) 
        {
            return $chat_id;
        }
        
        return 0;
    }

    private function getUserIdByUsername($username)
    {
        $stmt = $this->con->prepare("SELECT id FROM Users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $this->closeStmt($stmt); 
            
            return $row["id"];
        }
    
        return 0;
    }

    private function findChatBetweenUsers($user1_id, $user2_id)
    {
        $stmt = $this->con->prepare("SELECT id FROM Chats WHERE creator_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $user1_id, $user2_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $this->closeStmt($stmt); 
            
            return $row["id"];
        }
    
        return 0;
    }

    private function createNewChat($creator_id, $user_id)
    {
        $stmt = $this->con->prepare("INSERT INTO Chats (creator_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $creator_id, $user_id);
        $stmt->execute();
        $this->closeStmt($stmt); 
    
        return $this->con->insert_id;
    }
    
    public function findAllChats($user_id)
    {
        $stmt = $this->con->prepare("SELECT Chats.id AS chat_id, Users.id AS user_id, Users.username 
	                                    FROM Chats 
		                                    INNER JOIN Users ON Users.id = 
		                                        CASE WHEN Chats.creator_id = ? 
			                                    THEN Chats.user_id 
			                                    WHEN Chats.user_id = ? THEN Chats.creator_id 
			                                    ELSE NULL 
		                                    END WHERE (Chats.creator_id = ? OR Chats.user_id = ?)
	                                    AND Users.id != ?");
        $stmt->bind_param("iiiii", $user_id, $user_id, $user_id, $user_id, $user_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }
        
        if(empty($data))
            return 0;

        $this->closeStmt($stmt); 
        return $data; 
    }
    
//_________________________________FIND CHAT_______________________________________________
//_________________________________FIND CHAT_______________________________________________
//_________________________________FIND CHAT_______________________________________________
    
    
    
//_________________________________SEND MESSAGES_______________________________________________
//_________________________________SEND MESSAGES_______________________________________________
//_________________________________SEND MESSAGES_______________________________________________

    public function getMessages($chat_id)
    {
        $data = array();
        
        $stmt = $this->con->prepare("SELECT * from Messages WHERE chat_id = ?");
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }
        
        if(empty($data))
            return 0;

        $this->closeStmt($stmt); 
        return $data;
    }
    
    public function sendMessage($user_id, $message, $chat_id)
    {
        $stmt = $this->con->prepare("INSERT INTO Messages (user_id, message, chat_id, date) VALUES (?,?,?,now())");
        $stmt->bind_param("isi", $user_id, $message, $chat_id);
        
        if($stmt->execute())
        {
            $this->closeStmt($stmt); 
            return $this->con->insert_id;
        }
        else
        {
            $this->closeStmt($stmt); 
            return 0;
        }
    }
    
    public function getNewMessages($chat_id, $lastMessage_id)
    {
        $data = array();
        
        $stmt = $this->con->prepare("SELECT * FROM Messages WHERE chat_id = ? AND id > ?");
        $stmt->bind_param("ii", $chat_id, $lastMessage_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }
        
        if(empty($data))
            return 0;

        $this->closeStmt($stmt); 
        return $data;
    }
    
    

//_________________________________SEND MESSAGES_______________________________________________
//_________________________________SEND MESSAGES_______________________________________________
//_________________________________SEND MESSAGES_______________________________________________
}
?>
