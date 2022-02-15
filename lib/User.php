<?php
include_once 'Session.php';
include 'Database.php';

class User
{
private $db; 
public function __construct()
{
$this->db = new Database();
}

public function userRegistration($data){
	    $name     = $data['name'];
        $username = $data['username'];
        $email    = $data['email'];
        $password = $data['password'];
        $chk_email = $this->emailCheck($email);

    if ($name == "" OR $username == "" OR $email == "" OR $password == ""){
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Field must not be Empty</div>";
    	return $msg;
    }
    if (strlen($username) < 3) {
    	
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>username is too short.</div>";
    	return $msg;

    }elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>username must only contain alphanumerical,dashes and underscores!</div>";
    	   
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) ===false){
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Email Address is not valid!!</div>";
    	return $msg;	
    }


    if ($chk_email == true) {
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Email Address Already Exist!!</div>";
    	return $msg;
    }
    
$password = md5($data['password']);
 $sql = "INSERT INTO db_user (name, username, email, password) VALUES(:name,:username,:email,:password)";

 $query = $this->db->pdo->prepare($sql);
    $query->bindvalue(':name',$name);
    $query->bindvalue(':username',$username);
    $query->bindvalue(':email',$email);
    $query->bindvalue(':password',$password);
    $result = $query->execute();
    if($result){
       $msg = "<div class='alert alert-success'><strong>Success</strong>Thanks for register him.</div>";
        return $msg;
    }else{
        $msg = "<div class='alert alert-danger'><strong>Error !!</strong>Sorry your registation faill.</div>";
        return $msg;
    }
	
}

  public function emailCheck($email){
  	$sql = "SELECT email FROM db_user WHERE email = :email";
  	$query = $this->db->pdo->prepare($sql);
    $query->bindvalue(':email',$email);
    $query->execute();
    if ($query->rowCount() >0) {
    	return true;
    }else{
    	return false;
    } 

  }

  public function getLoginUser($email, $password){
   $sql = "SELECT * FROM db_user WHERE email = :email AND password = :password LIMIT 1";
  	$query = $this->db->pdo->prepare($sql);
    $query->bindvalue(':email',$email);
    $query->bindvalue(':password',$password);
    $query->execute();
    $result =  $query->fetch(PDO::FETCH_OBJ);
    return $result;
  }

  public function userLogin($data){
        $email    = $data['email'];
        $password = md5($data['password']);

        $chk_email = $this->emailCheck($email);

    if ($email == "" OR $password == ""){
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Field must not be Empty</div>";
    	return $msg;
  }

   if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Email Address is not valid!!</div>";
    	return $msg;	
    }


    if ($chk_email == false) {
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Email Address Not Exist!!</div>";
    	return $msg;
    }

  

 $result = $this->getLoginUser($email, $password);
 if ($result) {
 	Session::init();
 	Session::set("login", true);
 	Session::set("id", $result->id);
 	Session::set("name", $result->name);
 	Session::set("username", $result->username);
 	Session::set("loginmsg", "<div class='alert alert-success'><strong>Success! </strong>Your are LoggIn!</div>");
 	header("Location:index.php");
 }else{
 	$msg = "<div class='alert alert-danger'><strong>Error! </strong>Data not found</div>";
 	return $msg;
 }

}

public function getUserData(){
  $sql = "SELECT * FROM db_user ORDER BY id DESC";
  	$query = $this->db->pdo->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();
    return $result;
    
}

public function getUserId($id){
	$sql = "SELECT * FROM db_user WHERE id = :id LIMIT 1";
  	$query = $this->db->pdo->prepare($sql);
    $query->bindvalue(':id',$id);
    $query->execute();
    $result =  $query->fetch(PDO::FETCH_OBJ);
    return $result;
} 

public function updateUser($id, $data){
	     $name     = $data['name'];
        $username = $data['username'];
        $email    = $data['email'];
        
       
    if ($name == "" OR $username == "" OR $email == ""){
    	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Field must not be Empty</div>";
    	return $msg;
    }
   
    

 $sql = "UPDATE db_user set
          name     = :name,
          username = :username,
          email    = :email
         WHERE id  = :id";

 $query = $this->db->pdo->prepare($sql);

    $query->bindvalue(':name',$name);
    $query->bindvalue(':username',$username);
    $query->bindvalue(':email',$email);
    $query->bindvalue(':id',$id);
    $result = $query->execute();
    if($result){
       $msg = "<div class='alert alert-success'><strong>Success</strong>Userdata Update Successfuly.</div>";
        return $msg;
    }else{
        $msg = "<div class='alert alert-danger'><strong>Error !!</strong>Sorry your Update faill.</div>";
        return $msg;
}

}

private function checkpassword($id, $old_Pass){
$password = md5($old_Pass);
 $sql = "SELECT password FROM db_user WHERE id = :id AND  password = :password";
  	$query = $this->db->pdo->prepare($sql);
    $query->bindvalue(':id',$id);
    $query->bindvalue(':password',$password);
    $query->execute();
    if ($query->rowCount() >0) {
    	return true;
    }else{
    	return false;
    } 

}

public function updatePass($id, $data){
	$old_Pass =$data['old_Pass'];
	$new_Pass =$data['Password'];
	if ($old_Pass == "" OR $new_Pass == "") {
	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Field Must Not Be Empty.</div>";
        return $msg;
	}
$chk_pass = $this->checkpassword($id, $old_Pass);
if ($chk_pass == false) {
	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Old Password not Exist.</div>";
        return $msg;
}
if (strlen($new_Pass) <6) {
	$msg = "<div class='alert alert-danger'><strong>Error !!</strong>Password is Too Short.</div>";
        return $msg;
}
$password = md5($new_Pass);
 $sql = "UPDATE db_user set
         password     = :password
         WHERE id  = :id";

 $query = $this->db->pdo->prepare($sql);
    $query->bindvalue(':password',$password);
    $query->bindvalue(':id',$id);
    $result = $query->execute();
    if($result){
       $msg = "<div class='alert alert-success'><strong>Success</strong>Password Update Successfuly.</div>";
        return $msg;
    }else{
        $msg = "<div class='alert alert-danger'><strong>Error !!</strong>Sorry your Update faill.</div>";
        return $msg;
}
}
}

?>