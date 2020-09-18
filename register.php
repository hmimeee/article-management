<?php
include "inc/header.php";
if (isset($_SESSION['id'])) {
  header("location: ./");
}
if ($_SERVER['REQUEST_METHOD']=="POST") {
  $query = "INSERT INTO users (name,email,password,role) VALUES ('".$_POST['name']."','".$_POST['email']."','".$_POST['password']."','5')";

  add_data($query,$connect,"You are successfully registered!");
}

/*$query = "SELECT * FROM users";
$result = select_data($query,$connect);
while($row = mysqli_fetch_array($result)){
  echo $row['name']."<br>";
}*/

?>
<div class="row">
	<div class="col-3"></div>
	<div class="col-6">
    <div class="card">
      <div class="card-body">
       <h5 class="card-title text-center">User Registration</h5>
       <form method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" placeholder="Name" name="name">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" placeholder="Password" name="password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
      </form>
      <br>
      Are you already registered? <a href="login.php">Login Now</a>
    </div>
  </div>
</div>
<div class="col-3"></div>
</div>
<?php
include "inc/footer.php";
?>