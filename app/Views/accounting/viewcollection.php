<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

	<?php
        // Message thrown from the controller
        if(!empty($_SESSION['collection_updated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Collection Update Success!</h4>
            <p>Collection has been successfully Updated</p>
        </div>';
            unset($_SESSION['collection_updated']);
        }  
    ?>

    <h1>Collection</h1>


    <div class="row">
		<div class="form-group col-lg-2">
			<label class="d-block">Collection List ID</label>
			<label class="d-block"><?= $colldetails['collection_list_id']; ?></label>
		</div>

		<div class="form-group col-lg-4">
			<label class="d-block">Bank Account</label>
			<label class="d-block">[<?= $colldetails['bank'] ?>] <?= $colldetails['bank_account'] ?> (<?= $colldetails['account_number'] ?>)</label>
		</div>

		<div class="form-group col-lg-2">
			<label class="d-block">Deposit Date</label>
			<label class="d-block"><?= date_format(date_create($colldetails['date']),"F d, Y");?></label>
		</div>

		<div class="form-group col-lg-2">
			<label class="d-block"># of Payment</label>
			<label class="d-block"><?= $colldetails['count']; ?></label>
		</div>

		<div class="form-group col-lg-2">
			<label class="d-block">Total</label>
			<label class="d-block"><?= number_format($colldetails['total'], 2); ?></label>
		</div>
	</div>




	<table class="table table-bordered">
		<thead>
			<tr>
				<th style="width: 1%;">ID</th>
				<th style="width: 15%;">Tenant</th>
				<th style="width: 10%;">Branch</th>
				<th style="width: 15%;">Payment Coverage</th>
				<th style="width: 30%;">Payment Details</th>
				<th style="width: 10%;">Amount</th>
				<th style="width: 10%;">Status</th>
				<th style="width: 10%;">Remarks</th>
				<th style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($collitems as $ci){?>
			<tr>
				<td class="align-middle"><a href=""><?= $ci['contract_id']; ?></a></td>
				<td class="align-middle"><?= $ci['tenant']; ?></td>
				<td class="align-middle"><?= $ci['branch']; ?> [<?= $ci['slot_number']; ?>]</td>
				<td class="align-middle">
					<?php echo ucwords(str_replace("-", " ", $ci['identifier'])); ?><br>
					<?php
						$pc = '';
						if($ci['identifier'] == 'security-deposit'){
							$pc = "Security Deposit";
							echo $pc;
						} else {
							$month = explode("-", $ci['identifier']);
							$pc = date("M. j, Y", strtotime($ci['start'] . " + " . ($month[1] - 1) . " month")) . " - " . date("M j, Y", strtotime($ci['start'] . "+" . $month[1] . " month -1 day"));
							echo $pc;
							
						}

					?>
				</td>
				<td class="align-middle">
				<?php
					$pd = '';
					if(in_array($ci['type'], array("cash", "bank-deposit", "online-bank-transfer"))){
						$pd = ucwords(str_replace("-", " ", $ci['type'])) . "<br>" . date_format(date_create($ci['date']),"F d, Y");
						echo $pd;
					} else {
						$pd = ucwords(str_replace("-", " ", $ci['type'])) . "<br>" . $ci['bank'] . "<br>#" . $ci['check_number'] . " - " . date_format(date_create($ci['date']),"F d, Y");
						echo $pd;
					}
				?>
				</td>
				<td class="align-middle text-right"><?= number_format($ci['amount'], 2); ?></td>
				<td class="align-middle">
					<?php if($ci['status'] == 'clearing'){ ?><span class="badge badge-info">Clearing</span><?php } ?>
					<?php if($ci['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
					<?php if($ci['status'] == 'bounced'){ ?><span class="badge badge-danger">Bounced</span><?php } ?>
				</td>
				<td class="align-middle"><?= $ci['remarks']; ?></td>
				<td class="align-middle">
					<?php if($ci['status'] == 'clearing'){ ?>
						<button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modaledit<?= $ci['collection_list_item_id']; ?>"><i class="fas fa-edit"></i></button>
					<?php } ?>

						<!-- MODAL VIEW -->
						<!-- Modal -->
						<div class="modal fade" id="modaledit<?= $ci['collection_list_item_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Update Collection List</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
								
									<?= form_open('collection/update/'.str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ci['collection_list_item_id'])).'/'.$clID);?>
									<table class="table table-bordered">
										<tbody>
											<tr>
												<td>Contract ID</td>
												<td><?= $ci['contract_id']; ?></td>
											</tr>
											<tr>
												<td>Tenant</td>
												<td><?= $ci['tenant']; ?></td>
											</tr>
											<tr>
												<td>Branch</td>
												<td><?= $ci['branch']; ?></td>
											</tr>
											<tr>
												<td>Slot Number</td>
												<td><?= $ci['slot_number']; ?></td>
											</tr>
											<tr>
												<td>Payment Coverage</td>
												<td><?= $pc; ?></td>
											</tr>
											<tr>
												<td>Payment Details</td>
												<td><?= $pd; ?></td>
											</tr>
											<tr>
												<td>Amount</td>
												<td>₱ <?= number_format($ci['amount'], 2); ?></td>
											</tr>
											<tr>
												<td>Status</td>
												<td>
													<select name="status" class="form-control" id="">
														<option value="bounced" selected>Bounced</option>
													</select>
												</td>
											</tr>
											<tr>
												<td>Remarks</td>
												<td>
													<input type="text" name="remarks" class="form-control">
												</td>
											</tr>
										</tbody>
									</table>
									
								<div class="modal-footer">

										<button type="submit" class="btn btn-primary">Update</button>
									</form>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
								</div>
								</div>
							</div>
						</div>
						<!-- END OF MODAL VIEW -->

				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>


	<a href="<?= site_url('collection/print/'.$clID)?>" target="_blank" class="btn btn-primary">Print Collection List</a>

</div>