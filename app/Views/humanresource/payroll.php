<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <h1>Payroll</h1>


    <table class="table table-bordered">
		<thead>
			<tr>
				<th style="width: 1%;">ID</th>
				<th style="width: 40%;">Coverage</th>
				<th style="width: 20%;">Total</th>
				<th style="width: 20%;">Added By</th>
				<th style="width: 20%;">Added On</th>
				<th style="width: 1%;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($payroll as $prl){
				$date = explode (" - ", $prl['coverage']);
			?>
			<tr>
				<td class="align-middle"><?= $prl['payroll_id']; ?></td>
				<td class="align-middle"><?= date('M. j, Y', strtotime($date[0])) . " - " . date('M. j, Y', strtotime($date[1])); ?></td>
				<td class="align-middle"><?= number_format(($prl['earned_salary'] - ($prl['sss'] + $prl['philhealth'] + $prl['pagibig'] + $prl['deduction'])), 2); ?></td>
				<td class="align-middle"><?= $prl['added_by']; ?></td>
				<td class="align-middle"><?= date('M. j, Y, g:i a', strtotime($prl['added_on'])); ?></td>
				<td class="align-middle">
                    <button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modalview" data-payroll-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($prl['payroll_id'])); ?>"><i class="fas fa-search"></i></button>
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
                <div class="row1">

                </div>

                <table class="table table-bordered mg-b-20">
                    <thead>
                        <tr>
                            <th style="width: 1%;">ID</th>
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
                    <tbody class="payrollbody">

                        

                    </tbody>
                    <tfoot class="payrollfooter" >
                        
                    </tfoot>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<!-- END OF MODAL VIEW -->



<script>

    function numberformat(num){
        return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    $(document).ready(function() {
        // When the button is clicked, show the modal and load the data

        $(".load-data").on("click", function() {
            // Show the modal
            var pID = $(this).data('payroll-id');
            // Fetch data using AJAX

            $.ajax({
                url: "<?= site_url('payroll/getinfo/')?>" + pID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {
                    
                    $(".modal-title").html(`Payroll ${data.date1} - ${data.date2} Information`);
                    // Populate the modal body with the fetched data
                    $(".row1").html(
                        `
                        <table class="table table-bordered mg-b-20">
                            <tbody>
                                <tr>
                                    <td style="width: 20%;">Payroll ID</td>
                                    <td style="width: 80%;">${data.payroll_id}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Coverage</td>
                                    <td style="width: 80%;">${data.date1} - ${data.date2}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Added By</td>
                                    <td style="width: 80%;">${data.added_by}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Added On</td>
                                    <td style="width: 80%;">${data.added_on}</td>
                                </tr>
                            </tbody>
                        </table>
                        `
                    );

                   

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }

            });


            $.ajax({
                url: "<?= site_url('payroll/getitem/')?>" + pID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {

                    var tblitem = "";
                    var earned = 0;
                    var sss = 0;
                    var ph = 0;
                    var pagbig = 0;
                    var deduc = 0;
                    var netsal = 0;
                    $.each(data, function(index, item) {
                        earned += parseInt(item.earned_salary);
                        sss += parseInt(item.sss);
                        ph += parseInt(item.philhealth);
                        pagbig += parseInt(item.pagibig);
                        deduc += parseInt(item.deduction);
                        netsal += parseInt(item.net_salary);

                        tblitem += `
                            <tr>
                                <td class="align-middle">${item.employee_id}</td>
                                <td class="align-middle">${item.first_name} ${item.last_name}</td>
                                <td class="align-middle">${item.assigned_branch}</td>
                                <td class="align-middle text-right">${numberformat(item.earned_salary)}</td>
                                <td class="align-middle text-right">${numberformat(item.sss)}</td>
                                <td class="align-middle text-right">${numberformat(item.philhealth)}</td>
                                <td class="align-middle text-right">${numberformat(item.pagibig)}</td>
                                <td class="align-middle text-right">${numberformat(item.deduction)}</td>
                                <td class="align-middle text-right">${numberformat(item.net_salary)}</td>
                                <td class="align-middle">${item.remark}</td>
                            </tr>
                        `;
                    });

                    $(".payrollbody").html(tblitem);



                    $(".payrollfooter").html(
                        `
                        <tr>
                            <td class="text-right" colspan="3">Total:</td>
                            <td class="text-right">${numberformat(earned)}</td>
                            <td class="text-right">${numberformat(sss)}</td>
                            <td class="text-right">${numberformat(ph)}</td>
                            <td class="text-right">${numberformat(pagbig)}</td>
                            <td class="text-right">${numberformat(deduc)}</td>
                            <td class="text-right">${numberformat(netsal)}</td>
                            <td class="text-right">&nbsp;</td>
                        </tr>
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