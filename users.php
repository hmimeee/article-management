 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
 	header("Location: ./login.php");
 }

 if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {

   ?>

   <!--Users-->
   <div class="row">
     <?php
     $result = get_users($connect);
     while ($data = mysqli_fetch_array($result)) { ?>
      <div class="col-3">
       <div class="card" style="width: 20rem;height: 35rem;margin-top: 5px;">
        <div style="background: url('<?php if(!empty($data['picture'])){echo $data['picture'];} else { echo "https://www.drsubhashtech.edu.in/img/faculty/user.png";}?>');background-size: cover; height: 250px;width: auto;"></div>
        <div class="card-body text-center bg-secondary text-light">
          <h5 class="card-title"><?php echo $data['name']?></h5>
          <p class="card-text"><?php if (!empty($data['role'])){ echo get_role_name($connect,$data['role']);} ?></p>
          <?php
          if ($data['role']==1 || $data['role']==2){} else{ 
           for($r=0;$r<$data['quality'];$r++){?>
            <span class="fa fa-star text-warning"></span>
          <?php } 
          for($r=$data['quality'];$r<5;$r++){?>
            <span class="fa fa-star text-dark"></span>
          <?php } }?>
        </div>
        <ul class="list-group list-group-flush">
          <?php 
          if ($data['role']==1 || $data['role']==2){} else{ ?>
          <li class="list-group-item">Rate: <?php echo $data['rate']?> BDT</li>
          <li class="list-group-item">Completed: <?php echo get_paid_tasks_number($connect,$data['id']);?> articles</li>
        <?php } ?>
          <div class="card-body">
            <a href="profile.php?user=<?php echo $data['id']?>" class="btn btn-outline-primary btn-block">View User</a>
          </div>
        </div>
      </div>
      <?php
    }
    ?>
  </div>

  <script src="assets/sticky-kit.min.js"></script>
  <script src="assets/jquery.sparkline.min.js"></script>
  <script src="assets/popper.min.js"></script>
  <?php
} else {
	echo "<center>";
	message("You don't have enough permission!",0);
	echo "</center>";
}
include "inc/footer.php";
?>