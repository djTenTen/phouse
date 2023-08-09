<?php
    $rental_model = new \App\Models\Rental_model;
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <h1>View Contract</h1>

    <div class="row mg-b-30">
        <div class="col-lg-6 mg-b-10">
            <label class="d-block">Contract ID</label>
            <label class="d-block"><?= $ct['contract_id']; ?></label>
        </div>
        <div class="col-lg-6 mg-b-10">
            <label class="d-block">Status</label>
            <?php if($ct['status'] == 'pending'){ ?><span class="badge badge-primary">Pending</span><?php } ?>
            <?php if($ct['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
            <?php if($ct['status'] == 'cancelled'){ ?><span class="badge badge-warning">Cancelled</span><?php } ?>
            <?php if($ct['status'] == 'terminated'){ ?><span class="badge badge-danger">Terminated</span><?php } ?>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Tenant</label>
            <label class="d-block"><?= $ct['tenant']; ?></label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Branch</label>
            <label class="d-block"><?= $ct['branch']; ?></label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Commission</label>
            <label class="d-block"><?= $ct['commission']; ?>%</label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Monthly Rate</label>
            <label class="d-block"><?= number_format($ct['monthly_rate'], 2); ?></label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Slot Number</label>
            <label class="d-block"><?= $ct['slot_number']; ?></label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Contract Start</label>
            <label class="d-block"><?= date_format(date_create($ct['start']),"M. d, Y"); ?></label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Contract End</label>
            <label class="d-block"><?= date_format(date_create($ct['end']),"M. d, Y"); ?></label>
        </div>

        <div class="col-lg-3 mg-b-10">
            <label class="d-block">Contract Duration</label>
            <label class="d-block"><?= $ct['duration']; ?> Month/s</label>
        </div>
    </div>


    <div class="contract-payment">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 15%;">Coverage</th>
                    <th style="width: 85%;">Payment</th>
                </tr>
            </thead>
            <tbody>
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
                                    <th style="width: 1%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            if(!empty($secdep)){
                                foreach($secdep as $sp){
                            ?>
                                <tr>
                                    <td class="align-middle"><?= ucwords(str_replace("-", " ", $sp['type'])); ?></td>
                                    <td class="align-middle"><?= $sp['bank']; ?></td>
                                    <td class="align-middle"><?= $sp['check_number']; ?></td>
                                    <td class="align-middle"><?= number_format($sp['amount'], 2); ?></td>
                                    <td class="align-middle"><?= date_format(date_create($sp['date']),"M. d, Y"); ?></td>
                                    <td class="align-middle">
                                        <?php if($sp['status'] == 'pending'){ ?><span class="badge badge-primary">Pending</span><?php } ?>
                                        <?php if($sp['status'] == 'clearing'){ ?><span class="badge badge-info">Clearing</span><?php } ?>
                                        <?php if($sp['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
                                        <?php if($sp['status'] == 'bounced'){ ?><span class="badge badge-danger">Bounced</span><?php } ?>
                                        <?php if($sp['status'] == 'returned'){ ?><span class="badge badge-warning">Returned</span><?php } ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if($sp['status'] == 'pending'){ ?><a href="#" class="btn btn-icon btn-primary btn-xs"><i class="fas fa-money-check-edit-alt"></i></a><?php } ?>
                                        <?php if($sp['status'] != 'pending'){ ?><a href="#" class="btn btn-icon btn-primary btn-xs"><i class="fas fa-search"></i></a><?php } ?>
                                    </td>
                                </tr>
                                <?php }?>
                            <?php }else{?>
                                    <tr><td colspan="7"><h5 class="text-center mg-t-30 mg-b-30">No records found.</h5></td></tr>
                            <?php }?>
                                
                            
                            </tbody>
                        </table>
                    </td>
                </tr>

                <?php
                    $ctrl = $ct['start'];
                    $i = 0;
                    while($i < $ct['duration']){
                        $i++;
                ?>
                <tr>
                    <td class="align-middle">Month <?= $i; ?><br><br><?= date("M. j, Y", strtotime($ctrl)) . "<br>to<br>" . date("M. j, Y", strtotime($ctrl . " +1 month -1 day")); ?></td>
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
                                    <th style="width: 1%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $q = $rental_model->getMonthly($ct['contract_id'],$i);
                                    if(!empty($q)){
                                        foreach($q as $mt){
                                ?>
                                            <tr>
                                                <td class="align-middle"><?= ucwords(str_replace("-", " ", $mt['type'])); ?></td>
                                                <td class="align-middle"><?= $mt['bank']; ?></td>
                                                <td class="align-middle"><?= $mt['check_number']; ?></td>
                                                <td class="align-middle"><?= number_format($mt['amount'], 2); ?></td>
                                                <td class="align-middle"><?= date_format(date_create($mt['date']),"M. d, Y"); ?></td>
                                                <td class="align-middle">
                                                    <?php if($mt['status'] == 'pending'){ ?><span class="badge badge-primary">Pending</span><?php } ?>
                                                    <?php if($mt['status'] == 'clearing'){ ?><span class="badge badge-info">Clearing</span><?php } ?>
                                                    <?php if($mt['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
                                                    <?php if($mt['status'] == 'bounced'){ ?><span class="badge badge-danger">Bounced</span><?php } ?>
                                                    <?php if($mt['status'] == 'returned'){ ?><span class="badge badge-warning">Returned</span><?php } ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php if($mt['status'] == 'pending'){ ?><a href="#" class="btn btn-icon btn-primary btn-xs"><i class="fas fa-money-check-edit-alt"></i></a><?php } ?>
                                                    <?php if($mt['status'] != 'pending'){ ?><a href="#" class="btn btn-icon btn-primary btn-xs"><i class="fas fa-search"></i></a><?php } ?>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr><td colspan="7"><h5 class="text-center mg-t-30 mg-b-30">No records found.</h5></td></tr>
                                <?php } ?>
                           
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
                        $ctrl = date("M. j, Y", strtotime($ctrl . " +1 month"));
                    }
                ?>
                <tr>
                    <td class="align-middle">Refunds</td>
                    <td>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Bank Account</th>
                                    <th style="width: 15%;">Check Number</th>
                                    <th style="width: 15%;">Amount</th>
                                    <th style="width: 15%;">Date</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 30%;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php 
                            if(!empty($refunds)){

                                foreach($refunds as $rf){
                        ?>
                                <tr>
                                    <td class="align-middle"><?= $rf['bank_account']; ?></td>
                                    <td class="align-middle"><?= $rf['check_number']; ?></td>
                                    <td class="align-middle"><?= number_format($rf['amount'], 2); ?></td>
                                    <td class="align-middle"><?= date_format(date_create($rf['date']),"M. d, Y"); ?></td>
                                    <td class="align-middle">
                                        <?php if($rf['status'] == 'pending'){ ?><span class="badge badge-primary">Pending</span><?php } ?>
                                        <?php if($rf['status'] == 'completed'){ ?><span class="badge badge-success">Completed</span><?php } ?>
                                        <?php if($rf['status'] == 'cancelled'){ ?><span class="badge badge-warning">Cancelled</span><?php } ?>
                                    </td>
                                    <td class="align-middle"><?= $rf['remarks']; ?></td>
                                </tr>
                                <?php } ?>

                            <?php } else {?>
                                    <tr><td colspan="6"><h5 class="text-center mg-t-30 mg-b-30">No records found.</h5></td></tr>
                            <?php } ?>
                        
                            </tbody>
                        </table>
                    </td>
                </tr>


            </tbody>
        </table>    

 
            <button type="button" class="btn btn-primary btn-icon edit-data" data-toggle="modal" data-target="#addpayment">Add Payment</button>
            <button type="button" class="btn btn-warning btn-icon edit-data" data-toggle="modal" data-target="#issuerefund">Issue Refund</button>
            <button type="button" class="btn btn-success btn-icon edit-data" data-toggle="modal" data-target="#publish">Publish</button>
            
        

</div>


<!-- MODAL ADD PAYMENT -->
<!-- Modal -->
<div class="modal fade" id="publish" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" >Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Are you sure to publish this Contract?
        </div>
        <div class="modal-footer">
            <?= form_open("contract/publish/".str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ct['contract_id'])));?>
                    <button type="submit" class="btn btn-primary">Confirm</button>
            <?= form_close();?>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL ADD PAYMENT -->




<!-- MODAL ADD PAYMENT -->
<!-- Modal -->
<div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" ></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?= form_open("contract/addpayment/".str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ct['contract_id'])));?>
                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Month</label>
                    <div class="col-lg-9">
                        <select class="custom-select" name="identifier" required>
                            <option value="" selected>Select Month</option>
                            <option value="security-deposit">Security Deposit</option>
                            <?php
                                $ctrl = $ct['start'];
                                $i = 0;
                                while($i < $ct['duration']){
                                    $i++;
                            ?>
                            <option value="month-<?php echo $i; ?>"><?php echo date("M. j, Y", strtotime($ctrl)) . " - " . date("M. j, Y", strtotime($ctrl . " +1 month -1 day")); ?></option>
                            <?php
                                    $ctrl = date("M. j, Y", strtotime($ctrl . " +1 month"));
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Mode of Payment</label>
                    <div class="col-lg-9">
                        <select class="custom-select" name="type" required>
                            <option value="" selected>Select Payment Mode</option>
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="bank-deposit">Bank Deposit</option>
                            <option value="online-bank-transfer">Online Bank Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Bank</label>
                    <div class="col-lg-9">
                        <select class="custom-select" name="bank">
                            <option value="" selected>Select Bank</option>
                            <?php foreach($bank as $bk){ ?>
                            <option value="<?= $encrypter->encrypt($bk['bank_id']); ?>"><?= $bk['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Check Number</label>
                    <div class="col-lg-9"><input type="text" class="form-control" name="check-number" value=""></div>
                </div>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Amount</label>
                    <div class="col-lg-9"><input type="text" class="form-control" name="amount" value="" required></div>
                </div>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Date</label>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-4">
                                <select name="mm" class="form-control" required>
                                    <option value="<?= date("m")?>" selected><?= date("F")?></option>
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
                                    <option value="<?= date("d")?>" selected><?= date("d")?></option>
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
                                    <option value="<?= date("Y")?>" selected><?= date("Y")?></option>
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


        </div>
        <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Payment</button>
                </form>
            <div class="ai"></div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL ADD PAYMENT -->



<!-- MODAL REFUND -->
<!-- Modal -->
<div class="modal fade" id="issuerefund" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" ></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?= form_open("contract/addrefund/".str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ct['contract_id'])));?>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Bank Account</label>
                    <div class="col-lg-9">
                        <select class="custom-select" name="bank-account">
                            <option value="" selected>Select Bank</option>
                            <?php foreach($bank as $bnk){ ?>
                            <option value="<?= $encrypter->encrypt($bnk['bank_id']); ?>"><?= $bnk['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Check Number</label>
                    <div class="col-lg-9"><input type="text" class="form-control" name="check-number" value=""></div>
                </div>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Amount</label>
                    <div class="col-lg-9"><input type="text" class="form-control" name="amount" value="" required></div>
                </div>

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Date</label>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-4">
                                <select name="mm" class="form-control" required>
                                    <option value="<?= date("m")?>" selected><?= date("F")?></option>
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
                                    <option value="<?= date("d")?>" selected><?= date("d")?></option>
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
                                    <option value="<?= date("Y")?>" selected><?= date("Y")?></option>
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

                <div class="form-group row">
                    <label class="d-block col-form-label col-lg-3">Remarks</label>
                    <div class="col-lg-9">
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                </div>


        </div>
        <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Issue Refund</button>
                </form>
            <div class="ai"></div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
        </div>
        </div>
    </div>
</div>
<!-- END OF MODAL REFUND -->
