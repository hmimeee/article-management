 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
 	header("Location: ./login.php");
 }

 if (isset($_GET['del'])) {
 	$result = delete_project($connect,$_GET['del']);
 	if ($result =="1") {
 		message("Project deleted successfully!","1");
 	}
 }

 if ($_SERVER['REQUEST_METHOD'] =="POST") {
 	
 	if (isset($_POST['create'])) {
 		$result = create_project($connect,$_POST['name'],$_POST['description'],$_SESSION['id'],$_POST['deadline'],$_POST['created']);
 		if ($result == 1) {
 			message("Project created successfully!",1);
 		} else {
 			message("Could not create project!",0);
 		}
 	}
 }
 ?>


 <!-- Create task button -->
 <button type="button" class="btn btn-outline-dark btn-md" data-toggle="modal" data-target="#create-project">
 	Create New Project
 </button>
 <a href="./projects.php"><button type="button" class="btn btn-outline-dark btn-md">
 	Refresh
 </button>
</a>

<br/>
<br/>
<!-- Create New Projects -->
<div class="modal fade" id="create-project" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="create-project-label" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" action="projects.php">
				<div class="modal-header">
					<h5 class="modal-title" id="create-project-label">Create Project</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Project Name</label>
						<input type="text" class="form-control" id="name" name="name">
						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="deadline">Deadline</label>
						<input type="date" class="form-control" id="deadline" name="deadline">
					</div>
				</div>
				<input type="hidden" name="created" value="<?php echo date("Y-m-d"); ?>"/>
				<input type="hidden" name="create" value="create"/>
				<div class="modal-footer">
					<button class="btn btn-primary">Save</button>
					<button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!--Projects-->
<div class="table-responsive m-t-40">
	<table id="projects" class="table table-hover">
		<thead>
			<tr>
				<th scope="col" class="d-none">Default Sort Fixer</th>
				<th scope="col">ID</th>
				<th scope="col">Name</th>
				<th scope="col">Creator</th>
				<th scope="col">Deadline</th>
				<th scope="col">Created</th>
				<th scope="col" align="center">Option</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$result = get_projects($connect);
			if ($result->num_rows>0) {
			while ($data = mysqli_fetch_array($result)) {
				?>
				<tr>
					<td scope="row" class="d-none"><?php echo date("Y-m-d",strtotime($data['deadline']));?></td>
					<td><?php echo $data['id']?></td>
					<td><a href="project.php?id=<?php echo $data['id']?>&ref=projects"><?php echo $data['name']?></a></td>
					<td><?php
					$user = get_user_data($connect,$data['creator']);
					echo $user['name'];
					?>
				</td>
				<td><?php echo date("Y-m-d",strtotime($data['deadline']));?></td>
				<td><?php echo date("Y-m-d",strtotime($data['created']));?></td>
				<td align="center"><div class="dropdown">
					<button class="btn btn-light" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-ellipsis-h"></i>
					</button>
					<div class="dropdown-menu" aria-labelledby="option">
						<a class="dropdown-item" href="project.php?id=<?php echo $data['id']?>">View</a>
						<a class="dropdown-item" href="project.php?edit=<?php echo $data['id']?>">Edit</a>
						<?php if ($_SESSION['role']==1) {?><a class="dropdown-item" href="#<?php echo $data['id'];?>" data-toggle="modal" data-target="#delete-<?php echo $data['id'];?>">Delete</a><?php } ?>
					</div>
				</div>
			</td>
		</tr>

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

		<?php
	}
}
	?>
</tbody>
</table>
</div>

<script src="assets/datatables.min.js"></script>
<script>
	$(function() {
		$('#projects').DataTable();
		$(function() {
			var table = $('#example').DataTable({
				"columnDefs": [{
					"visible": false,
					"targets": 2
				}],
				"ordering": false,
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
	$('#example23').DataTable({
		dom: 'Bfrtip',
		buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});
	$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
</script>

<?php
include "inc/footer.php";
?>