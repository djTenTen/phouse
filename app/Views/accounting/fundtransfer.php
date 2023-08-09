<?php
    $encrypter = \Config\Services::encrypter();
	$acctng = new \App\Models\Accounting_model;
	
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['fundtransfer_cancelled'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Fund Transfer Cancelled!</h4>
            <p>Fund Transfer has been cancelled.</p>
        </div>';
            unset($_SESSION['fundtransfer_cancelled']);
        }  

        // Message thrown from the controller
        if(!empty($_SESSION['fundtransfer_completed'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Fund Transfer Completed!</h4>
            <p>Fund Transfer has been successfully completed</p>
        </div>';
            unset($_SESSION['fundtransfer_completed']);
        }  
    ?>

    <h1><?= $state;?> Fund Transfer</h1>

    <div class="table-reponsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="align-middle" style="width: 1%;">ID</th>
					<th class="align-middle" style="width: 20%;">Transfer From</th>
					<th class="align-middle" style="width: 20%;">Transfer To</th>
					<th class="align-middle" style="width: 20%;">Total Amount</th>
					<th class="align-middle" style="width: 1%;">Status</th>
					<th class="align-middle" style="width: 20%;">Added By</th>
					<th class="align-middle" style="width: 20%;">Added On</th>
					<th class="align-middle" style="width: 1%;"></th>
				</tr>
			</thead>
			<tbody>
                <?php 
					foreach($fundtransfer as $ft){
						$from = $acctng->getbankname($ft['transfer_from']);
						$to = $acctng->getbankname($ft['transfer_to']);
				?>
                <tr>
					<td class="align-middle"><?= $ft['fund_transfer_id']; ?></td>
					<td class="align-middle">[<?= $from['bank']; ?>]<?= $from['name']; ?></td>
					<td class="align-middle">[<?= $to['bank']; ?>]<?= $to['name']; ?></td>
					<td class="align-middle"><?= number_format($ft['amount'], 2);?></td>
					<td class="align-middle">
						<?php if($ft['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
						<?php if($ft['status'] == 'cancelled'){ ?><span class="badge badge-warning">Cancelled</span><?php } ?>
					</td>
					<td class="align-middle"><?= $ft['added_by']; ?></td>
					<td class="align-middle"><?= $ft['added_on']; ?></td>
					<td class="align-middle">
						<div class="btn-group">
                            <button type="button" class="btn btn-success btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-from="[<?= $from['bank']; ?>]<?= $from['name']; ?>" data-to="[<?= $to['bank']; ?>]<?= $to['name']; ?>" data-fundt-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ft['fund_transfer_id'])); ?>"><i class="fas fa-eye"></i></button>
							<button type="button" class="btn btn-primary btn-icon btn-sm edit-data" data-toggle="modal" data-target="#modaledit" data-fundt-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ft['fund_transfer_id'])); ?>"><i class="fas fa-edit"></i></button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">View Fund Transfer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
			<div class="row1">


			</div>
            

        <div class="modal-footer">
			<a href="" target="_blank" class="btn btn-info" id="printtransfer">Print Transfer</a>
			<form id="mcomplete" action="" method="post">
                <button type="submit" class="btn btn-success">Mark Completed</button>
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
            var ftID = $(this).data('fundt-id');

			var from = $(this).data('from');
			var to = $(this).data('to');
            // Fetch data using AJAX
            $('#mcancel').attr('action', "<?= site_url("fundtransfer/markcancelled/");?>" + ftID);
			$('#mcomplete').attr('action', "<?= site_url("fundtransfer/markcompleted/");?>" + ftID);
            $('#printtransfer').attr('href', "<?= site_url("fundtransfer/print/");?>" + ftID);


            var span;
            $.ajax({
                url: "<?= site_url('fundtransfer/getdetails/')?>" + ftID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {


                  	if(data.status == 'completed'){
                        span = `<span class="badge badge-success">${ data.status }</span>`;
                    }else if(data.status == 'cancelled'){
                        span = `<span class="badge badge-danger">${ data.status }</span>`;
                    }

                    // Populate the modal body with the fetched data
                    $(".row1").html(
                        `
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label class="d-block">Transfer From:</label>
                                <label class="d-block">${from }</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Trasfer To:</label>
                                <label class="d-block">${ to }</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Status</label>
                                ${ span }
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="d-block">Purpose</label>
                                <label class="d-block">${ data.purpose }</label>
                            </div>

                            <div class="form-group col-lg-6">
                                <label class="d-block">Check Number</label>
                                <label class="d-block">${ data.check_number }</label>
                            </div>

							<div class="form-group col-lg-6">
                                <label class="d-block">Amount</label>
                                <label class="d-block">₱ ${ numberformat(data.amount) }</label>
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


