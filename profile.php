 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
  header("Location: ./login.php");
}

if ($_SERVER['REQUEST_METHOD'] =="POST" && isset($_POST['update_profile'])) {
    if (isset($_POST['role'])) {
      $update = update_user($connect,$_POST['role'],$_POST['quality'],$_POST['rate'],$_GET['user']);
} else {
    $target_dir = "uploads/";
  $target_file = $target_dir .rand(). basename($_FILES["picture"]["name"]);
  if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
        $picture = $target_file;
    } else {
      $picture = $_POST['picture_def'];
    }
    
  $update = update_user_data($connect,$_POST['name'],$_POST['email'],$_POST['phone'],strip_tags($_POST['address']),$_POST['payment_method'],$_POST['payment_account'],$_POST['fb_url'],$_POST['twitter_url'],$_POST['instagram_url'],$picture,$_POST['id']);
}
if ($update ==1) {
  message("Updated successfully!",1);
} else {
message("Something went wrong!",0);
}

}

if ($_SERVER['REQUEST_METHOD'] =="POST" && isset($_POST['update_password'])) {
  if ($_POST['new-pass']==$_POST['confirm-new-pass']) {
  $data_check = get_user_data($connect,$_POST['update_password']);
  if ($data_check['password']==$_POST['old-pass']) {
    $update_password = update_password($connect,$_POST['new-pass'],$_POST['update_password']);
    if ($update_password==1) {
      message("Password has been changed!",1);
    }
  } else {
    message("Your old password didn't matched!",0);
  }
} else {
  message("Confirm password didn't matched!",0);
  }
}

if (isset($_GET['user'])) {
  if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {
  $data = get_user_data($connect,$_GET['user']);
}
} else {
$data = get_user_data($connect,$_SESSION['id']);
}

if (isset($_GET['del'])) {

   if ($_SESSION['id'] == $_GET['del']) {
    session_destroy();
  }

  $del = delete_user($connect,$_GET['del']);
  if ($del ==1) {
    header("location: ./users.php");
  }
}
?>

<!-- Row -->
<div class="row">
  <!-- Column -->
  <div class="col-lg-4 col-xlg-3 col-md-5">
    <div class="card">
      <div class="card-body">
        <center class="m-t-30"> <img src="<?php echo $data['picture'];?>" class="rounded-circle w-cover" width="150" />
          <h4 class="card-title m-t-10"><?php echo $data['name'];?></h4>
          <h6 class="card-subtitle"><?php if (!empty($data['role'])){echo get_role_name($connect,$data['role']);} ?></h6>
          <?php for($r=0;$r<$data['quality'];$r++){?>
      <span class="fa fa-star text-warning"></span>
    <?php } 
    for($r=$data['quality'];$r<5;$r++){?>
      <span class="fa fa-star text-dark"></span>
    <?php }?>
        </center>
        <div class="row text-center justify-content-md-center">
          <div class="col-4"><i class="fa fa-money"></i> <font class="font-medium"><?php echo $data['rate'];?></font></div>
          <div class="col-4"><i class="fa fa-check-circle"></i> <font class="font-medium"><?php echo get_paid_tasks_number($connect,$data['id']);?></font></div>
        </div>
      </div>
      <div>
        <hr> </div>
        <div class="card-body"> <small class="text-muted">Email address </small>
          <h6><?php echo $data['email'];?></h6> 
          <small class="text-muted p-t-30 db">Phone</small>
          <h6><?php echo $data['phone'];?></h6> 
          <small class="text-muted p-t-30 db">Address</small>
          <h6><?php echo $data['address'];?></h6>
          <small class="text-muted p-t-30 db">Payment Method</small>
          <h6><?php echo $data['payment_method'];?></h6>
          <small class="text-muted p-t-30 db">Payment Account</small>
          <h6><?php echo $data['payment_account'];?></h6>

          <small class="text-muted p-t-30 db">Social Profile</small>
          <br/>
          <a href="<?php if (!empty($data['fb_url'])){echo $data['fb_url'];}else{echo "#";}?>"><button class="btn btn-secondary"><i class="fa fa-facebook"></i></button></a>
          <a href="<?php if (!empty($data['twitter_url'])){echo $data['twitter_url'];}else{echo "#";}?>"><button class="btn btn-secondary"><i class="fa fa-twitter"></i></button></a>
          <a href="<?php if (!empty($data['instagram_url'])){echo $data['instagram_url'];}else{echo "#";}?>"><button class="btn btn-secondary"><i class="fa fa-instagram"></i></button></a>
        </div>
      </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
      <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab" role="tablist">
          <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Paid Invoices</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#update-profile" role="tab">Update Profile</a> </li>
          <?php if (!isset($_GET['user'])) { ?>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ch-pass" role="tab">Change Password</a> </li>
          <?php } ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="home" role="tabpanel">
            <div class="card-body">
              <div class="profiletimeline">
               <?php 
               $invoices = get_invoices_by_user($connect,$data['id']);
               if ($invoices->num_rows>0) {
                 while ($invoice = mysqli_fetch_array($invoices)) { ?>

                <div class="sl-item">
                  <div class="sl-left"> <img src="<?php echo $data['picture'];?>" alt="user" class="rounded-circle" height="50px" /> </div>
                  <div class="sl-right">
                    <div><a href="?user=<?php echo $data['id'];?>" class="link"><?php echo $data['name'];?></a> (<span class="sl-date">11-12-2019</span>)
                      <blockquote class="m-t-10">
                        Last payment received for invoice <a href="invoices.php">#<?php echo "VXARI".str_pad($invoice['id'], 6, '0', STR_PAD_LEFT); ?></a> and the ammount was <code>1435.54 BDT</code>.
                      </blockquote>
                    </div>
                  </div>
                </div>

                <?php 
                  
                 }
               } ?>

              </div>
            </div>
          </div>

          <!-- Tab panes -->
          <div class="tab-pane" id="update-profile" role="tabpanel">
            <div class="card-body">
              <form class="form-horizontal form-material" method="post" enctype="multipart/form-data">
                  <?php if (!isset($_GET['user'])) { ?>

                  <h4 class="card-title">Change Picture</h4>
                  <input type="file" id="input-file-now-custom-1" class="dropify" data-default-file="<?php echo $data['picture'];?>" name="picture" />
               <input type="hidden" value="<?php echo $data['picture'];?>" name="picture_def" />

              <div class="form-group">
                <label class="col-md-12">Name</label>
                <div class="col-md-12">
                  <input type="text" name="name" class="form-control form-control-line" value="<?php echo $data['name'];?>">
                </div>
              </div>
              <div class="form-group">
                <label for="phone" class="col-md-12">Phone</label>
                <div class="col-md-12">
                  <input type="number" class="form-control form-control-line" name="phone" id="phone" value="<?php echo $data['phone'];?>">
                </div>
              </div>
              <div class="form-group">
                <label for="email" class="col-md-12">Email</label>
                <div class="col-md-12">
                  <input type="email" class="form-control form-control-line" name="email" id="email" value="<?php echo $data['email'];?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-12">Payment Method</label>
                <div class="col-md-12">
                  <select class="form-control form-control-line" name="payment_method">
                    <option value="<?php echo $data['payment_method'];?>"><?php echo $data['payment_method'];?> (Selected)</option>
                    <option>bKash</option>
                    <option>Rocket (DBBL)</option>
                    <option>DBBL Bank</option>
                    <option>BRAC Bank</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-12">Payment Account</label>
                <div class="col-md-12">
                  <input type="text" class="form-control form-control-line" name="payment_account" value="<?php echo $data['payment_account'];?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-12">Address</label>
                <div class="col-md-12">
                  <textarea rows="5" class="form-control form-control-line" name="address"><?php echo $data['address'];?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="facebook" class="col-md-12">Facebook URL</label>
                <div class="col-md-12">
                  <input type="text" class="form-control form-control-line" name="fb_url" id="facebook" value="<?php echo $data['fb_url'];?>">
                </div>
                <label for="twitter" class="col-md-12">Twitter URL</label>
                <div class="col-md-12">
                  <input type="text" class="form-control form-control-line" name="twitter_url" id="twitter" value="<?php echo $data['twitter_url'];?>">
                </div>
                <label for="instagram" class="col-md-12">Instagram URL</label>
                <div class="col-md-12">
                  <input type="text" class="form-control form-control-line" name="instagram_url" id="instagram" value="<?php echo $data['instagram_url'];?>">
                </div>
              </div>

            <?php } else { ?>

              <div class="form-group <?php if($_SESSION['role'] !=1){echo"d-none";} ?>">
                <label class="col-md-12">Rate (Per 1K Words)</label>
                <div class="col-md-12">
                  <input type="text" class="form-control form-control-line" name="rate" value="<?php echo $data['rate'];?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-12">Quality (Base on writing)</label>
                <div class="col-md-12">
                  <input type="range" class="form-control form-control-line" name="quality" value="<?php echo $data['quality'];?>" max="5">
                </div>
              </div>

              <div class="form-group <?php if($_SESSION['role'] !=1){echo"d-none";} ?>">
                <label class="col-md-12">User Role</label>
                <div class="col-md-12">
                 <select class="form-control form-control-line" name="role">
                  <?php if (!empty($data['role'])) {?>
                    <option value="<?php echo $data['role'];?>"><?php echo get_role_name($connect,$data['role']);?> (Selected)</option>
                    <?php } ?>

                    <?php $roles = get_roles($connect); while($role = mysqli_fetch_array($roles)){ ?>
                    <option value="<?php echo $role['id'];?>"><?php echo $role['name'];?></option>
                  <?php } ?>
                  
                  </select>
                </div>
                </div>

           <?php } ?>

              <?php if (isset($_GET['user']) && $_SESSION['role'] ==1) { ?>
                <input type="hidden" name="id" value="<?php echo $_GET['user'];?>">
              <?php } else { ?>
              <input type="hidden" name="id" value="<?php echo $data['id'];?>">
            <?php } ?>

            <input type="hidden" name="update_profile" value="<?php echo $data['id'];?>">

              <div class="form-group">
                <div class="col-sm-12">
                  <button class="btn btn-primary">Update Profile</button>
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Delete Account</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Delete Popup -->
    <div class="modal fade" style="margin-top: 100px;" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteLabel">Delete Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete your account?
          </div>
          <div class="modal-footer">
            <a href="?del=<?php if(isset($_GET['user'])){echo $_GET['user'];} else {echo $_SESSION['id'];} ?>" class="btn btn-danger">Yes</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>


         <!-- Tab panes -->
          <div class="tab-pane" id="ch-pass" role="tabpanel">
            <div class="card-body">
              <form method="post">
             <div class="form-group">
                <label class="col-md-12">Old Password</label>
                <div class="col-md-12">
                  <input type="password" name="old-pass" class="form-control form-control-line" placeholder="Enter Old Password">
                </div>
              </div>
              <div class="form-group">
                <label for="new" class="col-md-12">New Password</label>
                <div class="col-md-12">
                 <input type="password" name="new-pass" class="form-control form-control-line" placeholder="Enter New Password">
              </div>
            </div>
             <div class="form-group">
                <label for="new" class="col-md-12">Confirm New Password</label>
                <div class="col-md-12">
                 <input type="password" name="confirm-new-pass" class="form-control form-control-line" placeholder="Enter New Password Again">
              </div>
            </div>
            <input type="hidden" name="update_password" value="<?php echo $data['id'];?>">
            <div class="form-group">
                <div class="col-sm-12">
                  <button class="btn btn-primary">Update Password</button>
                </div>
              </div>
            </form>

            </div>
          </div>

      </div>
    </div>
  </div>
  <!-- Column -->
</div>
<!-- Row -->

<script src="assets/dropify.min.js"></script>
<script>
  $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
          messages: {
            default: 'Glissez-déposez un fichier ici ou cliquez',
            replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
            remove: 'Supprimer',
            error: 'Désolé, le fichier trop volumineux'
          }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
          return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
          alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
          console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
          e.preventDefault();
          if (drDestroy.isDropified()) {
            drDestroy.destroy();
          } else {
            drDestroy.init();
          }
        })
      });
    </script>
    <?php
    include "inc/footer.php";
    ?>