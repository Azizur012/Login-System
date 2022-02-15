<?php
include 'inc/header.php';
Session::checkSession();
include 'lib/User.php';
$user = new User();
?>
<?php
if (isset($_GET['id'])) {
	$userid = (int)$_GET['id'];

	$sessId = Session::get("id");
if ($userid == $sessId) {
	header("Location: index.php");
}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatepass'])){
	$updatepass = $user->updatePass($userid, $_POST);
	
}

?>

<div class="panel panel-default">
<div class="panel-heading">
<h2>Change Password<span class="pull-right"><a class="btn btn-primary" href="profile.php?id=<?php echo $userid; ?>">Back</a></span></h2>
</div>

<div class="panel-body">
<div style="max-width: 600px; margin: 0 auto;">
<?php
if (isset($updatepass)) {
	echo $updatepass;	
}

?>




<form action="" method="POST">

<div class="form-group">
<label for="old_Pass">Old Password</label>
<input type="Password" id="old_Pass" name="old_Pass" class="form-control" />
</div>

<div class="form-group">
<label for="Password">New Password</label>
<input type="password" id="Password" name="Password" class="form-control"  />
</div>




<button type="submit" name="updatepass" class="btn btn-success">Update</button>

</form>

</div>
</div>

</div>



<?php
include 'inc/footer.php';
?>