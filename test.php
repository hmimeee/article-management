 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
  header("Location: ./login.php");
}

?>

  <input type="checkbox" id="box-1"><label for="box-1">Publishing Required</label>

<?php
include "inc/footer.php";
?>