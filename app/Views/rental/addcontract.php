<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['contract_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Contract Registration Success!</h4>
            <p>Contract has been successfully created</p>
        </div>';
            unset($_SESSION['contract_added']);
        }  
    ?>


    <h1>Add Contract</h1>


    <div id="add-contract-page">

        <?= form_open("contract/save")?>
            <div class="row">
                <div class="form-group col-lg-3">
                    <label class="d-block">Name</label>
                    <select class="custom-select" name="tenant" required>
                        <option value="" selected>Select Tenant</option>
                        <?php foreach($tenants as $t){?>
                        <option value="<?= $encrypter->encrypt($t['tenant_id']); ?>"><?= $t['name']; ?></option>
                        <?php }?>
                    </select>
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Branch</label>
                    <select class="custom-select" name="branch" required>
                        <option value="">-</option>
                        <?php foreach($branch as $b){?>
                        <option value="<?= $encrypter->encrypt($b['branch_id']); ?>"><?= $b['name']; ?></option>
                        <?php }?>
                    </select>
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Commission</label>
                    <input type="number" class="form-control" name="commission" min="0" value="">
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Monthly Rate</label>
                    <input type="number" class="form-control" name="monthly-rate" min="0" value="">
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Slot Number</label>
                    <input type="text" class="form-control" name="slot-number" value="">
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Contract Start</label>
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
                    <!-- <input type="text" class="form-control" name="contract-start" data-type="datepicker" value=""> -->
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Contract End</label>
                    <span data-name="contract-end" class="d-block col-form-label"></span>
                </div>

                <div class="form-group col-lg-3">
                    <label class="d-block">Contract Duration</label>
                    <select class="custom-select" name="contract-duration" required>
                        <option value="">-</option>
                        <option value="1">1 Month</option>
                        <option value="2">2 Months</option>
                        <option value="3">3 Months</option>
                        <option value="4">4 Months</option>
                        <option value="5">5 Months</option>
                        <option value="6">6 Months</option>
                        <option value="7">7 Months</option>
                        <option value="8">8 Months</option>
                        <option value="9">9 Months</option>
                        <option value="10">10 Months</option>
                        <option value="11">11 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </div>
            </div>

            <div class="contract-payments">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td style="width: 10%;">Coverage</td>
                            <td style="width: 90%;">Payment</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-name="security-deposit">
                            <td class="align-middle">Security Deposit</td>
                            <td>
                                <div class="table-repeater">
                                    <table class="table table-bordered" data-max-row="5">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%;">Type</th>
                                                <th style="width: 20%;">Bank</th>
                                                <th style="width: 15%;">Check No.</th>
                                                <th style="width: 15%;">Amount</th>
                                                <th style="width: 30%;">Date</th>
                                                <th style="width: 1%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="secdep">
                                                <tr>
                                                    <td>
                                                        <select class="custom-select" name="security-deposit[type][]">
                                                            <option value="" selected>Select Type</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="check">Check</option>
                                                            <option value="bank-deposit">Bank Deposit</option>
                                                            <option value="online-bank-transfer">Online Bank Transfer</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="custom-select" name="security-deposit[bank][]">
                                                            <option value="" selected>Select Bank</option>
                                                            <?php foreach($bank as $bk){ ?>
                                                            <option value="<?= $encrypter->encrypt($bk['bank_id']); ?>"><?= $bk['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="security-deposit[check-no][]"></td>
                                                    <td><input type="text" class="form-control" name="security-deposit[amount][]"></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <select name="security-deposit[mm][]" class="form-control" required>
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
                                                                <select name="security-deposit[dd][]" class="form-control" required>
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
                                                                <select name="security-deposit[yy][]" class="form-control" required>
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
                                                    </td>
                                                    <td><button class="btn btn-icon btn-danger btn-xs remove" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
                                                </tr>

                                                <div></div>
                                            </div>
                                        </tbody>
                                    </table>

                                    <button class="btn btn-primary float-right" type="button" id="addsecuritydep">Add Row</button>
                                </div>
                            </td>
                        </tr>

                        <?php
                            $i = 0;

                            while($i < 12){
                                $i++;
                        ?>
                        <tr data-name="month-<?php echo $i; ?>" class="d-none">
                            <td class="align-middle">Month <?php echo $i; ?><br><span></span></td>
                            <td>
                                <div class="table-repeater">
                                    <table class="table table-bordered" data-max-row="5">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%;">Type</th>
                                                <th style="width: 20%;">Bank</th>
                                                <th style="width: 15%;">Check No.</th>
                                                <th style="width: 15%;">Amount</th>
                                                <th style="width: 30%;">Date</th>
                                                <th style="width: 1%;"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="paymentcontract<?= $i; ?>">
                                        
                                            <tr>
                                                <td>
                                                    <select class="custom-select" name="month[<?= $i; ?>][type][]" disabled>
                                                        <option value="" selected>Select Type</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="check">Check</option>
                                                        <option value="bank-deposit">Bank Deposit</option>
                                                        <option value="online-bank-transfer">Online Bank Transfer</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="custom-select" name="month[<?php echo $i; ?>][bank][]" disabled>
                                                        <option value="" selected>Select Bank</option>
                                                        <?php foreach($bank as $bk){ ?>
                                                        <option value="<?= $encrypter->encrypt($bk['bank_id']); ?>"><?= $bk['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="month[<?php echo $i; ?>][check-no][]" disabled></td>
                                                <td><input type="text" class="form-control" name="month[<?php echo $i; ?>][amount][]" disabled></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <select name="month[<?php echo $i; ?>][mm][]" class="form-control" required>
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
                                                            <select name="month[<?php echo $i; ?>][dd][]" class="form-control" required>
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
                                                            <select name="month[<?php echo $i; ?>][yy][]" class="form-control" required>
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
                                                
                                                </td>
                                                <td><button class="btn btn-icon btn-danger btn-xs removepayment" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
                                            </tr>

                                            <script>
                                                $(document).ready(function() {

                                                    // Event listener for the duplication button
                                                    $("#addpayment<?= $i; ?>").click(function() {

                                                        var rowTemplate = `
                                                        <tr>
                                                            <td>
                                                                <select class="custom-select" name="month[<?php echo $i; ?>][type][]" >
                                                                    <option value="" selected>Select Type</option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="check">Check</option>
                                                                    <option value="bank-deposit">Bank Deposit</option>
                                                                    <option value="online-bank-transfer">Online Bank Transfer</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="custom-select" name="month[<?php echo $i; ?>][bank][]" >
                                                                    <option value="" selected>Select Bank</option>
                                                                    <?php foreach($bank as $bk){ ?>
                                                                    <option value="<?= $encrypter->encrypt($bk['bank_id']); ?>"><?= $bk['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control" name="month[<?php echo $i; ?>][check-no][]" ></td>
                                                            <td><input type="text" class="form-control" name="month[<?php echo $i; ?>][amount][]" ></td>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <select name="month[<?php echo $i; ?>][mm][]" class="form-control" required>
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
                                                                        <select name="month[<?php echo $i; ?>][dd][]" class="form-control" required>
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
                                                                        <select name="month[<?php echo $i; ?>][yy][]" class="form-control" required>
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
                                                            </td>
                                                            <td><button class="btn btn-icon btn-danger btn-xs removepayment" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
                                                        </tr>
                                                        `;
                                                        
                                                        $(".paymentcontract<?= $i; ?>").append(rowTemplate);
                                                    });

                                                    // Optional: Event delegation for the remove button (if rows can be removed)
                                                    $(".paymentcontract<?= $i; ?>").on("click", ".removepayment", function() {
                                                        $(this).closest("tr").remove();
                                                    });


                                                });

                                            </script>

                                        </tbody>
                                    </table>

                                    <button class="btn btn-primary float-right" type="button" id="addpayment<?= $i; ?>" >Add Row</button>

                                </div>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>

                    </tbody>
                </table>
            </div>

            <div class="mg-t-20">
                <button class="btn btn-primary mg-r-15" name="submit" type="submit">Add Contract</button>
            </div>
        </form>
    </div>

</div>


<script>

    $(document).ready(function() {

        // Event listener for the duplication button
        $("#addsecuritydep").click(function() {
            var rowTemplate = `
            <tr>
                <td>
                    <select class="custom-select" name="security-deposit[type][]">
                        <option value="" selected>Select Type</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="bank-deposit">Bank Deposit</option>
                        <option value="online-bank-transfer">Online Bank Transfer</option>
                    </select>
                </td>
                <td>
                    <select class="custom-select" name="security-deposit[bank][]">
                        <option value="" selected>Select Bank</option>
                        <?php foreach($bank as $bk){ ?>
                        <option value="<?= $encrypter->encrypt($bk['bank_id']); ?>"><?= $bk['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><input type="text" class="form-control" name="security-deposit[check-no][]"></td>
                <td><input type="text" class="form-control" name="security-deposit[amount][]"></td>
                <td>
                    <div class="row">
                        <div class="col-4">
                            <select name="security-deposit[mm][]" class="form-control" required>
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
                            <select name="security-deposit[dd][]" class="form-control" required>
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
                            <select name="security-deposit[yy][]" class="form-control" required>
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
                </td>
                <td><button class="btn btn-icon btn-danger btn-xs remove" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
            </tr>
            `;
            $("#secdep").append(rowTemplate);
        });

        // Optional: Event delegation for the remove button (if rows can be removed)
        $("#secdep").on("click", ".remove", function() {
            $(this).closest("tr").remove();
        });


    });

</script>

