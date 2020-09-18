<?php
include_once "inc/header.php";
if (empty($_SESSION['id'])) {
	header("Location: ./login.php");
}
?>

<!--View Project-->
<?php 

if (isset($_POST['id'])) {
 		$result = update_project($connect,$_POST['name'],$_POST['description'],$_POST['deadline'],$_POST['id']);
 		if ($result == "1") {
 			message("Project updated successfully!",1);
 		}
 	}

 if (isset($_GET['del'])) {
 	$result = delete_project($connect,$_GET['del']);
 	if ($result =="1") {
 		header("Location: ./projects.php");
 		message("Project deleted successfully!","1");
 	}
 }


if (isset($_GET['edit'])) {
	$data = get_project($connect,$_GET['edit']);
	?>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th scope="cols" colspan="3" class="p-0">
						<h5> <a href="project.php?id=<?php echo $data['id'];?>&ref=projects"><button class="btn btn-dark">← Back to project</button></a> </h5>
					</th>
				</tr>
			</thead>
			<form method="post">
			<tbody>
				<tr>
					<td>
						<div class="form-group">
							<label for="name">Project Name</label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name'];?>">
							<div class="form-group">
								<label for="description">Description</label>
								<textarea class="form-control" id="description" name="description" rows="10"><?php echo base64_decode($data['description']);?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="deadline">Deadline</label>
							<input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $data['deadline'];?>">
						</div>

						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data['id'];?>">

						<div class="form-group" align="right">
							<button class="btn btn-primary">Save Project</button> <a class="btn btn-dark" href="/project.php?id=<?php echo $data['id'];?>&ref=projects">Cancel</a>
						</div>
					</td>

				</tr>
			</tbody>
		</form>
		</table>

<?php } ?>


<?php 
if (!empty($_GET['id'])) {
	$data = get_project($connect,$_GET['id']);
	?>

	<!--View Project-->
	<table class="table table-bordered">
		<thead class="thead-dark">
			<tr>
				<th scope="cols" colspan="3" class="p-0">
					<h5> <a href="projects.php"><button class="btn btn-dark">← Back to projects </button></a> <?php echo $data['name']?></h5>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="40%">
					<div class="row">
						<div class="col-4 border-right border-bottom p-1">Deadline</div>
						<div class="col-8 border-bottom p-1"><?php echo date("Y-m-d",strtotime($data['deadline']));?></div>
						<div class="col-4 border-right border-bottom p-1">Creator</div>
						<div class="col-8 border-bottom p-1"><?php $user = get_user_data($connect,$data['creator']); echo $user['name']; ?></div>
						<div class="col-4 border-right p-1">Created On</div>
						<div class="col-8 p-1"><?php echo date("Y-m-d  h:i:sa",strtotime($data['created']));?></div>
					</div>
				</td>
				<td width="60%">
					<?php echo base64_decode($data['description']); ?>
				</td>

			</tr>
		</tbody>
	</table>


	<div id="result"></div>
	<div class="modal-footer">
		<a id="restart" href="#restart-<?php echo $data['id'];?>"><button class="btn btn-danger">Restart</button></a>
		<a id="start" href="#start-<?php echo $data['id'];?>"><button class="btn btn-primary">Start</button></a>
		<a id="finish" href="#finish-<?php echo $data['id'];?>"><button class="btn btn-primary">Finish</button></a>
		<a href="<?php echo $_GET['ref'].".php";?>"><button class="btn btn-dark">Back</button></a>
		<?php if ($_SESSION['role']==1) {?>
			<div class="dropdown">
				<button class="btn btn-light" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-ellipsis-h"></i>
				</button>
				<div class="dropdown-menu" aria-labelledby="option">
					<a class="dropdown-item" href="project.php?edit=<?php echo $data['id']?>">Edit</a>
					<a class="dropdown-item" href="#<?php echo $data['id'];?>" data-toggle="modal" data-target="#delete">Delete</a>
				</div>
			</div>
		<?php } ?>
	</div>


	<?php if ($_SESSION['role']==1) {?>
		<!-- Delete Popup -->
		<div class="modal fade" style="width: 30%; margin-left: 35%; margin-top: 150px;" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteLabel">Delete Task</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body text-center">
						Are you sure you want to delete?
					</div>
					<div class="modal-footer">
						<a href="status.php?project&delete=<?php echo $data['id'];?>&ref=projects"><button type="button" class="btn btn-danger">Yes</button></a>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<br/>

	<div class="table-responsive m-t-40">
		<table id="tasks" class="table table-hover">
			<thead class="bg-dark text-light">
				<tr>
					<th scope="col" class="d-none p-3">Default Sort Fixer</th>
					<th scope="col" class="p-3">Task Name</th>
					<th scope="col" class="p-3">Assigned To</th>
					<th scope="col" class="p-3">Creator</th>
					<th scope="col" class="p-3">Creation Date</th>
					<th scope="col" class="p-3">Deadline</th>
					<th scope="col" class="p-3">Word Count</th>
					<th scope="col" class="p-3">Priority</th>
					<th scope="col" class="p-3">Writing Status</th>
					<th scope="col" class="p-3">Publish Link</th>
					<th scope="col" class="p-3">Options</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$tasks = get_tasks_by_project($connect,$_GET['id']);
				if ($tasks->num_rows > 0) {
					while ($task = mysqli_fetch_array($tasks)) {
						?>
						<tr>
							<td scope="row" class="d-none"><?php echo $data['deadline'];?></td>
							<td><a class="<?php if($task['status']==2){echo"text-warning";} elseif($task['status']==3){echo "text-success";} elseif($task['status']==4){echo "del text-dark";}?>" href="task.php?id=<?php echo $task['id']?>&ref=tasks"><?php echo $task['name'];?> </a></td>
							<td>
								<?php $user = get_user_data($connect,$task['assignee']);
								echo $user['name']; 
								?>
							</td>
							<td>
								<?php $user = get_user_data($connect,$task['creator']);
								echo $user['name'];
								?>
							</td>
							<td>
								<?php echo date("Y-m-d",strtotime($task['created']));?>
							</td>
							<td>
								<?php echo date("Y-m-d",strtotime($task['deadline']));?>
							</td>
							<td>
								<?php echo $task['words']?>
							</td>
							<td>
								<?php if ($task['priority']==1) {
									echo '<i class="text-danger fa fa-fire" data-toggle="tooltip" data-placement="top" title="High"></i>';
								} else {
									echo '<i class="fa fa-circle" data-toggle="tooltip" data-placement="top" title="Low"></i>';
								}?>
							</td>
							<td>
								<?php if ($task['status']==0) {
									echo 'Not started';
								} elseif ($task['status']==1) {
									echo 'Task started';
								} elseif ($task['status']==2) {
									echo 'Pending for review';
								} elseif ($task['status']==3) {
									echo 'Completed';
								} elseif ($task['status']==4) {
									echo 'Task paid';
								}?>
							</td>
							<td>
								<?php if ($task['publish_status']==2) {
									echo '<a href="'.$task['publish_link'].'" target="_blank">Link</a>';
								} elseif($task['publish_status']==1) {
									echo 'Publishing started';
								} else {
									echo 'Not started';
								}?>
							</td>
							<td align="center">
								<div class="dropdown">
									<button class="btn btn-light" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></button>

									<div class="dropdown-menu" aria-labelledby="option">
										<a class="dropdown-item" href="task.php?ref=projects&id=<?php echo $task['id'];?>">View</a>
										<?php if ($_SESSION['role'] ==1 || $task['creator']==$_SESSION['id']) {?>
											<a class="dropdown-item" href="task.php?edit&ref=projects&id=<?php echo $task['id'];?>">Edit</a>
										<?php } ?>
									</div>

								</div>
							</td>
						</tr>

						<?php

					}
				}
				?>
			</tbody>
		</table>
	</div>
	<br>



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
		var id = <?php echo $data['id'];?>;

		$("#start").click(function(){
			$("#start").css("opacity:0.5");
			$.ajax({url: "status.php?project&start="+id, success: function(result){
				if (result==1) {
					$("#start").hide();
				}
			}});
		});

		$("#finish").click(function(){
			$("#finish").css("opacity:0.5");
			$.ajax({url: "status.php?project&finish="+id, success: function(result){
				if (result==1) {
					$("#finish").hide();
					$("#start").hide();
					$("#restart").show();
				}
			}});
		});

		$("#restart").click(function(){
			$("#restart").css("opacity:0.5");
			$.ajax({url: "status.php?project&restart="+id, success: function(result){
				if (result==1) {
					$("#restart").hide();
					$("#start").show();
					$("#finish").show();
				}
			}});
		});
	</script>
	<script type="text/javascript">
		var status = <?php echo $data['status']?>;
		$(document).ready(function(){
			if (status==2) {
				$("#finish").hide();
				$("#start").hide();
				$("#restart").show();
			}
		});

		var status = <?php echo $data['status']?>;
		$(document).ready(function(){
			if (status==0) {
				$("#restart").hide();
			}
		});

		var status = <?php echo $data['status']?>;
		$(document).ready(function(){
			if (status==1) {
				$("#start").hide();
				$("#restart").hide();
			}
		});

	</script>

<?php  } 


include_once "inc/footer.php";
?>