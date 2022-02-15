public function userRegistration($data){
$name     = $data['name'];
$username = $data['username'];
$email    = $data['email'];
$password = md5($data['password']);

$chk_email = $this->emailCheck($email);

if ($name == "" OR $username == "" OR $email == "" OR $password == "") {
$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field must not be Empty</div>";
return $msg;
}

if (strlen($username) < 3) {
	$msg = "<div class='alert alert-danger'><strong>Error !</strong>User Name too Short</div>";
return $msg;

}elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
$msg = "<div class='alert alert-danger'><strong>Error !</strong>UserName must only contain alphanumerical deshes and underscores</div>";
return $msg;
}

if (filter_var($email, FILTER_VALISATE_EMAIL) == false) {
$msg = "<div class='alert alert-danger'><strong>Error !</strong>The Email Address is not valid</div>";
return $msg;
}
if ($chk_email == true) {
	$msg = "<div class='alert alert-danger'><strong>Error !</strong>The Email Address already Exist</div>";
return $msg;
}

 $sql = "INSERT INTO db_user (name, username, email, password) VALUES(:name, :username, :email, :password)";
 $query = $this->db->pdo->prepare($sql);	
$query->bindValue(':name', $name);
$query->bindValue(':username', $username);
$query->bindValue(':email', $email);
$query->bindValue(':password', $password);
$result = $query->execute();
if ($result) {
	$msg = "<div class='alert alert-success'><strong>Success !</strong>Data Insert Successfully</div>";
return $msg;
}else{
	$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not Insert</div>";
return $msg;
}

}
public function emailCheck($email){

$sql = "SELECT email FROM db_user WHERE email = :email";
$query = $this->db->pdo->prepare($sql);	
$query->bindValue(':email', $email);
//$query->execute();
if($query->rowCount() >0) {
	return true;
}else{
	return false;
}
}
