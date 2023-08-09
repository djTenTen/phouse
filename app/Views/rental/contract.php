<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <h1><?= $state;?> Contract Management</h1>
    
    <table class="table table-bordered">
		<thead>
			<tr>
				<th style="width: 1%;">ID</th>
				<th style="width: 15%;">Tenant</th>
				<th style="width: 15%;">Branch</th>
				<th style="width: 10%;">Slot Number</th>
				<th style="width: 15%;">Comission</th>
				<th style="width: 15%;">Monthly Rate</th>
				<th style="width: 10%;">Duration</th>
				<th style="width: 10%;">Start</th>
				<th style="width: 10%;">End</th>
				<th style="width: 1%;">Status</th>
				<th style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($contracts as $ct){?>
			<tr>
				<td class="align-middle"><?= $ct['contract_id']; ?></td>
				<td class="align-middle"><?= $ct['tenant']; ?></td>
				<td class="align-middle"><?= $ct['branch']; ?></td>
				<td class="align-middle"><?= $ct['slot_number']; ?></td>
				<td class="align-middle"><?= $ct['commission']; ?>%</td>
				<td class="align-middle"><?= number_format($ct['monthly_rate'], 2); ?></td>
				<td class="align-middle"><?= $ct['duration']; ?> Month/s</td>
				<td class="align-middle"><?= date_format(date_create($ct['start']),"M. d, Y")?></td>
				<td class="align-middle"><?= date_format(date_create($ct['end']),"M. d, Y")?></td>
				<td class="align-middle">
					<?php if($ct['status'] == 'pending'){ ?><span class="badge badge-primary">Pending</span><?php } ?>
					<?php if($ct['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
					<?php if($ct['status'] == 'cancelled'){ ?><span class="badge badge-warning">Cancelled</span><?php } ?>
					<?php if($ct['status'] == 'terminated'){ ?><span class="badge badge-danger">Terminated</span><?php } ?>
				</td>
				<td class="align-middle">
					<div class="btn-group">
						<a href="<?= site_url("contract/viewcontract/".str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ct['contract_id']))); ?>" class="btn btn-icon btn-success btn-sm"><i class="fas fa-search"></i></a>
                        <?php if($ct['status'] == 'pending'){ ?>
                            <button type="button" class="btn btn-primary btn-icon btn-sm edit-data" data-toggle="modal" data-target="#modaledit" data-contract-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ct['contract_id'])); ?>"><i class="fas fa-edit"></i></button>
                        <?php } ?>
					</div>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>

</div>







<!-- MODAL EDIT -->
<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" ></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="" method="post" id="editcont">
            <div class="edit-body">
                
            </div>
        </div>
        <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Contract</button>
                </form>
            <div class="ai"></div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL EDIT -->







<script>

    $(document).ready(function() {
        // When the button is clicked, show the modal and load the data

        $(".edit-data").on("click", function() {
            // Show the modal
            var cID = $(this).data('contract-id');
            // Fetch data using AJAX

            $.ajax({
                url: "<?= site_url('contract/getinfo/')?>" + cID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {
                    
                    $('#editcont').attr('action', "<?= site_url("contract/update/");?>" + cID);


                    $(".modal-title").html(`${data.tenant}-${data.branch} Information`);
                    // Populate the modal body with the fetched data
                    $(".edit-body").html(
                        `
						<div class="form-group row">
							<label class="d-block col-form-label col-lg-3">Name</label>
							<label class="d-block col-form-label col-lg-9">${data.tenant}</label>
						</div>

						<div class="form-group row">
							<label class="d-block col-form-label col-lg-3">Branch</label>
							<label class="d-block col-form-label col-lg-9">${data.branch}</label>
						</div>

						<div class="form-group row">
							<label class="d-block col-form-label col-lg-3">Commission</label>
							<div class="col-lg-9"><input type="number" class="form-control" name="commission" min="0" value="${data.commission}" required></div>
						</div>

						<div class="form-group row">
							<label class="d-block col-form-label col-lg-3">Monthly Rate</label>
							<div class="col-lg-9"><input type="number" class="form-control" name="monthly-rate" min="0" value="${data.monthly_rate}" required></div>
						</div>

						<div class="form-group row">
							<label class="d-block col-form-label col-lg-3">Slot Number</label>
							<div class="col-lg-9"><input type="text" class="form-control" name="slot-number" value="${data.slot_number}" required></div>
						</div>

						<div class="form-group row">
							<label class="d-block col-form-label col-lg-3">Contract Start</label>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-4">
                                        <select name="mm" class="form-control" required>
                                            <option value="${data.mm}" selected>${data.MM}</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>/

                                    <div class="col-3">
                                        <select name="dd" class="form-control" required>
                                            <option value="${data.dd}" selected>${data.dd}</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                        </select>
                                    </div>/
                                    <div class="col-4">
                                        <select name="yy" class="form-control" required>
                                            <option value="${data.yy}" selected>${data.yy}</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                            <option value="2028">2028</option>
                                            <option value="2029">2029</option>
                                            <option value="2030">2030</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `
                    );

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });

        });

    });

</script>