<?php
    $rental_model = new \App\Models\Rental_model;
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">


    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['ticket_replied'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Reply Success!</h4>
            <p>Reply has been successfully Posted</p>
        </div>';
            unset($_SESSION['ticket_replied']);
        }  
    ?>

    <h1><?= $state;?> Ticket Management</h1>


    <table class="table table-bordered">
		<thead>
			<tr>
				<th class="align-middle" style="width: 1%;">ID</th>
				<th class="align-middle" style="width: 20%;">Branch</th>
				<th class="align-middle" style="width: 15%;">Tenant</th>
				<th class="align-middle" style="width: 20%;">Category</th>
				<th class="align-middle" style="width: 20%;">Concern</th>
				<th class="align-middle" style="width: 5%;">Replies</th>
				<th class="align-middle" style="width: 1%;">Status</th>
				<th class="align-middle" style="width: 20%;">Latest Activity</th>
				<th class="align-middle" style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($tickets as $t){ ?>
                <tr>
                    <td class="align-middle"><?= $t['support_id']; ?></td>
                    <td class="align-middle">
                        <?php 
                            foreach(json_decode($t['branch_id']) as $b){
                                $brch = $rental_model->getBranchName($b);
                                echo $brch['branch'].'<br>';
                            }
                        ?>
                    </td>
                    <td class="align-middle"><?= $t['tenant']; ?>
                    </td>
                    <td class="align-middle"><?= $t['category']; ?></td>
                    <td class="align-middle"><?= $t['subject']; ?></td>
                    <td class="align-middle"><?= $t['reply']; ?></td>
                    <td class="align-middle">
                        <?php if($t['status'] == 'open'){ echo '<span class="badge badge-warning">'.ucwords($t['status']).'</span>'; } else { echo '<span class="badge badge-success">'.ucwords($t['status']).'</span>'; } ?>
                    </td>
                    <td class="align-middle">
                        <?= $t['added_by'];?><br>
                        <?= date_format(date_create($t['added_on']),"F d, Y");?>
                    </td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-support-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($t['support_id'])); ?>"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
			<?php }?>
		</tbody>
	</table>


</div>



<!-- MODAL VIEW -->
<!-- Modal -->
<div class="modal fade" id="modalview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" ></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="body"></div>

                        <div class="replies"></div>
                    </div>

                    <div class="col-6">
                        <h5 class="mg-b-15">Add Reply</h5>
                    <form action="" method="post" id="reply">
                        <div class="form-group">
                            <textarea class="form-control" rows="20" id="comment" name="content"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Reply</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<!-- END OF MODAL VIEW -->


<script>

    $(document).ready(function() {
        // When the button is clicked, show the modal and load the data

        $(".load-data").on("click", function() {
            // Show the modal
            var sID = $(this).data('support-id');
            // Fetch data using AJAX

            var span;
            $.ajax({
                url: "<?= site_url('ticket/getinfo/')?>" + sID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    $('#reply').attr('action', "<?= site_url("ticket/reply/");?>" + sID);

                    $(".modal-title").html(`${data.tenant} Information`);
                    // Populate the modal body with the fetched data
                    $(".body").html(
                        `
                        <p class="mg-0 mg-b-5">${data.tenant}</p>
                        <h5 class="mg-0 mg-b-5">${data.tenant}</h5>
                        <h5 class="tx-13 tx-color-03 mg-0 mg-b-30">${data.concerned_branch}</h5>
                        `
                    );

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });

            $.ajax({
                url: "<?= site_url('ticket/getreply/')?>" + sID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {
                    var tblitem = "";
					$.each(data, function(index, item) {

                        tblitem += `
                        <div class="timeline-item">
                            <div class="timeline-time">${item.added_on}</div>
                            <div class="timeline-body">
                                <h6 class="mg-b-30">${item.added_by}</h6>
                                <div class="timeline-content">${item.content}</div>
                            </div>
                        </div>
                        `;
                    });

                    $(".replies").html(tblitem);
                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });

        });

    });

</script>
