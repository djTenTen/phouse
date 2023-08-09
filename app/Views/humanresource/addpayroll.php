<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['payroll_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Payroll Created!</h4>
            <p>Payroll has been successfully created</p>
        </div>';
            unset($_SESSION['payroll_added']);
        }  
    ?>

    <h1>Add Payroll</h1>

    <div id="create-payroll-page">
	<?= form_open('payroll/save')?>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Date</label>
			<div class="col-sm-10">
				<select class="form-control" name="cutoff" required>
					<?php foreach($cutoff as $ct){?>
                        <option value="<?= $ct['cover']?>"><?= $ct['cutoff']?></option>
                    <?php }?>
				</select>
			</div>
		</div>

        <table class="table table-bordered">
			<thead>
				<tr>
					<th style="width: 1%;"></th>
					<th style="width: 15%;">Name</th>
					<th style="width: 10%;">Assigned Branch</th>
					<th style="width: 10%;">Earned Salary</th>
					<th style="width: 10%;">SSS</th>
					<th style="width: 10%;">Philhealth</th>
					<th style="width: 10%;">Pag-ibig</th>
					<th style="width: 10%;">Other Deduction</th>
					<th style="width: 10%;">Net Salary</th>
					<th style="width: 15%;">Remark</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($employee as $emp){?>
				<tr>
					<td class="align-middle">
						<input type="checkbox">
						<input type="hidden" name="employee[]" value="<?= $encrypter->encrypt($emp['employee_id']); ?>" disabled>
					</td>
					<td class="align-middle">[<?= $emp['employee_id']; ?>] <?= $emp['first_name']; ?> <?= $emp['last_name']; ?></td>
					<td class="align-middle"><?= $emp['branch']; ?></td>
					<td class="align-middle"><input type="number" class="form-control" name="earned-salary[]" step="0.01" value="" required disabled></td>
					<td class="align-middle"><input type="number" class="form-control" name="sss[]" step="0.01" value="" required disabled></td>
					<td class="align-middle"><input type="number" class="form-control" name="philhealth[]" step="0.01" value="" required disabled></td>
					<td class="align-middle"><input type="number" class="form-control" name="pagibig[]" step="0.01" value="" required disabled></td>
					<td class="align-middle"><input type="number" class="form-control" name="deduction[]" step="0.01" value="" required disabled></td>
					<td class="align-middle text-right" data-name="subtotal">0.00</td>
					<td class="align-middle"><input type="text" class="form-control" name="remark[]" value="" disabled></td>
				</tr>
				<?php }?>
			</tbody>
			<tfoot>
				<tr>
					<td class="text-right" colspan="3">Total:</td>
					<td class="text-right" data-name="salary">0.00</td>
					<td class="text-right" data-name="sss">0.00</td>
					<td class="text-right" data-name="philhealth">0.00</td>
					<td class="text-right" data-name="pagibig">0.00</td>
					<td class="text-right" data-name="deduction">0.00</td>
					<td class="text-right" data-name="total">0.00</td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>
		</table>

		<button type="submit" name="submit" class="btn btn-primary">Submit</button>
	</form>
</div>



</div>