<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

	<?php
        // Message thrown from the controller
        if(!empty($_SESSION['expense_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Expense Registration Success!</h4>
            <p>Expense has been successfully registered</p>
        </div>';
            unset($_SESSION['expense_added']);
        }  
    ?>

	<h1>Add Expense</h1>
	
	<?= form_open("expense/save")?>

		<div class="row">
			<div class="form-group col-lg-4">
				<label class="d-block">Payee</label>
				<input type="text" class="form-control" name="payee" value="" required autofocus>
			</div>

			<div class="form-group col-lg-4">
				<label class="d-block">Payment Receiver</label>
				<input type="text" class="form-control" name="receiver" value="">
			</div>

			<div class="form-group col-lg-4">
				<label class="d-block">Date</label>
				<div class="input-group mg-b-10">
					<div class="col-sm-10">
						<div class="row">
							<div class="col-4">
								<select name="mme" class="form-control" required>
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
								<select name="dde" class="form-control" required>
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
								<select name="yye" class="form-control" required>
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

			
			<div class="col-lg-12 mg-b-20 table-repeater">
				<h5 class="mg-b-15">Particulars</h5>

				<table class="table table-bordered" data-max-row="14" id="expense">
					<thead>
						<tr>
							<th style="width: 25%;">Branch</th>
							<th style="width: 25%;">Category</th>
							<th style="width: 25%;">Description</th>
							<th style="width: 25%;">Amount</th>
							<th></th>
						</tr>
					</thead>

					<tbody id="texpense">
						<tr>
							<td class="align-middle">
								<select class="custom-select" name="expense-branch[]" required>
									<option value="" selected>Select Branch</option>
									<?php foreach($braches as $brc){?>
									<option value="<?= $encrypter->encrypt($brc['branch_id']);?>"><?= $brc['name'];?></option>
									<?php }?>
								</select>
							</td>
							<td class="align-middle">
								<select class="custom-select" name="expense-category[]" required>
									<option value="" selected>Select Category</option>
									<?php foreach($expensecategory as $ec){?>				
									<option value="<?= $encrypter->encrypt($ec['expense_category_id']);?>"><?= $ec['name'];?></option>
									<?php }?>
								</select>
							</td>
							<td class="align-middle"><input type="text" class="form-control" name="expense-description[]" value="" required></td>
							<td class="align-middle"><input type="number" class="form-control" data-name="amount" name="expense-amount[]" step="0.01" value="" required></td>
							<td><button class="btn btn-icon btn-danger btn-xs remove" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
						</tr>
				

					</tbody>
					<tfoot>
						<tr class="tx-20">
							<td class="align-middle text-right" colspan="3">Total:</td>
							<td class="align-middle text-right" data-name="total"></td>
							<td></td>
						</tr>
					</tfoot>
				</table>

				<button class="btn btn-primary float-right" type="button" id="addexpense">Add Row</button>
			</div>

			<div class="col-lg-12 mg-b-20 table-repeater">
				<h5 class="mg-b-15">Payment Details</h5>

				<table class="table table-bordered" data-max-row="10" id="payment">
					<thead>
						<tr>
							<th style="width: 20%;">Source</th>
							<th style="width: 20%;">Type</th>
							<th style="width: 25%;">Date</th>
							<th style="width: 20%;">Check No.</th>
							<th style="width: 15%;">Amount</th>
							<th></th>
						</tr>
					</thead>

					<tbody id="tpayment">
				
						<tr>
							<td class="align-middle">
								<select class="custom-select" name="payment-source[]">
									<option value="" selected>Select Bank</option>
									<?php foreach($bankaccount as $ba){?>		
									<option value="<?= $encrypter->encrypt($ba['bank_account_id']);?>">[<?= $ba['bank'];?>]<?= $ba['name'];?></option>
									<?php }?>
								</select>
							</td>
							<td class="align-middle">
								<select class="custom-select" name="payment-type[]">
									<option value="cash">Cash</option>
									<option value="check">Check</option>
								</select>
							</td>
							<td class="align-middle">
								<div class="row">
									<div class="col-4">
										<select name="mmp[]" class="form-control" required>
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
										<select name="ddp[]" class="form-control" required>
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
										<select name="yyp[]" class="form-control" required>
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
							<td class="align-middle"><input type="text" class="form-control" name="payment-check-no[]" value=""></td>
							<td class="align-middle"><input type="number" class="form-control" data-name="amount" name="payment-amount[]" step="0.01" value=""></td>
							<td><button class="btn btn-icon btn-danger btn-xs remove" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
						</tr>

						
					</tbody>
					<tfoot>
						<tr class="tx-20">
							<td class="align-middle text-right" colspan="4">Total:</td>
							<td class="align-middle text-right" data-name="total"></td>
							<td></td>
						</tr>
					</tfoot>
				</table>

				<button class="btn btn-primary float-right" type="button" id="addpayment">Add Row</button>
			</div>

		</div>


		<div class="mg-t-20">
			<button class="btn btn-primary mg-r-15" name="publish" type="submit">Register Expense</button>
		</div>
		
	</form>
</div>


<script>
    $(document).ready(function () {
		// Denotes total number of rows
        var rowIdx1 = 0;
        // jQuery button click event to add a row

		var tablee = $("#expense");

		tablee.on("change keyup", "input, select", function(){
			var totale = 0;
			tablee.find("tbody tr").each(function(){
				var row = $(this);
				var amount = parseFloat(row.find("input[data-name='amount']").val());

				if(!Number.isNaN(amount)){
					totale += parseFloat(amount);
				}
			});
			tablee.find("tfoot tr td[data-name='total']").html(totale.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g,'$1,'));
		});


        $('#addexpense').on('click', function () {
            // Adding a row inside the tbody.
            $('#texpense').append(`
			
			<tr>
				<td class="align-middle">
					<select class="custom-select" name="expense-branch[]" required>
						<option value="" selected>Select Branch</option>
						<?php foreach($braches as $brc){?>
						<option value="<?= $encrypter->encrypt($brc['branch_id']);?>"><?= $brc['name'];?></option>
						<?php }?>
					</select>
				</td>
				<td class="align-middle">
					<select class="custom-select" name="expense-category[]" required>
						<option value="" selected>Select Category</option>
						<?php foreach($expensecategory as $ec){?>				
						<option value="<?= $encrypter->encrypt($ec['expense_category_id']);?>"><?= $ec['name'];?></option>
						<?php }?>
					</select>
				</td>
				<td class="align-middle"><input type="text" class="form-control" name="expense-description[]" value="" required></td>
				<td class="align-middle"><input type="number" class="form-control" data-name="amount" name="expense-amount[]" step="0.01" value="" required></td>
				<td><button class="btn btn-icon btn-danger btn-xs remove" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
			</tr>
			
			`);

        });

        // jQuery button click event to remove a row.
        $('#texpense').on('click', '.remove', function () {
            var child = $(this).closest('tr').nextAll();
            child.each(function () {
            var id = $(this).attr('id');
            var idx = $(this).children('.row-index').children('p');
            var dig = parseInt(id.substring(1));
            idx.html(`Row ${dig - 1}`);
            $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowIdx1--;
        });



		// Denotes total number of rows
        var rowIdx2 = 0;
        // jQuery button click event to add a row

		var tablep = $("#payment");

		tablep.on("change keyup", "input, select", function(){
			var totalp = 0;
			tablep.find("tbody tr").each(function(){
				var row = $(this);
				var amount = parseFloat(row.find("input[data-name='amount']").val());

				if(!Number.isNaN(amount)){
					totalp += parseFloat(amount);
				}
			});
			tablep.find("tfoot tr td[data-name='total']").html(totalp.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g,'$1,'));
		});


        $('#addpayment').on('click', function () {
            // Adding a row inside the tbody.
            $('#tpayment').append(`
			
			<tr>
				<td class="align-middle">
					<select class="custom-select" name="payment-source[]">
						<option value="" selected>Select Bank</option>
						<?php foreach($bankaccount as $ba){?>		
						<option value="<?= $encrypter->encrypt($ba['bank_account_id']);?>">[<?= $ba['bank'];?>]<?= $ba['name'];?></option>
						<?php }?>
					</select>
				</td>
				<td class="align-middle">
					<select class="custom-select" name="payment-type[]">
						<option value="cash">Cash</option>
						<option value="check">Check</option>
					</select>
				</td>
				<td class="align-middle">
					<div class="row">
						<div class="col-4">
							<select name="mmp[]" class="form-control" required>
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
							<select name="ddp[]" class="form-control" required>
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
							<select name="yyp[]" class="form-control" required>
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
				<td class="align-middle"><input type="text" class="form-control" name="payment-check-no[]" value=""></td>
				<td class="align-middle"><input type="number" class="form-control" data-name="amount" name="payment-amount[]" step="0.01" value=""></td>
				<td><button class="btn btn-icon btn-danger btn-xs remove" type="button" data-action="remove"><i class="fas fa-times"></i></button></td>
			</tr>
			
			`);

        });

        // jQuery button click event to remove a row.
        $('#tpayment').on('click', '.remove', function () {
            var child = $(this).closest('tr').nextAll();
            child.each(function () {
            var id = $(this).attr('id');
            var idx = $(this).children('.row-index').children('p');
            var dig = parseInt(id.substring(1));
            idx.html(`Row ${dig - 1}`);
            $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowIdx2--;
        });


    });
</script>
