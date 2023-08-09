<?php
    $rental_model = new \App\Models\Rental_model;
	$encrypter = \Config\Services::encrypter();
?>
<div class="container">

	<?php
        // Message thrown from the controller
        if(!empty($_SESSION['tenant_updated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Tenant Update Success!</h4>
            <p>Tenant has been successfully Updated</p>
        </div>';
            unset($_SESSION['tenant_updated']);
        }  

        // Message thrown from the controller
        if(!empty($_SESSION['tenant_activated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Tenant Activated!</h4>
            <p>Tenant has been activated</p>
        </div>';
            unset($_SESSION['tenant_activated']);
        } 

        // Message thrown from the controller
        if(!empty($_SESSION['tenant_deactivated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Tenant Deactivated!</h4>
            <p>Tenant has been deactivated</p>
        </div>';
            unset($_SESSION['tenant_deactivated']);
        } 
    ?>

    <h1><?= $state;?> Tenant Management</h1>

    <table class="table table-bordered">
		<thead>
			<tr>
				<th class="align-middle" style="width: 1%;">ID</th>
				<th class="align-middle" style="width: 20%;">Name</th>
				<th class="align-middle" style="width: 15%;">Facebook</th>
				<th class="align-middle" style="width: 15%;">Instagram</th>
				<th class="align-middle" style="width: 20%;">Contact Person</th>
				<th class="align-middle" style="width: 20%;">Contact Number</th>
				<th class="align-middle" style="width: 10%;">Active Branches</th>
				<th class="align-middle" style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($tenants as $tnts){
                $contact_number = explode(",", $tnts['contact_number']);
                $facebook = explode(",", $tnts['facebook']);
                $instagram = explode(",", $tnts['instagram']);
                $branches = $rental_model->countBranches($tnts['tenant_id']);
			?>
			<tr>
				<td><?= $tnts['tenant_id']; ?></td>
				<td><?= $tnts['name']; ?></td>
				<td><?php foreach($facebook as $i => $value){ if($i > 0){ echo ', '; } echo '<a href="https://www.facebook.com/'.$value.'" target="_blank">'.$value.'</a>'; } ?></td>
				<td><?php foreach($instagram as $i => $value){ if($i > 0){ echo ', '; } echo '<a href="https://www.instagram.com/'.$value.'" target="_blank">'.$value.'</a>'; } ?></td>
				<td><?= $tnts['contact_person']; ?></td>
				<td><?php foreach($contact_number as $i => $value){ if($i > 0){ echo ', '; } echo $value; } ?></td>
				<td class="text-center"><?= $branches['count']; ?></td>
				<td>
					<div class="btn-group">
                        <button type="button" class="btn btn-success btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-tenant-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($tnts['tenant_id'])); ?>"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-primary btn-icon btn-sm edit-data" data-toggle="modal" data-target="#modaledit" data-tenant-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($tnts['tenant_id'])); ?>"><i class="fas fa-edit"></i></button>
					</div>
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
            <div class="body">

            </div>


			<h5 class="mg-t-30 mg-b-15">Active Contract</h5>

			<h5 class="mg-t-30 mg-b-15">Tenant Concern</h5>


			<table class="table table-bordered">
				<thead>
					<tr>
						<th style="width: 1%;">ID</th>
						<th style="width: 50%;">Subject</th>
						<th style="width: 15%;">Reply</th>
						<th style="width: 15%;">Status</th>
					</tr>
				</thead>
				<tbody class="tenantconcern">
					
				</tbody>
			</table>

        </div>
        <div class="modal-footer">
            
            <div class="ai">
                    
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL VIEW -->



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
            <form action="" method="post" id="edittenant">
            <div class="edit-body">
                
            </div>
        </div>
        <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Employee</button>
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

        $(".load-data").on("click", function() {
            // Show the modal
            var tID = $(this).data('tenant-id');
            // Fetch data using AJAX

            var span;
            $.ajax({
                url: "<?= site_url('tenant/getinfo/')?>" + tID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    if(data.status == 'active'){
                        span = `<span class="badge badge-success">${ data.status }</span>`;
                        $(".ai").html(`
                            <form id="" action="<?= site_url("tenant/deactivate/");?>${tID}" method="post">
                                <button type="submit" class="btn btn-danger">Deactivate</button>
                            </form>
                        `);
                    }else if(data.status == 'inactive'){
                        span = `<span class="badge badge-danger">${ data.status }</span>`;
                        $(".ai").html(`
                            <form id="" action="<?= site_url("tenant/activate/");?>${tID}" method="post">
                                <button type="submit" class="btn btn-primary">Activate</button>
                            </form>
                        `);   
                    }

                    $(".modal-title").html(`${data.name} Information`);
                    // Populate the modal body with the fetched data
                    $(".body").html(
                        `
                        <div class="row">
                            <h5 class="col-lg-12 mg-b-30">Personal Information</h5>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Tenant ID</label>
                                <label class="d-block">${data.tenant_id}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Category</label>
                                <label class="d-block">${data.category}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Name</label>
                                <label class="d-block">${data.name}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Facebook</label>
                                <label class="d-block">${data.facebook}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Instagram</label>
                                <label class="d-block">${data.instagram}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Contact Person</label>
                                <label class="d-block">${data.contact_person}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Contact Number</label>
                                <label class="d-block">${data.contact_number}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Email Address</label>
                                <label class="d-block">${data.email_address}</label>
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
                url: "<?= site_url('tenant/getconcern/')?>" + tID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

					var tblitem = "";
					$.each(data, function(index, item) {

                        tblitem += `
						<tr>
							<td>${item.support_id}</td>
							<td>${item.subject}<br>${item.added_by}, ${item.added_on}</td>
							<td>${item.reply}</td>
							<td>${item.status}</td>
						</tr>
                        `;
                    });

                    $(".tenantconcern").html(tblitem);

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });

        });




		$(".edit-data").on("click", function() {
            // Show the modal
            var tID = $(this).data('tenant-id');
            // Fetch data using AJAX

            var span;
            $.ajax({
                url: "<?= site_url('tenant/getinfo/')?>" + tID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

					$('#edittenant').attr('action', "<?= site_url("tenant/update/");?>" + tID);

                    if(data.status == 'active'){
                        span = `<span class="badge badge-success">${ data.status }</span>`;
                        $(".ai").html(`
                            <form id="" action="<?= site_url("tenant/deactivate/");?>${tID}" method="post">
                                <button type="submit" class="btn btn-danger">Deactivate</button>
                            </form>
                        `);
                    }else if(data.status == 'inactive'){
                        span = `<span class="badge badge-danger">${ data.status }</span>`;
                        $(".ai").html(`
                            <form id="" action="<?= site_url("tenant/activate/");?>${tID}" method="post">
                                <button type="submit" class="btn btn-primary">Activate</button>
                            </form>
                        `);   
                    }

                    $(".modal-title").html(`Edit ${data.name} Information`);
                    // Populate the modal body with the fetched data
                    $(".edit-body").html(
                        `
                        <div class="row">
							<div class="form-group col-lg-6">
								<label class="d-block">Name</label>
								<input type="text" class="form-control" name="name" value="${data.name}" required autofocus>
							</div>

							<div class="form-group col-lg-6">
								<label class="d-block">Category</label>
								<select name="category" class="custom-select" required>
									<option value="${data.category_id}" selected>${data.category}</option>
									<?php foreach($category as $cate){?>
										<option value="<?= $encrypter->encrypt($cate['category_id'])?>"><?= $cate['name']?></option>
									<?php }?>
								</select>
							</div>

							<div class="form-group col-lg-6">
								<label class="d-block">Facebook</label>
								<div class="input-group mg-b-10">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fab fa-facebook-f"></i>
										</span>
									</div>
									<input type="text" class="form-control" name="facebook" value="${data.facebook}">
								</div>
							</div>

							<div class="form-group col-lg-6">
								<label class="d-block">Instagram</label>
								<div class="input-group mg-b-10">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fab fa-instagram"></i>
										</span>
									</div>
									<input type="text" class="form-control" name="instagram" value="${data.instagram}">
								</div>
							</div>

							<div class="form-group col-lg-6">
								<label class="d-block">Contact Person</label>
								<input type="text" class="form-control" name="contact-person" value="${data.contact_person}" required>
							</div>

							<div class="form-group col-lg-6">
								<label class="d-block">Contact Number</label>
								<input type="text" class="form-control" name="contact-number" value="${data.contact_number}" required>
							</div>

							<div class="form-group col-lg-6">
								<label class="d-block">Email Address</label>
								<input type="text" class="form-control" name="email-address" value="${data.email_address}">
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