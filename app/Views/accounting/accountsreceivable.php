<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <h1>Account Receivable</h1>

    <table class="table table-bordered">
		<thead>
			<tr>
				<th style="width: 1%;">ID</th>
				<th style="width: 15%;">Tenant</th>
				<th style="width: 15%;">Branch</th>
				<th style="width: 10%;">Slot Number</th>
				<th style="width: 15%;">Payment Coverage</th>
				<th style="width: 30%;">Payment Details</th>
				<th style="width: 15%;">Amount</th>
				<th style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$total = 0;
                foreach($receivable as $rcv){
			?>
			<tr>
				<td class="align-middle"><?= $rcv['contract_id']; ?></td>
				<td class="align-middle"><?= $rcv['tenant']; ?></td>
				<td class="align-middle"><?= $rcv['branch']; ?></td>
				<td class="align-middle"><?= $rcv['slot_number']; ?></td>
				<td class="align-middle">
					<?= ucwords(str_replace("-", " ", $rcv['identifier'])); ?><br>
					<?php
						if($rcv['identifier'] == 'security-deposit'){
							echo "Security Deposit";
						} else {
							$month = explode("-", $rcv['identifier']);
							echo date("M. j, Y", strtotime($rcv['start'] . " + " . ($month[1] - 1) . " month")) . " - " . date("M. j, Y", strtotime($rcv['start'] . "+" . $month[1] . " month -1 day"));
						}
					?>
				</td>
				<td class="align-middle">
				<?php
					if(in_array($rcv['type'], array("cash", "bank-deposit", "online-bank-transfer"))){
						echo ucwords(str_replace("-", " ", $rcv['type'])) . "<br>" . date_format(date_create($rcv['date']),"F d, Y");
					} else {
						echo ucwords(str_replace("-", " ", $rcv['type'])) . "<br>" . $rcv['bank'] . "<br>#" . $rcv['check_number'] . " - " . date_format(date_create($rcv['date']),"F d, Y");
					}
				?>
				</td>
				<td class="align-middle text-right"><?= number_format($rcv['amount'], 2); ?></td>
				<td class="align-middle">
                    <button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-contract-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($rcv['contract_id']))?>"><i class="fas fa-search"></i></button>
				</td>
			</tr>
			<?php }?>
		</tbody>
		<tfoot>
			<tr>
				<td class="text-right" colspan="6">Total</td>
				<td class="text-right"><?= number_format($total, 2) ?></td>
				<td></td>
			</tr>
		</tfoot>
	</table>

</div>



<!-- MODAL VIEW -->
<!-- Modal -->
<div class="modal fade" id="modalview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">View Contract</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row1">

            </div>

            <table class="table table-bordered">
				<thead>
					<tr>
						<th style="width: 15%;">Coverage</th>
						<th style="width: 85%;">Payment</th>
					</tr>
				</thead>
				<tbody class="data-check-deposit">
					<tr>
						<td class="align-middle">Security Deposit</td>
						<td>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th style="width: 10%;">Type</th>
										<th style="width: 20%;">Bank</th>
										<th style="width: 15%;">Check No.</th>
										<th style="width: 20%;">Amount</th>
										<th style="width: 20%;">Date</th>
										<th style="width: 15%;">Status</th>
									</tr>
								</thead>
								<tbody class="data-security-deposit">
                                    
								</tbody>
							</table>
						</td>
					</tr>
                    

                    
				</tbody>
			</table>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL VIEW -->


<script>

    function formatDate(dateString) {
        const dateObj = new Date(dateString);
        const options = { month: 'long', day: 'numeric', year: 'numeric' };
        const formattedDate = dateObj.toLocaleDateString('en-US', options);
        return formattedDate;
    }

    function addOneMonthOneDay(dateString) {
        const dateObj = new Date(dateString);
        dateObj.setMonth(dateObj.getMonth() + 1);
        dateObj.setDate(dateObj.getDate() - 1);
        return dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    }

    $(document).ready(function() {
        // When the button is clicked, show the modal and load the data
        $(".load-data").on("click", function() {
            // Show the modal
            var cID = $(this).data('contract-id');
            // Fetch data using AJAX

            $(".toremove").remove();
         
            var span;
            $.ajax({
                url: "<?= site_url('account/contract/details/')?>" + cID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    if(data.status == 'pending'){
                        span = `<span class="badge badge-primary">${ data.status }</span>`;
                    }else if(data.status == 'completed'){
                        span = `<span class="badge badge-success">${ data.status }</span>`;
                    }else if(data.status == 'cancelled'){
                        span = `<span class="badge badge-warning">${ data.status }</span>`;
                    }else if(data.status == 'terminated'){
                        span = `<span class="badge badge-danger">${ data.status }</span>`;
                    }

                    // Populate the modal body with the fetched data
                    $(".row1").html(
                        `
                        <div class="col-lg-9">
		                    <div class="row mg-b-30">
                                <div class="col-lg-6 mg-b-10">
                                    <label class="d-block">Contract ID</label>
                                    <label class="d-block">${data.contract_id}</label>
                                </div>
                                <div class="col-lg-6 mg-b-10">
                                    <label class="d-block">Status</label>
                                    ${span}
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Tenant</label>
                                    <label class="d-block">${data.tenant}</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Branch</label>
                                    <label class="d-block">${data.branch}</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Commission</label>
                                    <label class="d-block">${data.commission}%</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Monthly Rate</label>
                                    <label class="d-block">${data.monthly_rate}</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Slot Number</label>
                                    <label class="d-block">${data.slot_number}</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Contract Start</label>
                                    <label class="d-block">${formatDate(data.start)}</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Contract End</label>
                                    <label class="d-block">${formatDate(data.end)}</label>
                                </div>

                                <div class="col-lg-3 mg-b-10">
                                    <label class="d-block">Contract Duration</label>
                                    <label class="d-block">${data.duration} Month/s</label>
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


            $.ajax({
                url: "<?= site_url('account/secdep/details/')?>" + cID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    var span2;
                    var tableHTMLsecdep = "";
                    var hasData = false;

                    $.each(data, function(index, item) {
                        hasData = true;
                        if(item.status == 'pending'){
                        span3 = `<span class="badge badge-primary">${ item.status }</span>`;
                        }else if(item.status == 'clearing'){
                            span3 = `<span class="badge badge-info">${ item.status }</span>`;
                        }else if(item.status == 'completed'){
                            span3 = `<span class="badge badge-success">${ item.status }</span>`;
                        }else if(item.status == 'bounced'){
                            span3 = `<span class="badge badge-danger">${ item.status }</span>`;
                        }else if(item.status == 'returned'){
                            span3 = `<span class="badge badge-warning">${ item.status }</span>`;
                        }

                        tableHTMLsecdep += `
                            <tr>
                                <td>${item.type}</td>
                                <td>${item.bank}</td>
                                <td>${item.check_number}</td>
                                <td>${item.amount}</td>
                                <td>${formatDate(item.date)}</td>
                                <td>${span2}</td>
                            </tr>
                        `;

                    });

                    if (hasData) {
                        $(".data-security-deposit").html(tableHTMLsecdep);
                    } else {
                        $(".data-security-deposit").html(`<tr><td colspan="6"><h5 class="text-center mg-t-30 mg-b-30">No records found.</h5></td></tr>`);
                    }

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });



            $.ajax({
                url: "<?= site_url('account/checkdep/details/')?>" + cID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    var span3;
                    var tableHTMLcheckdep = "";
                    var coverage = "";

                    $.each(data, function(index, item) {

                        if(item.status == 'pending'){
                        span3 = `<span class="badge badge-primary">${ item.status }</span>`;
                        }else if(item.status == 'clearing'){
                            span3 = `<span class="badge badge-info">${ item.status }</span>`;
                        }else if(item.status == 'completed'){
                            span3 = `<span class="badge badge-success">${ item.status }</span>`;
                        }else if(item.status == 'bounced'){
                            span3 = `<span class="badge badge-danger">${ item.status }</span>`;
                        }else if(item.status == 'returned'){
                            span3 = `<span class="badge badge-warning">${ item.status }</span>`;
                        }

                        //coverage  += `<td>${item.date}</td>`;

                        tableHTMLcheckdep += `

                            <tr class="toremove">
                                <td class="align-middle">
                                    ${formatDate(item.date)} <br>
                                    to <br>
                                    ${formatDate(addOneMonthOneDay(item.date))}
                                </td>
                                <td>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">Type</th>
                                                <th style="width: 20%;">Bank</th>
                                                <th style="width: 15%;">Check No.</th>
                                                <th style="width: 20%;">Amount</th>
                                                <th style="width: 20%;">Date</th>
                                                <th style="width: 15%;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>${item.type}</td>
                                                <td>${item.bank}</td>
                                                <td>${item.check_number}</td>
                                                <td>${item.amount}</td>
                                                <td>${formatDate(item.date)}</td>
                                                <td>${span3}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                        `;

                    });
                    // $(".coverage").html(tableHTMLcheckdep);
                    $(".data-check-deposit").append(tableHTMLcheckdep);
                    
                    
                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });



            
           
        });

    });

</script>