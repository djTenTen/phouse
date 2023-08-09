<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <h1>Collection Management</h1>

    <div class="table-reponsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="width: 1%;">ID</th>
					<th style="width: 30%;">Bank Account</th>
					<th style="width: 10%;"># of Payments</th>
					<th style="width: 15%;">Date Deposit</th>
					<th style="width: 15%;">Total Amount</th>
					<th style="width: 15%;">Added By</th>
					<th style="width: 15%;">Added On</th>
					<th style="width: 1%;"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($collection as $coll){?>
				<tr>
					<td class="align-middle"><?= $coll['collection_list_id']; ?></td>
					<td class="align-middle"><?= $coll['bank_account']; ?> [<?= $coll['account_number']; ?>]</td>
					<td class="align-middle"><?= $coll['count']; ?></td>
					<td class="align-middle"><?= date_format(date_create($coll['date']),"F d, Y")?></td>
					<td class="align-middle"><?= number_format($coll['total'], 2); ?></td>
					<td class="align-middle"><?= $coll['added_by']; ?></td>
					<td class="align-middle"><?= date_format(date_create($coll['added_on']),"F d, Y")?></td>
					<td class="align-middle">
						<div class="nowrap">
							<a href="<?= site_url("collection/viewcollection/").str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($coll['collection_list_id']));?>" class="btn btn-icon btn-primary btn-xs"><i class="fas fa-search"></i></a>
						</div>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>

</div>