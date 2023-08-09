<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['employee_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Employee Registration Success!</h4>
            <p>Employee has been successfully registered</p>
        </div>';
            unset($_SESSION['employee_added']);
        }  
    ?>


    <h1>Add Employee</h1>

    <?= form_open("employee/save")?>

        <div class="row">
            <h5 class="col-lg-12 mg-b-30">Personal Information</h5>

            <div class="form-group col-lg-6">
                <label class="d-block">First Name</label>
                <input type="text" class="form-control" name="first-name" value="" required autofocus>
            </div>

            <div class="form-group col-lg-2">
                <label class="d-block">Middle Name</label>
                <input type="text" class="form-control" name="middle-name" value="" required>
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Last Name</label>
                <input type="text" class="form-control" name="last-name" value="" required>
            </div>

            <div class="form-group col-lg-12">
                <label class="d-block">Address</label>
                <input type="text" class="form-control" name="address" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Contact Number</label>
                <input type="text" class="form-control" name="contact-number" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Gender</label>
                <div class="col-form-label">
                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                        <input type="radio" class="custom-control-input" id="gender-male" name="gender" value="male" checked>
                        <label class="custom-control-label" for="gender-male">Male</label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block">
                        <input type="radio" class="custom-control-input" id="gender-female" name="gender" value="female">
                        <label class="custom-control-label" for="gender-female">Female</label>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Birthday</label>
                <input type="date" class="form-control" data-type="datepicker" name="birthday" value="" required>
            </div>

            <h5 class="col-lg-12 mg-t-30 mg-b-30">Employment Records</h5>

            <div class="form-group col-lg-3">
                <label class="d-block">ID Number</label>
                <label class="d-block">-</label>
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Position</label>
                <input type="text" class="form-control" name="position" value="">
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Assigned Branch</label>
                <select name="branch" class="custom-select" required>
                    <option selected>Select Branch</option>
                    <?php foreach($branch as $b){?>
                    <option value="<?=$encrypter->encrypt($b['branch_id']); ?>"><?= $b['name']; ?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Date Hired</label>
                <input type="date" class="form-control" data-type="datepicker" name="date-hired" value="" required>
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Metrobank Account Number</label>
                <input type="text" class="form-control" name="metrobank-account-number" value="">
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Salary</label>
                <input type="number" class="form-control" name="salary" value="" required>
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Salary Type</label>
                <div class="col-form-label">
                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                        <input type="radio" class="custom-control-input" id="salary-type-day" name="salary-type" value="daily" checked>
                        <label class="custom-control-label" for="salary-type-day">Day</label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block">
                        <input type="radio" class="custom-control-input" id="salary-type-month" name="salary-type" value="month">
                        <label class="custom-control-label" for="salary-type-month">Month</label>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-3">
                <label class="d-block">Has Payslip</label>
                <div class="col-form-label">
                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                        <input type="radio" class="custom-control-input" id="has-payslip-yes" name="has-payslip" value="yes" checked>
                        <label class="custom-control-label" for="has-payslip-yes">Yes</label>
                    </div>

                    <div class="custom-control custom-radio mg-r-10 d-inline-block">
                        <input type="radio" class="custom-control-input" id="has-payslip-no" name="has-payslip" value="no">
                        <label class="custom-control-label" for="has-payslip-no">No</label>
                    </div>
                </div>
            </div>

            

            <h5 class="col-lg-12 mg-t-30 mg-b-30">Government Records</h5>

            <div class="form-group col-lg-4">
                <label class="d-block">Tin Number</label>
                <input type="text" class="form-control" name="tin-number" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">SSS Number</label>
                <input type="text" class="form-control" name="sss-number" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Philhealth Number</label>
                <input type="text" class="form-control" name="philhealth-number" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Pag-ibig Number</label>
                <input type="text" class="form-control" name="pagibig-number" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Barangay Clearance</label>
                <input type="date" class="form-control" data-type="datepicker" name="barangay-clearance" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">NBI Clearance</label>
                <input type="date" class="form-control" data-type="datepicker" name="nbi-clearance" value="">
            </div>

            <h5 class="col-lg-12 mg-t-30 mg-b-30">Emergency Contact Person</h5>

            <div class="form-group col-lg-4">
                <label class="d-block">Name</label>
                <input type="text" class="form-control" name="emergency-contact-person" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Contact Number</label>
                <input type="text" class="form-control" name="emergency-contact-number" value="">
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Relation</label>
                <input type="text" class="form-control" name="emergency-contact-relation" value="">
            </div>
        </div>

        <div class="mg-t-20">
            <button class="btn btn-primary mg-r-15" name="submit" type="submit">Register Employee</button>
        </div>
        
    </form>

</div>