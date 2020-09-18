<?php
include "inc/header.php";
if (isset($_POST['email']) && isset($_POST['password'])) {
$query ="SELECT * FROM users WHERE email='".$_POST['email']."' AND password='".$_POST['password']."'";
$result = select_data($query,$connect);

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()){
  $_SESSION['id'] = $row['id'];
  $_SESSION['name'] = $row['name'];
  $_SESSION['role'] = $row['role'];
}
} else {
  message("Your email/password was incorrect!","0");
}
}
if (isset($_SESSION['id'])) {
  if ($_SESSION['role']==5) {
    header("location: ./pending_user.php");
  } else{
  header("Location: ./");
}
}
?>
<div class="row">
	<div class="col-3"></div>
	<div class="col-6">
<div class="card">
  <div class="card-body">
  	<h5 class="card-title text-center">User Login</h5>
    <form method="post">
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
  </div>
  <button type="submit" class="btn btn-primary btn-block">Login</button>
</form>
<br>
Are you new here? <a href="register.php">Sign Up Now</a>
  </div>
</div>
</div>
<div class="col-3"></div>
</div>
<?php
include "inc/footer.php";
?>