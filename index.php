<?php
include "inc/header.php";
if (empty($_SESSION['id'])) {
    header("Location: ./login.php");
} elseif ($_SESSION['role'] ==3 || $_SESSION['role'] ==4){
    echo '<script>window.location.replace("./tasks.php");</script>';
}
?>

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Info box -->
        <!-- ============================================================== -->
        <div class="card-group">
            <div class="card">
                <div class="card-body">
                    <a href="users.php" class="text-decoration-none">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div align="center">
                                        <h2><i class="fa fa-user"></i></h2>
                                        <h5 class="text-dark">All Users</h5>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-primary"><?php echo get_users($connect)->num_rows;?></h2>
                                    </div>
                                </div>
                                <div class="progress">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <a href="projects.php" class="text-decoration-none">
                        <div class="row">
                           <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div align="center">
                                    <h2><i class="fa fa-sitemap"></i></h2>
                                    <h5 class="text-dark">All Projects</h5>
                                </div>
                                <div class="ml-auto">
                                    <h2 class="counter text-primary"><?php echo get_projects($connect)->num_rows;?></h2>
                                </div>
                            </div>
                            <div class="progress">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <a href="tasks.php?status=4" class="text-decoration-none">
                    <div class="row">
                     <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div align="center">
                                <h2><i class="fa fa-tasks"></i></h2>
                                <h5 class="text-dark">Paid Tasks</h5>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-primary"><?php echo get_tasks($connect,4)->num_rows;?></h2>
                            </div>
                        </div>
                        <div class="progress">
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="card">
        <div class="card-body">
            <a href="invoices.php" class="text-decoration-none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div align="center">
                                <h2><i class="fa fa-file"></i></h2>
                                <h5 class="text-dark">All Invoices</h5>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-primary"><?php echo get_invoices($connect)->num_rows;?></h2>
                            </div>
                        </div>
                        <div class="progress">
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Info box -->
<!-- ============================================================== -->
<br>
<!-- ============================================================== -->
<div class="row">
    <!-- ============================================================== -->
    <!-- Comment widgets -->
    <!-- ============================================================== -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent Comments</h5>
            </div>
            <!-- ============================================================== -->
            <!-- Comment widgets -->
            <!-- ============================================================== -->
            <div class="comment-widgets">
                <?php $comments = get_all_comments($connect);
                while($comment = mysqli_fetch_array($comments)){?>
                    <!-- Comment Row -->
                    <div class="d-flex no-block comment-row border-top">
                        <div class="p-2"><span class="round"><img src="<?php echo get_user_data($connect,$comment['creator'])['picture'];?>" alt="user" width="50"></span></div>
                        <div class="comment-text w-100">
                            <h5 class="font-medium"><?php echo get_user_data($connect,$comment['creator'])['name'];?></h5>
                            <p class="m-b-10 text-muted"><?php echo base64_decode($comment['comment']);?></p>
                            <div class="comment-footer">
                                Attachments:<br>
                                <?php $i = 0; $files = explode(',',$comment['file']); foreach($files as $file){ echo '<a href="uploads/'.$file.'"><span class="badge badge-pill badge-info">'.$file.'</span></a><br/>'; $i++;}?>
                                <span class="text-muted pull-right"><?php echo date("Y-m-d h:i:sa",strtotime($comment['creation_date']));?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Table -->
    <!-- ============================================================== -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-6">
                        <h3><?php echo date('M, Y');?></h3>
                        <h5 class="font-light m-t-0">Report of this month payments</h5></div>
                        <div class="col-6 align-self-center display-6 text-right">
                            <h2 class="text-success">
                                <?php 
                                $invoices = get_invoices_report($connect,date('Y-m')); 
                                $paid = 0; 
                                while ($invoice = mysqli_fetch_array($invoices)) {
                                    $paid += $invoice['paid'];
                                } 
                                echo number_format($paid,2);
                                ?> BDT</h2></div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover no-wrap" id="users">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>NAME</th>
                                        <th>ROLE</th>
                                        <th>DATE</th>
                                        <th>EARNED</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $users = get_users($connect);
                                    while ($user = mysqli_fetch_array($users)) {?>
                                        <tr>
                                            <td class="text-center"><?php echo $user['id'];?></td>
                                            <td class="txt-oflo"><?php echo $user['name'];?></td>
                                            <td><span class="badge <?php if($user['role']==1){echo "badge-success";} elseif($user['role']==2){ echo "badge-warning";} elseif($user['role']==3){ echo "badge-dark";} elseif($user['role']==4){ echo "badge-info";}?> badge-pill"><?php echo get_role_name($connect,$user['role']);?></span> </td>
                                            <td class="txt-oflo"><?php echo date("Y-m-d",strtotime($user['join_date']));?></td>
                                            <td><span class="text-success">
                                                <?php 
                                                $invoices = get_invoices_by_user($connect,$user['id'],1);
                                                $paid = 0;
                                                while ($invoice = mysqli_fetch_array($invoices)) {
                                                    $paid += $invoice['paid'];
                                                }
                                                echo number_format($paid,2);
                                                ?> BDT</span></td>
                                            </tr>
                                        <?php } ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->


                <script src="assets/datatables.min.js"></script>
                <script>
                    $(function() {
                        $('#users').DataTable();
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

                <?php
                include "inc/footer.php";
                ?>