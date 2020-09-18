<?php
session_start();
include "core.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Management System</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
 <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
 <link href="assets/dataTables.bootstrap4.min.css" rel="stylesheet">
 <link rel="stylesheet" href="assets/bootstrap-wysihtml5.css" />
 <link href="assets/tablesaw.css" rel="stylesheet">
 <link href="assets/dropify.min.css" rel="stylesheet">
 <link href="assets/dropzone.css" rel="stylesheet" type="text/css" />
 <script src="assets/wysihtml5-0.3.0.js"></script>
<script src="assets/bootstrap-wysihtml5.js"></script>
 <script>
    $(document).ready(function() {

        $('textarea').wysihtml5();


    });
    </script>

 <style type="text/css">
   .modal-dialog{
    max-width: 75%!important;
  }

  .profile-picture{
    border-radius: 100%;
    margin-left: 10px;
    margin-bottom: -100px;
    width: 200px;
  }

  .del{
    text-decoration: line-through;
  }


/*Checkboxes styles*/
.pub input[type="checkbox"] { display: none; }

.pub input[type="checkbox"] + label {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-top: 5px;
  font: 14px/20px 'Open Sans', Arial, sans-serif;
  color: #000;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}

.pub input[type="checkbox"] + label:last-child { margin-bottom: 0; }

.pub input[type="checkbox"] + label:before {
  content: '';
  display: block;
  width: 20px;
  height: 20px;
  border: 2px solid #6cc0e5;
  position: absolute;
  left: 0;
  top: 0;
  opacity: .6;
  -webkit-transition: all .12s, border-color .08s;
  transition: all .12s, border-color .08s;
}

.pub input[type="checkbox"]:checked + label:before {
  width: 10px;
  top: -5px;
  left: 5px;
  border-radius: 0;
  opacity: 1;
  border-top-color: transparent;
  border-left-color: transparent;
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark clearfix">
    <a class="navbar-brand" href="./">Management System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse float-right" id="navbarColor01">
      <div class="col-<?php if(isset($_SESSION['id'])){if($_SESSION['role'] == 1) { echo"6";}elseif($_SESSION['role'] == 2){echo"6";}else{echo"9";}}else{echo"10";}?>"></div>
      <ul class="navbar-nav mr-auto">
        <?php if (isset($_SESSION['id'])) { if ($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
          <li class="nav-item">
            <a class="nav-link" href="./"><i class="fa fa-list-alt"></i> Dashboard</a>
          </li>
        <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="./tasks.php"><i class="fa fa-tasks"></i> Tasks</a>
          </li>
          <?php if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) { ?>
            <li class="nav-item">
              <a class="nav-link" href="./projects.php"><i class="fa fa-sitemap"></i> Projects</a>
            </li>
            <?php } ?>

           <li class="nav-item">
            <a class="nav-link" href="./invoices.php"><i class="fa fa-file"></i> Invoices</a>
          </li>
          <?php if ($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
            <li class="nav-item">
              <a class="nav-link" href="./users.php"><i class="fa fa-users"></i> Users</a>
            </li>
          <?php } ?>
          <div class="dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user"></i> <?php echo $_SESSION['name'];?>
            </a>
            <div class="dropdown-menu bg-dark" aria-labelledby="profile">
              <li class="nav-item">
                <a class="nav-link" href="./profile.php"><i class="fa fa-user"></i> Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./logout.php"><i class="fa fa-sign-out"></i> Logout</a>
              </li>
            </div>
          </div>

        <?php } else { ?>

          <li class="nav-item">
            <a class="nav-link" href="./login.php"><i class="fa fa-lock"></i> Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./register.php"><i class="fa fa-user-plus"></i> Register</a>
          </li>
        <?php } ?>

      </ul>

    </div>
  </nav>
  <div style="margin-left: 60px; margin-right: 60px;">
   <br/>