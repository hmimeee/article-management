 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
 	header("Location: ./login.php");
 }
 if ($_SERVER['REQUEST_METHOD'] =="POST") {
 	if (isset($_POST['create'])) {
 		$description = $_POST['description'];
 		$assignee = $_POST['assignee'];
 		$creator = $_POST['creator'];
 		$rate = $_POST['rate'];
 		$project = $_POST['project'];
 		$deadline = $_POST['deadline'];
 		$created = $_POST['created'];
 		$i=1;
 		foreach ($_POST as $key => $title) { 
 			if (0 === strpos($key, 'title_')) { 
 				$words = $_POST['words_'.$i];
 				if (isset($_POST['publishing_'.$i])) {
 					$publishing = $_POST['publishing_'.$i];
 				} else {
 					$publishing = 0;
 				}
 				if (isset($_POST['priority_'.$i])) {
 					$priority = $_POST['priority_'.$i];
 				} else {
 					$priority = 0;
 				}
 				$type = $_POST['type_'.$i];
 				$result = create_task($connect,$title,$description,$assignee,$creator,$words,$rate,$type,$project,$deadline,$publishing,$priority);
 				$i++;
 			} 
 		}
 		if ($result ==1) {
 			message("Task created successfully!",1);
 		} else {
 			message("Couldn't create the task!",0);
 		}
 	}

 	if (isset($_POST['batch'])) {
 		delete_tasks($connect,$_POST);
 	}
 }


 if (isset($_GET['del'])) {
 	$result = delete_task($connect,$_GET['del']);
 	if ($result ==1) {
 		message("Task deleted successfully!",1);
 	}
 }
 ?>


 <div class="row">
 	<div class="col-4">
 		<!-- Buttons -->
 		<?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {?>
 			<button type="button" class="btn btn-outline-dark btn-md" data-toggle="modal" data-target="#create-task">
 				Create New Task
 			</button>
 		<?php } ?>
 		<a href="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];?>"><button type="button" class="btn btn-outline-dark btn-md">
 			Refresh
 		</button>
 	</a>
 </div>
 <div class="col-8" align="right">
 	<?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2 || $_SESSION['role'] == 4) {?>
 		<a class="btn btn-outline-dark btn-md <?php if(!isset($_GET['assignee']) && !isset($_GET['creator']) && !isset($_GET['status'])){echo "active";} ?>" href="<?php echo $_SERVER['PHP_SELF'];?>">All Tasks</a>
 	<?php } ?>

 	<?php if ($_SESSION['role'] != 4) {?>
 		<a class="btn btn-outline-dark btn-md <?php if(isset($_GET['assignee'])){if($_GET['assignee']=="me"){echo "active";}} ?>" href="?assignee=me">My Tasks <span class="badge badge-primary badge-pill"><?php echo get_tasks_by_assignee($connect,$_SESSION['id'],0)->num_rows; ?></span></a>

 		<?php if ($_SESSION['role'] !=3) {?>
 			<a class="btn btn-outline-dark btn-md <?php if(isset($_GET['creator'])){if($_GET['creator']=="me"){echo "active";}} ?>" class="nav-link" href="?creator=me">Assigned Tasks <span class="badge badge-primary badge-pill"><?php echo get_tasks_by_creator($connect,$_SESSION['id'],0)->num_rows; ?></span></a>
 			<a class="btn btn-outline-dark btn-md <?php if(isset($_GET['status'])){if($_GET['status']==2){echo "active";}} ?>" href="?status=2">Peding Aproval <span class="badge badge-primary badge-pill"><?php echo get_tasks_by_creator($connect,$_SESSION['id'],2)->num_rows; ?></span></a>
 			<a class="btn btn-outline-dark btn-md <?php if(isset($_GET['status'])){if($_GET['status']==4){echo "active";}} ?>" href="?status=4">Paid Tasks <span class="badge badge-primary badge-pill"><?php echo get_tasks_by_creator($connect,$_SESSION['id'],4)->num_rows; ?></span></a>
 		<?php } ?>

 		<?php if ($_SESSION['role'] == 3) {?>
 			<a class="btn btn-outline-dark btn-md <?php if(isset($_GET['status'])){if($_GET['status']==3){echo "active";}} ?>" href="?status=3">Unpaid Tasks <span class="badge badge-primary badge-pill"><?php echo get_tasks_by_assignee($connect,$_SESSION['id'],3)->num_rows; ?></span></a>
 		<?php }?>
 	<?php } ?>
 </div>
</div>
<br/>
<br/>

<!-- Create Task -->
<?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {?>
	<form method="post">
		<div class="modal fade" id="create-task" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="create-task-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="create-task-label">Create Task</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<div id="title-area"></div>
							<div class="row">
								<div class="col-4">
									<input id="my_input" name="input_count" type="number" value="1" class="form-control">
								</div>
								<div class="col-4">
									<button class="btn btn-md btn-outline-dark" data-toggle="modal" data-target="#myModal" id="input-button" name="" type="button">Extend Title</button>
								</div>
							</div>	

							<script type="text/javascript">
								function add_inputboxes() {
									n = $('#my_input').val();
									if (n < 1)
										alert("ERROR: Enter a positive number");
									else {
										$('#my_input').hide();
										$('#input-button').hide();
										$("#title-area").html('');
										for (var i = 1; i <= n; i++) {
											$("#title-area").append('<div class="row mt-2"><div class="col-1 pub"><input type="checkbox" id="priority_' + i + '" name="priority_' + i + '" value="1"><label for="priority_' + i + '"><i class="text-danger fa fa-fire" data-toggle="tooltip" data-placement="top" title="High"></i></label></div><div class="col-5"><input id="rolo_add' + i + '" name="title_' + i + '"  type="text" class="form-control" value="" placeholder="Title ' + i + '" required/></div><div class="col-2"><input type="number" class="form-control" id="words_' + i + '" name="words_' + i + '" placeholder="Words ' + i + '" required></div><div class="col-2 pub"><input type="checkbox" id="publishing_' + i + '" name="publishing_' + i + '" value="1"><label for="publishing_' + i + '">Publishing Required</label></div><div class="col-2"><select id="type_' + i + '" class="form-control" name="type_' + i + '"><option value="" selected>Choose Type...</option><option value="Review Article">Review Article</option><option value="Info Article">Info Article</option><option value="Blog Article">Blog Article</option><option value="Web 2.0 Article">Web 2.0 Article</option><option value="Page Content">Page Content</option><option value="Guest Post">Guest Post</option></select></div></div>');
										}
									}
								}
								jQuery('#input-button').click(add_inputboxes);

							</script>
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description"></textarea>
						</div>

						<div class="form-group">
							<label for="asignee">Assign To</label>
							<select class="form-control" name="assignee" id="assign_to">
								<?php
								$result = get_users($connect);
								while ($data = mysqli_fetch_array($result)) {
									if ($data['role'] !=1 && $data['role'] !=2 && $data['role'] !=4) {
										echo "<option value='".$data['id']."'>".$data['name']."</option>";
									}
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label for="rate">Rate Per 1K</label><span id="user_rate"></span>
							<input type="number" class="form-control" id="rate" name="rate">
						</div>

						<div class="form-group">
							<label for="deadline">Deadline</label>
							<input type="date" class="form-control" id="deadline" name="deadline">
						</div>

						<div class="form-group">
							<label for="project">Project</label>
							<select id="project" class="form-control" name="project">
								<option value="" selected>Choose...</option>
								<?php
								$result = get_projects($connect);
								while ($data = mysqli_fetch_array($result)) {
									echo "<option value='".$data['id']."'>".$data['name']."</option>";
								}
								?>
							</select>
						</div>

						<input type="hidden" name="creator" value="<?php echo $_SESSION['id']; ?>">
						<input type="hidden" name="created" value="<?php echo date("Y-m-d"); ?>">
						<input type="hidden" name="create" value="True">
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php } ?>

<!--Tasks-->
<div class="table-responsive m-t-40">
	<form method="post">
		<table id="tasks" class="table table-hover">
			<thead>
				<tr>
					<th scope="col" class="d-none">Default Sort Fixer</th>
					<?php if ($_SESSION['role']==1 || $_SESSION['role']==2) {?>
						<th scope="col">Select</th>
					<?php } ?>

					<th scope="col">Project</th>

					<th scope="col">Article Title</th>
					<?php if ($_SESSION['role'] !=4) {?>
						<th scope="col">Word Count</th>
						<th scope="col">Assigned To</th>
					<?php } else { ?>
						<th scope="col">Publisher</th>
					<?php } ?>
					<?php if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {?>
						<th scope="col">Publisher</th>
					<?php } ?>
					<th scope="col">Deadline</th>
					<th scope="col">Priority</th>
					<th scope="col" align="center">Option</th>
				</tr>
			</thead>
			<tbody>
				<?php 
			//Status Control
				if (isset($_GET['status'])) {
					$status = $_GET['status'];
				} else {
					$status = 0;
				}


			//Task By User Control
				if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {
					if (isset($_GET['creator'])) {
						$result = get_tasks_by_creator($connect,$_SESSION['id'],$status);
					} elseif(isset($_GET['assignee'])) {
						$result = get_tasks_by_assignee($connect,$_SESSION['id'],$status);
					} elseif(isset($_GET['status'])) {
						if ($_GET['status']==2) {
							$result = get_tasks_by_creator($connect,$_SESSION['id'],2);
						} else {
							$result = get_tasks($connect,$status);
						} 
					} else {
						$result = get_tasks($connect,$status);
					} 
				} elseif($_SESSION['role'] ==4){
					$result = get_tasks_by_publisher($connect,$_SESSION['id'],$status);
				} elseif($_SESSION['role'] ==3) {

					$result = get_tasks_by_assignee($connect,$_SESSION['id'],$status);
				}

				if ($result->num_rows > 0) {
					while ($data = mysqli_fetch_array($result)) { ?>
						<tr class="">
							<td scope="row" class="d-none"></td>

							<?php if ($_SESSION['role']==1 || $_SESSION['role']==2) {?>
								<td class="pub"><input type="checkbox" name="id_<?php echo $data['id'];?>" id="id_<?php echo $data['id'];?>" value="<?php echo $data['id'];?>"/> <label style="margin-top: 10px;" for="id_<?php echo $data['id'];?>"></label></td>
							<?php } ?>

							<?php if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {?>
								<td><a class="<?php if($_SESSION['role'] !=4){if($data['status']==2){echo"text-warning";} elseif($data['status']==3){echo "text-success";} elseif($data['status']==4){echo "del text-dark";}}elseif($_SESSION['role'] ==4){if($data['publish_status']==1){echo"text-warning";}}?>" href="./project.php?id=<?php echo $data['project'];?>&ref=tasks"><?php $project = get_project_by_id($connect,$data['project']);
								if (!empty($project['name'])) {
									echo $project['name']; } ?></a></td>
								<?php } else {?>
									<td><?php $project = get_project_by_id($connect,$data['project']);
									if (!empty($project['name'])) {
										echo $project['name']; } ?></td>
									<?php } ?>

									<td><a class="<?php if($_SESSION['role'] !=4){if($data['status']==2){echo"text-warning";} elseif($data['status']==3){echo "text-success";} elseif($data['status']==4){echo "del text-dark";}}elseif($_SESSION['role'] ==4){if($data['publish_status']==1){echo"text-warning";}}?>" href="task.php?id=<?php echo $data['id']?>&ref=tasks"><?php echo $data['name'];?> </a></td>

									<?php if ($_SESSION['role'] !=4) {?>
										<td><?php echo $data['words']; ?></td>

										<td><?php $user = get_user_data($connect,$data['assignee']); echo $user['name']; ?></td>

									<?php } else { ?>
										<td><?php $user = get_user_data($connect,$data['publisher']); echo $user['name']; ?></td>
									<?php } ?>

									<?php if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {?>
										<td><?php $user = get_user_data($connect,$data['publisher']); echo $user['name']; ?></td>
									<?php } ?>

									<td><?php echo $data['deadline']; ?></td>

									<td align="center"><?php if ($data['priority']==1) {
										echo '<i class="text-danger fa fa-fire" data-toggle="tooltip" data-placement="top" title="High"></i>';
									} else {
										echo '<i class="fa fa-circle" data-toggle="tooltip" data-placement="top" title="Low"></i>';
									}?></td>

									<td align="center">
										<div class="dropdown">
											<button class="btn btn-light" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></button>

											<div class="dropdown-menu" aria-labelledby="option">
												<a class="dropdown-item" id="v-<?php echo $data['id'];?>" href="task.php?id=<?php echo $data['id'];?>">View</a>
												<?php if ($_SESSION['role'] ==1 || $data['creator']==$_SESSION['id']) {?>
													<a class="dropdown-item" href="task.php?edit&ref=tasks&id=<?php echo $data['id'];?>">Edit</a>
													<a class="dropdown-item" href="#<?php echo $data['id'];?>" data-toggle="modal" data-target="#delete-<?php echo $data['id'];?>">Delete</a>
												<?php } ?>
											</div>

										</div>
									</td>

									<!-- Delete Popup -->
									<div style="margin-top: 200px;width: 30%;margin-left: 35%;margin-right: 35%;" class="modal fade" id="delete-<?php echo $data['id'];?>" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
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

								</tr>



								<?php
							}
						}
						?>
					</tbody>
				</table>
				<?php if ($_SESSION['role']==1 || $_SESSION['role']==2) {?>
					<input type='hidden' name='batch' value='del'/><button class='btn btn-outline-danger'>Delete</button>
				<?php } ?>
			</form>
		</div>


		<script src="assets/datatables.min.js"></script>
		<script>
			$(function() {
				$('#tasks').DataTable();
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
			$("#assignee").on("change",function(){
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