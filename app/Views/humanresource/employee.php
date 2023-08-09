<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['employee_updated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Employee Update Success!</h4>
            <p>Employee has been successfully Updated</p>
        </div>';
            unset($_SESSION['employee_updated']);
        }  

        // Message thrown from the controller
        if(!empty($_SESSION['employee_activated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Employee Activated!</h4>
            <p>Employee has been activated</p>
        </div>';
            unset($_SESSION['employee_activated']);
        } 

        // Message thrown from the controller
        if(!empty($_SESSION['employee_desctivated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Employee Deactivated!</h4>
            <p>Employee has been deactivated</p>
        </div>';
            unset($_SESSION['employee_desctivated']);
        } 
    ?>

    <h1>Employee Management</h1>

    <table class="table table-bordered">
		<thead>
			<tr>
				<th class="align-middle" style="width: 1%;">ID</th>
				<th class="align-middle" style="width: 15%;">Name</th>
				<th class="align-middle" style="width: 10%;">Salary</th>
				<th class="align-middle" style="width: 15%;">Contact Number</th>
				<th class="align-middle" style="width: 10%;">Date Hired</th>
				<th class="align-middle" style="width: 15%;">Assigned Branch</th>
				<th class="align-middle" style="width: 10%;">Gov't Requirements</th>
				<th class="align-middle" style="width: 15%;">Emergency Contact Person</th>
				<th class="align-middle" style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($employee as $emp){?>
			<tr>
				<td class="align-middle"><?= $emp['employee_id']; ?></td>
				<td class="align-middle"><?= $emp['first_name']; ?> <?= $emp['middle_name']; ?> <?= $emp['last_name']; ?></td>
				<td class="align-middle"><?= number_format($emp['salary'], 2); ?> / <?= ucwords($emp['salary_type']); ?></td>
				<td class="align-middle"><?= $emp['contact_number']; ?></td>
				<td class="align-middle"><?= date_format(date_create($emp['date_hired']),"F d, Y");?></td>
				<td class="align-middle"><?= $emp['branch']; ?></td>
				<td class="align-middle text-center">
					<?php
						if(
							$emp['metrobank_account_number'] != "" &&
							$emp['tin_number'] != "" &&
							$emp['sss_number'] != "" &&
							$emp['philhealth_number'] != "" &&
							$emp['pagibig_number'] != "" &&
							strtotime($emp['barangay_clearance']) > strtotime("now") &&
							strtotime($emp['nbi_clearance']) > strtotime("now")
						){
								echo '<i class="fas fa-check-circle tx-success tx-24"></i>';
						} else {
								echo '<i class="fas fa-times-circle tx-danger tx-24"></i>';
						}
					?>
				</td>
				<td class="align-middle"><?= $emp['emergency_contact_name']; ?> (<?= $emp['emergency_contact_relation']; ?>)<br><?= $emp['emergency_contact_number']; ?></td>
				<td class="align-middle">
					<div class="btn-group">
                        <button type="button" class="btn btn-success btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-emp-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($emp['employee_id'])); ?>"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-primary btn-icon btn-sm edit-data" data-toggle="modal" data-target="#modaledit" data-emp-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($emp['employee_id'])); ?>"><i class="fas fa-edit"></i></button>
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
            <form action="" method="post" id="editemp">
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

        $(".edit-data").on("click", function() {
            // Show the modal
            var eID = $(this).data('emp-id');
            // Fetch data using AJAX

            $.ajax({
                url: "<?= site_url('employee/getinfo/')?>" + eID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {
                    
                    $('#editemp').attr('action', "<?= site_url("employee/update/");?>" + eID);

                    if(data.status == 'active'){
                        $(".ai").html(`
                            <form id="" action="<?= site_url("employee/deactivate/");?>${eID}" method="post">
                                <button type="submit" class="btn btn-danger">Deactivate</button>
                            </form>
                        `);
                    }else if(data.status == 'inactive'){
                        $(".ai").html(`
                            <form id="" action="<?= site_url("employee/activate/");?>${eID}" method="post">
                                <button type="submit" class="btn btn-primary">Activate</button>
                            </form>
                        `);   
                    }

                    var m,f;
                    switch (data.gender) {
                        case 'male': m = 'checked'; f = ''; break;
                        case 'female': m = ''; f = 'checked'; break;
                    }

                    var std,stm;
                    switch (data.salary_type){
                        case 'daily': std = 'checked'; stm = ''; break;
                        case 'day': std = 'checked'; stm = ''; break;
                        case 'month': std = ''; stm = 'checked'; break;
                    }

                    var psy,psn;
                    switch (data.has_payslip){
                        case 'yes': psy = 'checked'; psn = ''; break;
                        case 'no': psy = ''; psn = 'checked'; break;
                    }

                    $(".modal-title").html(`${data.first_name} ${data.last_name} Information`);
                    // Populate the modal body with the fetched data
                    $(".edit-body").html(
                        `
                        <div class="row">
                            <h5 class="col-lg-12 mg-b-30">Personal Information</h5>
                            <div class="form-group col-lg-6">
                                <label class="d-block">First Name</label>
                                <input type="text" class="form-control" name="first-name" value="${data.first_name}" required autofocus>
                            </div>
                            <div class="form-group col-lg-2">
                                <label class="d-block">Middle Name</label>
                                <input type="text" class="form-control" name="middle-name" value="${data.middle_name}" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="d-block">Last Name</label>
                                <input type="text" class="form-control" name="last-name" value="${data.last_name}" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="d-block">Address</label>
                                <input type="text" class="form-control" name="address" value="${data.address}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="d-block">Contact Number</label>
                                <input type="text" class="form-control" name="contact-number" value="${data.contact_number}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="d-block">Gender</label>
                                <div class="col-form-label">
                                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                                        <input type="radio" class="custom-control-input" id="gender-male" name="gender" value="male" ${m}>
                                        <label class="custom-control-label" for="gender-male">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio d-inline-block">
                                        <input type="radio" class="custom-control-input" id="gender-female" name="gender" value="female" ${f}>
                                        <label class="custom-control-label" for="gender-female">Female</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Birthday</label>
                                <input type="date" class="form-control" data-type="datepicker" name="birthday" value="${data.birthday}" required>
                            </div>

                            <h5 class="col-lg-12 mg-t-30 mg-b-30">Employment Records</h5>

                            <div class="form-group col-lg-3">
                                <label class="d-block">ID Number</label>
                                <label class="d-block">-</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Position</label>
                                <input type="text" class="form-control" name="position" value="${data.position}">
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Assigned Branch</label>
                                <select name="branch" class="custom-select" required>
                                    <option value="${data.assigned_branch}" selected>${data.branch}</option>
                                    <?php foreach($branch as $b){?>
                                    <option value="<?= $encrypter->encrypt($b['branch_id']); ?>"><?= $b['name']; ?></option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Date Hired</label>
                                <input type="date" class="form-control" data-type="datepicker" name="date-hired" value="${data.date_hired}" required>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Metrobank Account Number</label>
                                <input type="text" class="form-control" name="metrobank-account-number" value="${data.metrobank_account_number}">
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Salary</label>
                                <input type="number" class="form-control" name="salary" value="${data.salary}" required>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Salary Type</label>
                                <div class="col-form-label">
                                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                                        <input type="radio" class="custom-control-input" id="salary-type-day" name="salary-type" value="daily" ${std}>
                                        <label class="custom-control-label" for="salary-type-day">Day</label>
                                    </div>

                                    <div class="custom-control custom-radio d-inline-block">
                                        <input type="radio" class="custom-control-input" id="salary-type-month" name="salary-type" value="month" ${stm}>
                                        <label class="custom-control-label" for="salary-type-month">Month</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Has Payslip</label>
                                <div class="col-form-label">
                                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                                        <input type="radio" class="custom-control-input" id="has-payslip-yes" name="has-payslip" value="yes" ${psy}>
                                        <label class="custom-control-label" for="has-payslip-yes">Yes</label>
                                    </div>

                                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                                        <input type="radio" class="custom-control-input" id="has-payslip-no" name="has-payslip" value="no" ${psn}>
                                        <label class="custom-control-label" for="has-payslip-no">No</label>
                                    </div>
                                </div>
                            </div>

                            

                            <h5 class="col-lg-12 mg-t-30 mg-b-30">Government Records</h5>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Tin Number</label>
                                <input type="text" class="form-control" name="tin-number" value="${data.tin_number}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">SSS Number</label>
                                <input type="text" class="form-control" name="sss-number" value="${data.sss_number}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Philhealth Number</label>
                                <input type="text" class="form-control" name="philhealth-number" value="${data.philhealth_number}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Pag-ibig Number</label>
                                <input type="text" class="form-control" name="pagibig-number" value="${data.pagibig_number}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Barangay Clearance</label>
                                <input type="date" class="form-control" data-type="datepicker" name="barangay-clearance" value="${data.barangay_clearance}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">NBI Clearance</label>
                                <input type="date" class="form-control" data-type="datepicker" name="nbi-clearance" value="${data.nbi_clearance}">
                            </div>

                            <h5 class="col-lg-12 mg-t-30 mg-b-30">Emergency Contact Person</h5>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Name</label>
                                <input type="text" class="form-control" name="emergency-contact-person" value="${data.emergency_contact_name}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Contact Number</label>
                                <input type="text" class="form-control" name="emergency-contact-number" value="${data.emergency_contact_number}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Relation</label>
                                <input type="text" class="form-control" name="emergency-contact-relation" value="${data.emergency_contact_relation}">
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




        $(".load-data").on("click", function() {
            // Show the modal
            var eID = $(this).data('emp-id');
            // Fetch data using AJAX

            var span;
            $.ajax({
                url: "<?= site_url('employee/getinfo/')?>" + eID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    if(data.status == 'active'){
                        span = `<span class="badge badge-success">${ data.status }</span>`;
                        $(".ai").html(`
                            <form id="" action="<?= site_url("employee/deactivate/");?>${eID}" method="post">
                                <button type="submit" class="btn btn-danger">Deactivate</button>
                            </form>
                        `);
                    }else if(data.status == 'inactive'){
                        span = `<span class="badge badge-danger">${ data.status }</span>`;
                        $(".ai").html(`
                            <form id="" action="<?= site_url("employee/activate/");?>${eID}" method="post">
                                <button type="submit" class="btn btn-primary">Activate</button>
                            </form>
                        `);   
                    }

                    $(".modal-title").html(`${data.first_name} ${data.last_name} Information`);
                    // Populate the modal body with the fetched data
                    $(".body").html(
                        `
                        <div class="row">
                            <h5 class="col-lg-12 mg-b-30">Personal Information</h5>

                            <div class="form-group col-lg-6">
                                <label class="d-block">First Name</label>
                                <label class="d-block">${data.first_name}</label>
                            </div>

                            <div class="form-group col-lg-2">
                                <label class="d-block">Middle Name</label>
                                <label class="d-block">${data.middle_name}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Last Name</label>
                                <label class="d-block">${data.last_name}</label>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="d-block">Address</label>
                                <label class="d-block">${data.address}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Contact Number</label>
                                <label class="d-block">${data.contact_number}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Gender</label>
                                <label class="d-block">${data.gender}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Birthday</label>
                                <label class="d-block">${data.birthday}</label>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="d-block">Status</label>
                                <label class="d-block">
                                    ${span}
                                </label>
                            </div>

                            <h5 class="col-lg-12 mg-t-30 mg-b-30">Employment Records</h5>

                            <div class="form-group col-lg-4">
                                <label class="d-block">ID Number</label>
                                <label class="d-block">-</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Assigned Branch</label>
                                <label class="d-block">${data.branch}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Metrobank Account Number</label>
                                <label class="d-block">${data.metrobank_account_number}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Salary</label>
                                <label class="d-block">${data.salary} / ${data.salary_type}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Date Hired</label>
                                <label class="d-block">${data.date_hired}</label>
                            </div>

                            <h5 class="col-lg-12 mg-t-30 mg-b-30">Government Records</h5>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Tin Number</label>
                                <label class="d-block">${data.tin_number}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">SSS Number</label>
                                <label class="d-block">${data.sss_number}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Philhealth Number</label>
                                <label class="d-block">${data.philhealth_number}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Pag-ibig Number</label>
                                <label class="d-block">${data.pagibig_number}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Barangay Clearance</label>
                                <label class="d-block">${data.barangay_clearance}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">NBI Clearance</label>
                                <label class="d-block">${data.barangay_clearance}</label>
                            </div>

                            <h5 class="col-lg-12 mg-t-30 mg-b-30">Emergency Contact Person</h5>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Name</label>
                                <label class="d-block">${data.emergency_contact_name}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Contact Number</label>
                                <label class="d-block">${data.emergency_contact_number}</label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label class="d-block">Relation</label>
                                <label class="d-block">${data.emergency_contact_relation}</label>
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