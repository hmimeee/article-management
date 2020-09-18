 <?php
 include "inc/header.php";
 if (empty($_SESSION['id'])) {
 	header("Location: ./login.php");
 }

 	if (isset($_GET['user'])) {
 		$tasks = get_tasks_by_assignee($connect,$_GET['user'],3);
 		if ($tasks->num_rows>0) {

 			$user = get_user_data($connect,$_GET['user']);
 			$invoice_name = "Invoice_".date("Y-m-d")."_".$_GET['user']."(".get_user_data($connect,$_GET['user'])['name'].")";
 			$create_invoice = create_invoice($connect,$invoice_name,$_GET['paid'],$_GET['words'],$_GET['user']);
 			if ($create_invoice ==1) {
 				$invoice = get_invoice_by_name($connect,$invoice_name);
 				if ($invoice !=0) {
 					$invoice_id = "VXARI".str_pad($invoice['id'], 6, '0', STR_PAD_LEFT);
 					$tasks = get_tasks_by_assignee($connect,$_GET['user'],3);
 					while ($change = mysqli_fetch_array($tasks)) {
 						$update = update_task_payment($connect,$invoice_id,4,$change['id']);
 					}

 				} else {
 					message("Could not generate invoice!",0);
 				}

 			} else {
 				message("Invoice for <b>".get_user_data($connect,$_GET['user'])['name']."</b> has already generated today!",0);
 			}

 			if ($create_invoice ==1) {
 				?>

 					<div class="row" id="invoice">
 						<div class="col-1"></div>
 						<div class="col-3">
 							<img src="https://viserx.com/wp-content/uploads/2019/04/logo-e1555414846101.png" height="100px">
 						</div>
 						<div class="col-3 text-center">
 							<u><h4>PAYSLIP</h4></u>
 						</div>
 						<div class="col-2 text-center">
 							Payslip No:
 							<br>
 							Payment Date :
 							<br>
 							Payment Status:
 						</div>
 						<div class="col-2 text-center">
 							<div class="border"><b><?php echo $invoice_id;?></b></div>
 							<div class="text-danger"><b><?php echo date("Y-m-d");?></b></div>
 							<div><b><div class='border border-danger text-danger'>Unpaid</div></b></div>
 						</div>
 						<div class="col-1"></div>


 						<div class="col-1"></div>
 						<div class="col-3">
 							House: 60, Road: 20, Uttara Model
 							<br>
 							Town, Dhaka - 1230
 						</div>
 						<div class="col-3 text-center">
 							<?php $user = get_user_data($connect,$_GET['user']);?>
 							Payment To:
 							<br>
 							E-mail:
 							<br>
 							Contact No. :
 							<br>
 							Payment Method:
 							<br>
 							Payment Account
 						</div>
 						<div class="col-2 text-left">
 							<b><?php echo $user['name'];?></b>
 							<br>
 							<?php echo $user['email'];?>
 							<br>
 							<?php echo $user['phone'];?>
 							<br>
 							<?php echo $user['payment_method'];?>
 							<br>
 							<?php echo $user['payment_account'];?>
 						</div>
 						<div class="col-3"></div>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>

 						<div class="col-1"></div>
 						<div class="col-10">
 							<table class="table table-sm table-bordered">
 								<thead>
 									<tr align="center">
 										<th scope="col">ID</th>
 										<th scope="col">Title</th>
 										<th scope="col">Words</th>
 										<th scope="col">Bill</th>
 									</tr>
 								</thead>
 								<tbody>
 									<?php
 									$tasks = get_tasks_by_invoice($connect,$invoice_id);
 									$words = 0;
 									$bill = 0;
 									while ($task = mysqli_fetch_array($tasks)){?>
 										<tr>
 											<th scope="row" class="text-center"><?php echo "#VXAR".str_pad($task['id'], 6, '0', STR_PAD_LEFT);?></th>
 											<td><?php echo $task['name'];?></td>
 											<td class="text-center"><?php $words += $task['words']; echo $task['words'];?> words</td>
 											<td class="text-right"><?php $bill +=($task['words']/1000)*$task['rate']; echo number_format(($task['words']/1000)*$task['rate'],2);?> /=</td>	
 										</tr>
 									<?php } if($tasks->num_rows<25){ $count = $tasks->num_rows; while ($count<25) {?>
 										<tr style="height: 30px">
 											<th scope="row" class="text-center"></th>
 											<td></td>
 											<td class="text-center"></td>
 											<td class="text-right"></td>	
 										</tr>
 										<?php $count++; } }?>

 										<tr>
 											<th scope="row" class="text-right" colspan="2">Total=</th>
 											<th scope="row" class="text-center"><?php echo $words;?> words</th>
 											<th scope="row" class="text-right"><?php echo number_format($bill,2);?> /=</th>
 										</tr>
 									</tbody>
 								</table>
 							</div>
 							<div class="col-1"></div>

 							<div class="col-12" style="height: 50px;"></div>

 							<div class="col-1"></div>
 							<div class="col-4 text-center">
 								<hr>
 								Signature of Account Manager
 							</div>
 							<div class="col-2"></div>
 							<div class="col-4 text-center">
 								<hr>
 								Signature of Project Manager
 							</div>
 							<div class="col-1"></div>

 							<div class="col-1"></div>
 							<div class="col-10"><hr style="border-top: 1px dashed grey"/></div>
 							<div class="col-1"></div>

 							<div class="col-12 text-center"><b>For Office Use Only</b></div>

 							<div class="col-1"></div>
 							<div class="col-2 text-center" style="background: rgba(0,0,0,0.2); border: 2px solid grey;">Approved/Not Approved</div>
 							<div class="col-3" style="border: 2px solid grey;"></div>
 							<div class="col-2 text-center" style="background: rgba(0,0,0,0.2); border: 2px solid grey;">Signature & Seal of Authority</div>
 							<div class="col-3" style="border: 2px solid grey;"></div>
 							<div class="col-1"></div>

 						</div>

 						<div class="row">
 							<div class="col-12" style="height: 30px;"></div>
 						</div>

 					<script type="text/javascript">
 						$( document ).ready(function() {
 							window.print();
 						});
 					</script>



 					<?php
 				}

 			} else {
 				message("<b>".get_user_data($connect,$_GET['user'])['name']."</b> has no completed tasks for payment!",0);
 			}

 		}
 		?>




 		<?php 

 		if (isset($_GET['invoice'])) {
 			$invoice_id = "VXARI".str_pad($_GET['invoice'], 6, '0', STR_PAD_LEFT);
 			$invoice = get_invoice_data($connect,$_GET['invoice']);?>
 				<div class="row" id="invoice">
 					<div class="col-1"></div>
 					<div class="col-3">
 						<img src="https://viserx.com/wp-content/uploads/2019/04/logo-e1555414846101.png" height="100px">
 					</div>
 					<div class="col-3 text-center">
 						<u><h4>PAYSLIP</h4></u>
 					</div>
 					<div class="col-2 text-center">
 						Payslip No:
 						<br>
 						Payment Date:
 						<br>
 						Payment Status:
 					</div>
 					<div class="col-2 text-center">
 						<div class="border"><b><?php echo $invoice_id;?></b></div>
 						<div class="text-danger"><b><?php echo date("Y-m-d",strtotime($invoice['creation_date']));?></b></div>
 						<div><b><?php if ($invoice['status']==1){echo "<div class='border border-success text-success'>Paid</div>";} else {echo "<div class='border border-danger text-danger'>Unpaid</div>";} ?> </b></div>
 					</div>
 					<div class="col-1"></div>


 					<div class="col-1"></div>
 					<div class="col-3">
 						House: 60, Road: 20, Uttara Model
 						<br>
 						Town, Dhaka - 1230
 					</div>
 					<div class="col-3 text-center">
 						<?php $user = get_user_data($connect,$invoice['paid_to']);?>
 						Payment To:
 						<br>
 						E-mail:
 						<br>
 						Contact No. :
 						<br>
 						Payment Method:
 						<br>
 						Payment Account
 					</div>
 					<div class="col-2 text-left">
 						<b><?php echo $user['name'];?></b>
 						<br>
 						<?php echo $user['email'];?>
 						<br>
 						<?php echo $user['phone'];?>
 						<br>
 						<?php echo $user['payment_method'];?>
 						<br>
 						<?php echo $user['payment_account'];?>
 					</div>
 					<div class="col-2">
 					</div>
 					<div class="col-1"></div>
 					<br>
 					<br>
 					<br>
 					<br>
 					<br>
 					<br>

 					<div class="col-1"></div>
 					<div class="col-10">
 						<table class="table table-sm table-bordered">
 							<thead>
 								<tr align="center">
 									<th scope="col">ID</th>
 									<th scope="col">Title</th>
 									<th scope="col">Words</th>
 									<th scope="col">Bill</th>
 								</tr>
 							</thead>
 							<tbody>
 								<?php
 								$tasks = get_tasks_by_invoice($connect,$invoice_id);
 								$words = 0;
 								$bill = 0;
 								while ($task = mysqli_fetch_array($tasks)){?>
 									<tr>
 										<th scope="row" class="text-center"><?php echo "#VXAR".str_pad($task['id'], 6, '0', STR_PAD_LEFT);?></th>
 										<td><?php echo $task['name'];?></td>
 										<td class="text-center"><?php $words += $task['words']; echo $task['words'];?> words</td>
 										<td class="text-right"><?php $bill +=($task['words']/1000)*$task['rate']; echo number_format(($task['words']/1000)*$task['rate'],2);?> /=</td>	
 									</tr>
 								<?php } if($tasks->num_rows<25){ $count = $tasks->num_rows; while ($count<25) {?>
 									<tr style="height: 30px">
 										<th scope="row" class="text-center"></th>
 										<td></td>
 										<td class="text-center"></td>
 										<td class="text-right"></td>	
 									</tr>
 									<?php $count++; } }?>

 									<tr>
 										<th scope="row" class="text-right" colspan="2">Total=</th>
 										<th scope="row" class="text-center"><?php echo $words;?> words</th>
 										<th scope="row" class="text-right"><?php echo number_format($bill,2);?> /=</th>
 									</tr>
 								</tbody>
 							</table>
 						</div>
 						<div class="col-1"></div>

 						<div class="col-12" style="height: 50px;"></div>

 						<div class="col-1"></div>
 						<div class="col-4 text-center">
 							<hr>
 							Signature of Account Manager
 						</div>
 						<div class="col-2"></div>
 						<div class="col-4 text-center">
 							<hr>
 							Signature of Project Manager
 						</div>
 						<div class="col-1"></div>

 						<div class="col-1"></div>
 						<div class="col-10"><hr style="border-top: 1px dashed grey"/></div>
 						<div class="col-1"></div>

 						<div class="col-12 text-center"><b>For Office Use Only</b></div>

 						<div class="col-1"></div>
 						<div class="col-2 text-center" style="background: rgba(0,0,0,0.2); border: 2px solid grey;">Approved/Not Approved</div>
 						<div class="col-3" style="border: 2px solid grey;"></div>
 						<div class="col-2 text-center" style="background: rgba(0,0,0,0.2); border: 2px solid grey;">Signature & Seal of Authority</div>
 						<div class="col-3" style="border: 2px solid grey;"></div>
 						<div class="col-1"></div>

 					</div>

 					<div class="row">
 						<div class="col-12" style="height: 30px;"></div>
 					</div>


 				<script type="text/javascript">
 					$( document ).ready(function() {
 						window.print();
 					});
 				</script>

 				<?php	
 			} 
 		?>

 	</div>
 </body>
 </html>