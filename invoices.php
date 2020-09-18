 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
 	header("Location: ./login.php");
 }

 if (isset($_GET['del'])) {
 	$invoice_id = "VXARI".str_pad($_GET['del'], 6, '0', STR_PAD_LEFT);
 	$result = delete_invoice($connect,$_GET['del']);
 	if ($result ==1) {
 		message("Invoice has been deleted successfully!",1);
 	}
 	$tasks = get_tasks_by_invoice($connect,$invoice_id);
 	if ($tasks->num_rows>0) {
 		while ($task = mysqli_fetch_array($tasks)) {
 			$update = update_task_payment($connect,'',3,$task['id']);
 		}
 	}
 }
 ?>

 <?php if ($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
 	<form method="post" action="invoices.php">
 		<div class="form-group">
 			<label for="Select User">Generate Invoice</label>
 			<select name="user" id="Select User" class="form-control">
 				<option value="0" selected>Choose...</option>
 				<?php $users = get_users($connect); while ($user = mysqli_fetch_array($users)) { 
 					if ($user['role'] !=1) {
 						?>
 						<option value="<?php echo $user['id'];?>"><?php echo $user['name'];?></option>
 					<?php } } ?>
 				</select>
 			</div>
 			<div class="form-group">
 				<button class="btn btn-outline-primary">Select</button> <a class="btn btn-outline-dark" href="invoices.php">Refresh</a>
 			</div>
 		</form>
 	<?php } ?>

 	<?php if (!isset($_POST['user'])) {?>
 		<!--Invoices-->
 		<div class="table-responsive m-t-40">
 			<table id="invoices" class="table table-hover">
 				<thead>
 					<tr align="center">
 						<th scope="col" class="d-none">Order Fix Default</th>
 						<th scope="col">ID</th>
 						<th scope="col">Invoice Name</th>
 						<th scope="col">Paid To</th>
 						<th scope="col">Status</th>
 						<th scope="col">Word Count</th>
 						<th scope="col">Bill</th>
 						<?php if ($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
 							<th scope="col" align="center">Option</th>
 						<?php } ?>
 					</tr>
 				</thead>
 				<tbody>
 					<?php
 					if ($_SESSION['role']==1 || $_SESSION['role']==2) {
 						$result = get_invoices($connect);
 					} else {
 						$result = get_invoices_by_user($connect,$_SESSION['id']);
 					}
 					if ($result->num_rows > 0) {
 						while ($data = mysqli_fetch_array($result)) { ?>
 							<tr>
 								<td class="d-none"><?php echo $data['name'];?></td>
 								<td>#<?php echo "VXARI".str_pad($data['id'], 6, '0', STR_PAD_LEFT); ?></td>
 								<td><a target="_blank" href="generate.php?invoice=<?php echo $data['id'];?>"><?php echo $data['name'];?></a></td>

 								<td><?php $user = get_user_data($connect,$data['paid_to']); echo $user['name']; ?></td>

 								<td><?php if($data['status']==1){echo " <span class='badge badge-success'>Paid";} else {echo " <span class='badge badge-danger'>Unpaid";}?></span></td>

 								<td><?php echo $data['words']; ?> words</td>

 								<td><?php echo number_format($data['paid'],2); ?> /=</td>

 								<td align="center">
 									<div class="dropdown">
 										<button class="btn btn-light" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></button>

 										<div class="dropdown-menu" aria-labelledby="option">
 											<a class="dropdown-item" id="v-<?php echo $data['id'];?>" href="invoice.php?id=<?php echo $data['id'];?>">View</a>
 											<?php if ($_SESSION['role'] ==1) {?>
 												<a class="dropdown-item" data-toggle="modal" data-target="#delete" href="#delete-<?php echo $data['id'];?>">Delete</a>
 											<?php } ?>
 										</div>

 									</div>
 									<?php if ($_SESSION['role']==1) { ?>
 										<!-- Delete Invoice Popup -->
 										<div style="margin-top: 200px;width: 30%;margin-left: 35%;margin-right: 35%;" class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
 											<div class="modal-dialog" role="document">
 												<div class="modal-content">
 													<div class="modal-header">
 														<h5 class="modal-title" id="deleteLabel">Delete Task</h5>
 														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 															<span aria-hidden="true">&times;</span>
 														</button>
 													</div>
 													<div class="modal-body">
 														Are you sure you want to delete?
 													</div>
 													<div class="modal-footer">
 														<a href="?del=<?php echo $data['id'];?>"><button type="button" class="btn btn-danger">Yes</button></a>
 														<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
 													</div>
 												</div>
 											</div>
 										</div>
 									<?php } ?>

 									</td>
 								</tr>

 								<?php
 							}
 						}
 						?>
 					</tbody>
 				</table>
 			</div>
 		<?php } ?>



 		<?php 
 		if (isset($_POST['user'])) { $data = get_user_data($connect,$_POST['user']);?>

 		<div class="card">
 			<div class="card-header text-center"><h4 class="card-title">Invoice for <?php echo $data['name'];?></h4></div>
 			<div class="row no-gutters">
 				<div class="col-md-4">
 					<img src="<?php if($data['picture'] ==''){ echo 'https://www.drsubhashtech.edu.in/img/faculty/user.png';} else{ echo $data['picture'];}?>" class="card-img" alt="<?php echo $data['name'];?>" height="400px">
 				</div>
 				<div class="col-md-8">
 					<div class="card-body">
 						<ul class="list-group list-group-flush">
 							<li class="list-group-item text-light bg-dark">Unpaid Articles</li>
 							<?php $tasks = get_tasks_by_assignee($connect,$data['id'],3);
 							$total = 0;
 							$words = 0;
 							if (!empty($data['name'])){
 								while ($task = mysqli_fetch_array($tasks)) { 
 									$total += ($task['rate']/1000)*$task['words'];
 									$words += $task['words'];
 									?>
 									<li class="list-group-item"><div class="row"><div class="col-9"><?php echo $task['name'];?></div> <div class="col-3" align="center"><span class="badge badge-primary badge-pill"><?php echo number_format(($task['rate']/1000)*$task['words'],2);?> BDT</span></div></div></li>
 								<?php } } ?>
 							</ul>
 						</div>
 					</div>
 				</div>
 				<div class="card-footer"><div class="row"><div class="col-10" align="right">Total Payment </div><div class="col-2"><b>= <?php echo number_format($total,2);?> BDT</b></div></div></div>
 			</div>
 			<br>
 			<div align="right">
 				<?php if (!empty($data['name'])) { ?>
 					<a href="generate.php?user=<?php echo $_POST['user'];?>&paid=<?php echo $total;?>&words=<?php echo $words;?>" class="btn btn-outline-primary btn-md">Generate</a> 
 				<?php } ?>
 				<a href="invoice.php" class="btn btn-outline-dark btn-md">Cancel</a>
 			</div>

 		<?php } ?>

<script src="assets/datatables.min.js"></script>
		<script>
			$(function() {
				$('#invoices').DataTable();
				$(function() {
					var table = $('#example').DataTable({
						"columnDefs": [{
							"visible": false,
							"targets": 2
						}],
						"order": [
						[2, 'asc']
						],
						"displayLength": 25,
						"drawCallback": function(settings) {
							var api = this.api();
							var rows = api.rows({
								page: 'current'
							}).nodes();
							var last = null;
							api.column(2, {
								page: 'current'
							}).data().each(function(group, i) {
								if (last !== group) {
									$(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
									last = group;
								}
							});
						}
					});
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
            	var currentOrder = table.order()[0];
            	if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            		table.order([2, 'desc']).draw();
            	} else {
            		table.order([2, 'asc']).draw();
            	}
            });
        });
			});
		</script>
		<script type="text/javascript">
			$("#assign_to").on("change",function(){
				var user = $("#assign_to").val();
				$.ajax({url: "status.php?user&rate="+user, success: function(result){
					$("#user_rate").empty();
					$("#user_rate").append(' <b>('+result+' BDT)</b>');
				}});
			});
		</script>

 		<?php
 		include "inc/footer.php";
 		?>