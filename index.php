<?php
include 'inc/header.php';
include 'lib/User.php';
$user = new User();
Session::checkSession();
?>

<?php
$loginmsg = Session::get("loginmsg");

if (isset($loginmsg)) {
	echo $loginmsg;
}
Session::set("loginmsg", NULL);
?>


<div class="panel panel-default">
<div class="panel-heading">
<h2>User list<span class="pull-right"><strong>Wellcome!</strong>
<?php
$name = Session::get("name");
if (isset($name)) {
	echo $name;
}
?>
</span></h2>
</div>

<div class="panel-body">
<table class="table table-striped">
<tr>
<th width="20%">Serial</th>
<th width="20%">Name</th>
<th width="20%">UserName</th>
<th width="20%">Email Address</th>
<th width="20%">Action</th>
</tr>
<?php
$user = new User();
$userdata = $user->getUserData();
if ($userdata) {
	$i = 0;
	foreach ($userdata as $data) {
		$i++;
?>
<tr>
<td><?php echo $i; ?></td>
<td><?php echo $data['name']; ?></td>
<td><?php echo $data['username']; ?></td>
<td><?php echo $data['email']; ?></td>
<td>
<a class="btn btn-primary" href="profile.php?id=<?php echo $data['id']; ?>">View</a>	
</td>
</tr>
<?php } }else{ ?>
<tr><td><h2>No User Data Found......</h2></td></tr>
<?php }?>
</table>
</div>
</div>





<?php
include 'inc/footer.php';
?>