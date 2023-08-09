

$(document).ready(function(e){
	if($('#gps-search-log-page').exists()){
		let map;
		let markers = [];

		// $.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyDncdF7OjBIj7lsl6i4NkdVW8NXiYWt2i8", function(){
		$.getScript("http://maps.google.com/maps/api/js?sensor=false", function(){
			const center = { lat: 14.610, lng: 121.038 };
			map = new google.maps.Map( document.getElementById('google_maps'), { zoom: 13, center: center } );
		});

		// Adds a marker to the map and push to the array.
		function addMarker(location, label) {
			const marker = new google.maps.Marker({
				position: location,
				map: map,
				title: label
			});
			markers.push(marker);
		}

		// Sets the map on all markers in the array.
		function setMapOnAll(map) {
			for (let i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}

		// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
			setMapOnAll(null);
		}

		// Shows any markers currently in the array.
		function showMarkers() {
			setMapOnAll(map);
		}

		// Deletes all markers in the array by removing references to them.
		function deleteMarkers() {
			clearMarkers();
			markers = [];
		}


		$('#gps-search-log-page button[name="submit"]').click(function(){
			$.ajax({
				url: siteurl + "/ajax/gps-search-log.php",
				cache: false,
				data: $('#gps-search-log-page form').serialize(),
				method: "POST",
				dataType: "json",
				success: function(response){
					$('#log-result tbody').html("");

					deleteMarkers();

					if(response.length > 0){
						for(var i in response){
							var html = "<tr>";
							html += "<td><input type='checkbox'></td>";
							html += "<td><div class='align-middle d-inline-block mr-2 tx-28'><i class='fal fa-location-circle'></i></div><div class='align-middle d-inline-block tx-12'><span data-name='longitude'>" + response[i].longitude + "</span>, <span data-name='latitude'>" + response[i].latitude + "</span><br><span data-name='speed'>" + response[i].speed + "</span><br><span data-name='timestamp'>" + response[i].provider_timestamp + "</span></div></td>";
							html += "</tr>";

							$('#log-result tbody').append(html);

							var loc = { lat: parseFloat(response[i].latitude), lng: parseFloat(response[i].longitude) };
							addMarker(loc, response[i].provider_timestamp + '\n' + response[i].speed);
						}

						// $('#log-result thead input[type="checkbox"]').prop("checked", true);

						$('table.table-checkbox').tablecheckbox();
					} else {
						var html = "<tr>";
						html += "<td class='text-center pd-30-f' colspan='2'>";
						html += "<i class='fal fa-file-times tx-48'></i>";
						html += "<h4 class='mg-0 mg-t-20-f tx-spacing--1'>No records found</h4>";
						html += "</td>";
						html += "</tr>";

						$('#log-result tbody').append(html);
					}
				},
			});
		});

		$('#gps-search-log-page #log-result').on("change", "input[type='checkbox']", function(e){
			e.preventDefault();
			
			deleteMarkers();

			$('#log-result tbody input[type="checkbox"]').each(function(){
				if($(this).is(":checked")){
					var row = $(this).closest("tr");

					var latitude = row.find('span[data-name="latitude"]').html();
					var longitude = row.find('span[data-name="longitude"]').html();
					var speed = row.find('span[data-name="speed"]').html();
					var provider_timestamp = row.find('span[data-name="timestamp"]').html();

					var loc = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
					addMarker(loc, provider_timestamp + '\n' + speed);
				}

			});
		});
	}

	if($("#employee-management-page").exists()){
		$("#employee-management-page a.data-information").click(function(){
			$.ajax({
				url: siteurl + "/ajax/employee.php",
				cache: false,
				data: "id=" + $(this).attr("data-id"),
				method: "POST",
				dataType: "text",
				success: function(response){
					$('#modal-lightbox div.modal-content h5.modal-title').html("Employement Information");
					$('#modal-lightbox div.modal-body').html(response);

					$('#modal-lightbox').modal('show');
				},
				error: function(){
					// console.log("error");
				}
			});
		});
	}

	if($("#tenant-management-page").exists()){
		$("#tenant-management-page a.data-information").click(function(){
			$.ajax({
				url: siteurl + "/ajax/tenant.php",
				cache: false,
				data: "id=" + $(this).attr("data-id"),
				method: "POST",
				dataType: "text",
				success: function(response){
					$('#modal-lightbox div.modal-content h5.modal-title').html("Tenant Information");
					$('#modal-lightbox div.modal-body').html(response);

					$('#modal-lightbox').modal('show');
				},
				error: function(){
					// console.log("error");
				}
			});
		});
	}

	if($("#view-ticket-page").exists()){
		$("#view-ticket-page .timeline-item img").click(function(){
			var html = '<img src="' + $(this).attr("src") + '" class="wd-100p">';

			$('#modal-lightbox div.modal-content h5.modal-title').html("Attachment");
			$('#modal-lightbox div.modal-body').html(html);
			$('#modal-lightbox').modal('show');
		});
	}

	if($('#view-expenses-page').exists()){
		$('#view-expenses-page form input').on("keyup change", function(){
			var form = $(this).closest("form");

			$.ajax({
				url: siteurl + "/ajax/expense-search.php",
				cache: false,
				data: form.serialize(),
				method: "POST",
				success: function(response){
					// console.log(response);
					$('#view-expenses-page table tbody').html(response);
				},
			});
		});
	}

	if($("#add-expense-page").exists()){
		$("#add-expense-page div.table-repeater").each(function(){
			var table = $(this).find("table");
			
			table.on("change keyup", "input, select", function(){
				var total = 0;

				table.find("tbody tr").each(function(){
					var row = $(this);
					var amount = parseFloat(row.find("input[data-name='amount']").val());

					if(!Number.isNaN(amount)){
						total += parseFloat(amount);
					}
				});

				table.find("tfoot tr td[data-name='total']").html(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g,'$1,'));
			});
		});
	}

	if($("#add-contract-page").exists()){

		$("#add-contract-page").on("change", "input[name='contract-date'], select[name='contract-duration']", function(){
			var i = 0;
			var months = $("#add-contract-page select[name='contract-duration'] option:selected").val();

			$("#add-contract-page div.contract-payments table tbody tr[data-name^='month-']").addClass("d-none");
			$("#add-contract-page div.contract-payments table tbody tr[data-name^='month-']").find("input, select").attr("disabled", "disabled");

			while(i < months){
				i++;

				$("#add-contract-page div.contract-payments table tbody tr[data-name='month-" + i + "']").removeClass("d-none");
				$("#add-contract-page div.contract-payments table tbody tr[data-name='month-" + i + "']").find("input, select").removeAttr("disabled");
			}

			$.ajax({
				url: "http://192.168.0.254/new/admin.penthouse/contract/duration",
				cache: false,
				data: $('#add-contract-page form').serialize(),
				method: "POST",
				dataType: "json",
				success: function(response){
					if(!response.hasOwnProperty("error")){
						$("#add-contract-page span[data-name='contract-end']").html(response.end);

						$.each(response.month, function(key, value){
							$("#add-contract-page div.contract-payments table tbody tr[data-name='month-" + key + "'] td span").html(value);
						});
					}
				}
			});
		});
	}

	if($("#view-contract-page").exists()){
		$("#view-contract-page #add-payment-modal form, #view-contract-page #edit-contract-modal form").on("submit", function(e){
			var form = $(this);

			$.ajax({
				url: siteurl + "/ajax/contract.php",
				cache: false,
				data: form.serialize(),
				method: "POST",
				success: function(response){
					location.reload();
				},
			});
		});

		$("#view-contract-page #add-payment-modal, #view-contract-page #edit-contract-modal").on("hidden.bs.modal", function(e){
			var modal = $(this);

			modal.find("input[type='text'], textarea").val("");
			modal.find("select").prop('selectedIndex', 0);
		});
	}

	if($('#create-payroll-page').exists()){
		$('#create-payroll-page table tbody tr td input[type="checkbox"]').change(function(){
			var input = $(this);

			if(input.prop("checked")){
				input.closest("tr").find("input[type='hidden'], input[type='number'], input[type='text']").removeAttr("disabled");
				input.closest("tr").find("input[type='number']").val("0");

				input.closest("tr").find("input[name='earned-salary[]']").val("");
				input.closest("tr").find("input[name='earned-salary[]']").focus();
			} else {
				input.closest("tr").find("input[type='number'], input[type='text']").val("");
				input.closest("tr").find("input[type='hidden'], input[type='number'], input[type='text']").attr("disabled", "disabled");
			}

			compute_salary();
		});

		$('#create-payroll-page table tbody tr td input[type="number"]').on('keyup change', function(){
			compute_salary();
		});

		function compute_salary(){
			var total_salary = 0;
			var total_sss = 0;
			var total_philhealth = 0;
			var total_pagibig = 0;
			var total_deduction = 0;
			var grand_total = 0;

			$('#create-payroll-page table tbody tr').each(function(){
				var row = $(this);

				var earned_salary = Number(row.find("input[name='earned-salary[]']").val());
				var sss = Number(row.find("input[name='sss[]']").val());
				var philhealth = Number(row.find("input[name='philhealth[]']").val());
				var pagibig = Number(row.find("input[name='pagibig[]']").val());
				var deduction = Number(row.find("input[name='deduction[]']").val());

				total_salary += earned_salary;
				total_sss += sss;
				total_philhealth += philhealth;
				total_pagibig += pagibig;
				total_deduction += deduction;
				
				subtotal = earned_salary - (sss + philhealth + pagibig + deduction);

				row.find("td[data-name='subtotal']").html( subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );

				grand_total += subtotal;
			});

			$('#create-payroll-page table tfoot tr td[data-name="salary"]').html( total_salary.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );
			$('#create-payroll-page table tfoot tr td[data-name="sss"]').html( total_sss.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );
			$('#create-payroll-page table tfoot tr td[data-name="philhealth"]').html( total_philhealth.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );
			$('#create-payroll-page table tfoot tr td[data-name="pagibig"]').html( total_pagibig.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );
			$('#create-payroll-page table tfoot tr td[data-name="deduction"]').html( total_deduction.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );
			$('#create-payroll-page table tfoot tr td[data-name="total"]').html( grand_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );
		}
	}

	if($('#collection-list-page').exists()){
		$('#collection-list-page form input').on("keyup change", function(){
			var form = $(this).closest("form");

			$.ajax({
				url: siteurl + "/ajax/collection-list-search.php",
				cache: false,
				data: form.serialize(),
				method: "POST",
				success: function(response){
					$('#collection-list-page table tbody').html(response);
				},
			});
			
		});
	}

	if($('#view-collection-list-page').exists()){
		$('button[data-action="edit-collection-list"]').click(function(){
			$.ajax({
				url: siteurl + "/ajax/collection-list-item.php",
				cache: false,
				data: "id=" + $(this).attr("data-id"),
				method: "POST",
				dataType: "html",
				success: function(response){
					$('#view-collection-list-page #modalEditCollectionList form').html(response);
					$('#view-collection-list-page #modalEditCollectionList').modal('show');
				}
			});
		});

		$("form#edit-colleciton-list").on("submit", function(){
			$.ajax({
				url: siteurl + "/ajax/edit-collection-list-item.php",
				cache: false,
				data: $(this).serialize(),
				method: "POST",
				success: function(response){
					location.reload();
				}
			});
		});
	}

	if($('#view-fund-transfer').exists()){
		$('#view-fund-transfer form input').on("keyup change", function(){
			var form = $(this).closest("form");

			$.ajax({
				url: siteurl + "/ajax/fund-transfer-search.php",
				cache: false,
				data: form.serialize(),
				method: "POST",
				success: function(response){
					$('#view-fund-transfer table tbody').html(response);
				},
			});
		});
	}

	if($("#accounts-receivable-page").exists()){
		$("#accounts-receivable-page a.data-information").click(function(){
			$.ajax({
				url: siteurl + "/ajax/view-contract.php",
				cache: false,
				data: "id=" + $(this).attr("data-id"),
				method: "POST",
				dataType: "text",
				success: function(response){
					$('#modal-lightbox div.modal-content h5.modal-title').html("View Contract");
					$('#modal-lightbox div.modal-body').html(response);

					$('#modal-lightbox').modal('show');
				},
			});
		});
	}

	if($("#accounts-payable-page").exists()){
		$('button[data-action="edit-payment-status"]').click(function(){
			$.ajax({
				url: siteurl + "/ajax/expense-payment-status.php",
				cache: false,
				data: "id=" + $(this).attr("data-id"),
				method: "POST",
				dataType: "html",
				success: function(response){
					$('#accounts-payable-page #modalEditPaymentStatus form').html(response);
					$('#accounts-payable-page #modalEditPaymentStatus').modal('show');

					if($('input[data-type="datepicker"]').exists()){
						$('input[data-type="datepicker"]').daterangepicker({
							autoUpdateInput: false,
							locale: {
								format: 'MMM. D, YYYY'
							},
							
							singleDatePicker: true,
						});

						$('input[data-type="datepicker"]').on('apply.daterangepicker', function(ev, picker){
							$(this).val(picker.startDate.format('MMM. D, YYYY'));
						});
					}
				}
			});
		});

		$("form#edit-payment-status").on("submit", function(){
			$.ajax({
				url: siteurl + "/ajax/edit-payment-status.php",
				cache: false,
				data: $(this).serialize(),
				method: "POST",
				success: function(response){
					location.reload();
				}
			});
		});
	}

	if($("#rental-report-page").exists()){
		$("#rental-report-page a.data-information").click(function(){
			$.ajax({
				url: siteurl + "/ajax/view-contract.php",
				cache: false,
				data: "id=" + $(this).attr("data-id"),
				method: "POST",
				dataType: "text",
				success: function(response){
					$('#modal-lightbox div.modal-content h5.modal-title').html("View Contract");
					$('#modal-lightbox div.modal-body').html(response);

					$('#modal-lightbox').modal('show');
				},
			});
		});
	}
});