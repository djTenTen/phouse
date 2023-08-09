<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['expense_updated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Expense Update Success!</h4>
            <p>Expense has been successfully updated!</p>
        </div>';
            unset($_SESSION['expense_updated']);
        }  

        if(!empty($_SESSION['expense_pending'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Expense Pending!</h4>
            <p>Expense has been successfully set to pending</p>
        </div>';
            unset($_SESSION['expense_pending']);
        }  

        if(!empty($_SESSION['expense_compeleted'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Expense Completed!</h4>
            <p>Expense has been successfully Completed</p>
        </div>';
            unset($_SESSION['expense_compeleted']);
        }

        if(!empty($_SESSION['expense_cancelled'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Expense Cancelled!</h4>
            <p>Expense has been Cancelled</p>
        </div>';
            unset($_SESSION['expense_cancelled']);
        }
         
    ?>


    <h1><?= $state; ?> Expense</h1>

    <div class="table-reponsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="align-middle" style="width: 1%;">ID</th>
					<th class="align-middle" style="width: 20%;">Date</th>
					<th class="align-middle" style="width: 20%;">Payee</th>
					<th class="align-middle" style="width: 20%;">Total Amount</th>
					<th class="align-middle" style="width: 1%;">Status</th>
					<th class="align-middle" style="width: 20%;">Added By</th>
					<th class="align-middle" style="width: 20%;">Added On</th>
					<th class="align-middle" style="width: 1%;"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($expenses as $exp){?>
				<tr>
					<td><?= $exp['expense_id']; ?></td>
					<td><?= $exp['date']; ?></td>
					<td><?= $exp['payee']; ?></td>
					<td><?php echo number_format($exp['total'], 2); ?></td>
					<td>
						<?php if($exp['status'] == 'pending'){ ?>
						<span class="badge badge-primary">Pending</span>
						<?php } else if($exp['status'] == 'completed'){ ?>
						<span class="badge badge-success">Completed</span>
						<?php } else if($exp['status'] == 'cancelled'){ ?>
						<span class="badge badge-danger">Cancelled</span>
						<?php } ?>
					</td>
					<td><?= $exp['added_by']; ?></td>
					<td><?= $exp['added_on']; ?></td>
					<td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-expense-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($exp['expense_id'])); ?>"><i class="fas fa-eye"></i></button>
                            <a href="<?= site_url().'expense/edit/'.str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($exp['expense_id'])); ?>" class="btn btn-primary btn-icon btn-sm"><i class="fas fa-edit"></i></a>
						</div>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>

</div>

<!-- MODAL VIEW -->
<!-- Modal -->
<div class="modal fade" id="modalview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reservation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row1">
                
            </div>

            <div class="col-lg-12 mg-b-20">
                <h5 class="mg-b-15">Particulars</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Branch</th>
                            <th style="width: 25%;">Category</th>
                            <th style="width: 25%;">Description</th>
                            <th style="width: 25%;">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="expenseitem">
                        
                    </tbody>
                    <tfoot>
                        <tr class="tx-20">
                            <td class="align-middle text-right" colspan="3">Total:</td>
                            <td class="align-middle text-right" id="totalitems"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-lg-12 mg-b-20">
                <h5 class="mg-b-15">Payment Details</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Source</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 15%;">Check No.</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 15%;">Date Cleared</th>
                            <th style="width: 15%;">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="expensepayment">

                    </tbody>
                    <tfoot>
                        <tr class="tx-20">
                            <td class="align-middle text-right" colspan="6">Total:</td>
                            <td class="align-middle text-right" id="totalpayment"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        <div class="modal-footer">
            <a href="" target="_blank" class="btn btn-info" id="printexpense">Print Expense</a>
            <form id="mpending" action="" method="post">
                <button type="submit" class="btn btn-primary">Mark Pending</button>
            </form>
            <form id="mcomlete" action="" method="post">
                <button type="submit" class="btn btn-success">Mark Completed/Publish</button>
            </form>
            <form id="mcancel" action="" method="post">
                <button type="submit" class="btn btn-danger">Mark Cancelled</button>
            </form>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL VIEW -->


<script>

    function numberformat(num){
        return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    $(document).ready(function() {
        // When the button is clicked, show the modal and load the data
        $(".load-data").on("click", function() {
            // Show the modal
            var eID = $(this).data('expense-id');
            // Fetch data using AJAX

            $('#mpending').attr('action', "<?= site_url("expense/markpending/");?>" + eID);
            $('#mcomlete').attr('action', "<?= site_url("expense/markcompleted/");?>" + eID);
            $('#mcancel').attr('action', "<?= site_url("expense/markcancelled/");?>" + eID);
            $('#printexpense').attr('href', "<?= site_url("expense/print/");?>" + eID);

            var span;
            $.ajax({
                url: "<?= site_url('expense/getdetails/')?>" + eID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    $('#printexpense').attr('href', "<?= site_url("expense/print/");?>" + eID);

                    if(data.status == 'pending'){
                        span = `<span class="badge badge-primary">${ data.status }</span>`;
                    }else if(data.status == 'completed'){
                        span = `<span class="badge badge-success">${ data.status }</span>`;
                    }else if(data.status == 'cancelled'){
                        span = `<span class="badge badge-danger">${ data.status }</span>`;
                    }

                    // Populate the modal body with the fetched data
                    $(".row1").html(
                        `
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label class="d-block">Payee</label>
                                <label class="d-block">${ data.payee }</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Date</label>
                                <label class="d-block">${ data.date }</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Status</label>
                                ${ span }
                            </div>

                            <div class="form-group col-lg-6">
                                <label class="d-block">Added By</label>
                                <label class="d-block">${ data.added_by }</label>
                            </div>

                            <div class="form-group col-lg-6">
                                <label class="d-block">Added On</label>
                                <label class="d-block">${ data.added_on }</label>
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

                url: "<?= site_url('expense/getitems/')?>" + eID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    var tableHTML = "";
                    var totalItems = 0;
                    $.each(data, function(index, item) {
                        totalItems +=  parseInt(item.amount);
                        tableHTML += `
                            <tr>
                                <td class="align-middle">${item.branch}</td>
                                <td class="align-middle">${item.category}</td>
                                <td class="align-middle">${item.description}</td>
                                <td class="align-middle text-right">${item.amount}</td>
                            </tr>
                        `;

                    });

                    $(".expenseitem").html(tableHTML);
                    $("#totalitems").html(`₱ ${numberformat(totalItems)}`);


                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });

            $.ajax({

                url: "<?= site_url('expense/getpayment/')?>" + eID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    var tableHTML = "";
                    var totalPayments = 0;
                    var span2;
                    $.each(data, function(index, payment) {

                        totalPayments += parseInt(payment.amount);

                        if(payment.status == 'pending'){
                            span2 = `<span class="badge badge-primary">${ payment.status }</span>`;
                        }else if(payment.status == 'completed'){
                            span2 = `<span class="badge badge-success">${ payment.status }</span>`;
                        }else if(payment.status == 'cancelled'){
                            span2 = `<span class="badge badge-danger">${ payment.status }</span>`;
                        }

                        tableHTML += `
                            <tr>
                                <td class="align-middle">[${ payment.bank }] ${ payment.bank_account }</td>
                                <td class="align-middle">${ payment.type }</td>
                                <td class="align-middle">${ payment.date }</td>
                                <td class="align-middle">${ payment.check_number }</td>
                                <td class="align-middle">
                                    ${ span2 }
                                </td>
                                <td class="align-middle">${ payment.date_posted }</td>
                                <td class="align-middle text-right">${ payment.amount }</td>
                            </tr>
                        `;

                    });

                    $(".expensepayment").html(tableHTML);

                    $("#totalpayment").html(`₱ ${numberformat(totalPayments)}`);

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });

        });

    });

</script>