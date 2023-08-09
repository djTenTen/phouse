<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['payment_completed'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Payment Registration Success!</h4>
            <p>Payment has been successfully posted</p>
        </div>';
            unset($_SESSION['payment_completed']);
        }  
    ?>

    <h1>Account Payable</h1>

    <div class="row">
        <div class="col-8">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 1%;">ID</th>
                        <th style="width: 30%;">Description</th>
                        <th style="width: 50%;">Payment Details</th>
                        <th style="width: 20%;">Amount</th>
                        <th style="width: 1%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $bnk = []; 
                        foreach($payable as $pay){
                            $data = [
                                'bankid' => $pay['bank_account_id'], 
                                'amount' => $pay['amount']
                            ];
                            array_push($bnk, $data);
                        ?>
                    <tr>
                        <td><?= $pay['expense_id']; ?></td>
                        <td><?= $pay['payee']; ?></td>
                        <td>[<?= $pay['bank']; ?>] <?= $pay['name'] ?> (<?= $pay['account_number']; ?>)<br><?= ucwords($pay['type']); ?><?php if($pay['type'] == 'check'){ echo '<br>Check No. #'.$pay['check_number']; } ?><br><?= date_format(date_create($pay['date']),"F d, Y"); ?></td>
                        <td class="text-right">₱ <?= number_format($pay['amount'], 2); ?></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modaledit<?= $pay['expense_payment_id']; ?>"><i class="fas fa-edit"></i></button>
                                
                            <!-- MODAL VIEW -->
                            <!-- Modal -->
                            <div class="modal fade" id="modaledit<?= $pay['expense_payment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update Payment Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    
                                        <?= form_open('account/payable/update/'.str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($pay['expense_payment_id'])));?>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Bank Account</td>
                                                    <td>[<?= $pay['bank']; ?>] <?= $pay['name'] ?> (<?= $pay['account_number']; ?>)</td>
                                                </tr>
                                                <tr>
                                                    <td>Type</td>
                                                    <td><?= $pay['type']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Date</td>
                                                    <td><?= date_format(date_create($pay['date']),"F d, Y"); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Check No.</td>
                                                    <td><?= $pay['check_number']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Amount</td>
                                                    <td>₱ <?= number_format($pay['amount'],2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Date Cleared</td>
                                                    <td>
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

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                    <div class="modal-footer">

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END OF MODAL VIEW -->
                        
                        </td>
                    </tr>
                    <?php } ?>
                    
                </tbody>
            </table>

        </div>
        <?php //print_r($bnk);?>
        <div class="col-4">
  
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Bank Account</th>
						<th>Payments Due</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $gtotal = 0;
                        foreach($paymentdues as $dues){
                            $total = 0;
                            foreach($bnk as $b){
                                if($b['bankid'] == $dues['bank_account_id']){
                                    $total += $b['amount'];
                                }
                            }
                        $gtotal += $total;
                    ?>
                          
                    <tr>
                        <td><?= $dues['bank']; ?> <br> <?= $dues['name']; ?> <br> <?= $dues['account_number']; ?></td>
                        <td class="text-right">₱ <?= number_format($total,2);?></td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td class="text-right">Total</td>
                        <td class="text-right">₱ <?= number_format($gtotal,2);?></td>
                    </tr>
                </tbody>

            </table>
            
        </div>

    </div>
    
</div>