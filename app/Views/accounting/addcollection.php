<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

	<?php
        // Message thrown from the controller
        if(!empty($_SESSION['collection_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Collection Registration Success!</h4>
            <p>Collection has been successfully registered</p>
        </div>';
            unset($_SESSION['collection_added']);
        }  
    ?>

    <h1>Add Collection</h1>

    <?= form_open("collection/save")?>
		<div class="row">
			<div class="form-group col-lg-3">
				<label class="d-block">Bank Account</label>
				<select class="custom-select" name="bank-account" required>
					<option value="" selected>Select Bank</option>
					<?php foreach($bankaccount as $ba){?>		
                        <option value="<?= $encrypter->encrypt($ba['bank_account_id']);?>">[<?= $ba['bank'];?>]<?= $ba['name'];?></option>
                    <?php }?>
				</select>
			</div>

			<div class="form-group col-lg-3">
				<label class="d-block">Deposit Date</label>
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

		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="width: 1%;"></th>
					<th style="width: 1%;">ID</th>
					<th style="width: 15%;">Tenant</th>
					<th style="width: 15%;">Branch</th>
					<th style="width: 10%;">Slot Number</th>
					<th style="width: 15%;">Payment Coverage</th>
					<th style="width: 30%;">Payment Details</th>
					<th style="width: 15%;">Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($paymentcontracts as $pc){?>
				<tr>
					<td class="align-middle">
						<input type="checkbox" name="collection-list-item[]" value="<?= $encrypter->encrypt($pc['contract_payment_id']); ?>">
					</td>
					<td class="align-middle"><?= $pc['contract_id']; ?></td>
					<td class="align-middle"><?= $pc['tenant']; ?></td>
					<td class="align-middle"><?= $pc['branch']; ?></td>
					<td class="align-middle"><?= $pc['slot_number']; ?></td>
					<td class="align-middle">
						<?= ucwords(str_replace("-", " ", $pc['identifier'])); ?><br>
						<?php
							if($pc['identifier'] == 'security-deposit'){
								echo "Security Deposit";
							} else {
								$month = explode("-", $pc['identifier']);

                                echo date("M. j, Y", strtotime($pc['start'] . " + " . ($month[1] - 1) . " month")) . " - " . date("M. j, Y", strtotime($pc['start'] . "+" . $month[1] . " month -1 day"));
							}
						?>
					</td>
					<td class="align-middle">
                        <?php
                            if(in_array($pc['type'], array("cash", "bank-deposit", "online-bank-transfer"))){
                                echo ucwords(str_replace("-", " ", $pc['type'])) . "<br>" . date_format(date_create($pc['start']),"F d, Y");
                            } else {
                                echo ucwords(str_replace("-", " ", $pc['type'])) . "<br>" . $pc['bank'] . "<br>#" . $pc['check_number'] . " - " . date_format(date_create($pc['date']),"F d, Y");
                            }
                        ?>
					</td>
					<td class="align-middle text-right"><?= $pc['amount']; ?></td>
				</tr>
                <?php }?>
			</tbody>
		</table>

		<div class="mg-t-20">
			<button class="btn btn-primary mg-r-15" name="submit" type="submit">Save</button>
		
		</div>
	</form>

</div>