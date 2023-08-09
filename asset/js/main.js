$(document).ready(function(){
	$('input, textarea').focusin(function(){
		input = $(this);
		input.attr('place-holder-text', input.attr('placeholder'))
		input.attr('placeholder', '');
	});

	$('input, textarea').focusout(function(){
		input = $(this);
		input.attr('placeholder', input.attr('place-holder-text'));
	});

	if($('div.panels').exists()){
		$('div.panels').each(function(){
			var parent = $(this);

			if($(this).attr('data-panels-initialize') == 'true'){
				$($(this).attr('data-panels-target')).on("change", function(){
					target = $(this);

					if( (target[0].localName == 'input' && $(this).attr("type") == 'radio') || target[0].localName == 'select' ){
						$(parent.find("> div")).removeClass("active");
						$(parent.find("> div")).find("input, select").attr("disabled", "disabled");

						$(parent.find("> div#" + target.val())).addClass("active");
						$(parent.find("> div#" + target.val())).find("input, select").removeAttr("disabled");
						
					}
				});
			}
		});
	}
});


if($('div.autocomplete').exists()){
	$('div.autocomplete').each(function(){
		var elem = $(this);
		var source = elem.attr("data-source");

		var input = elem.find('input[data-autocomplete-search="true"]');
		var button = elem.find("div.input-group-append.button");

		button.click(function(){
			input.removeAttr("disabled");
			elem.find("input").each(function(){
				$(this).val("");
			});
			input.focus();
		});

		if(input.attr("data-autocomplete-force-select") == "true"){
			input.focusout(function(){
				input.val("");
			});
		}

		input.autocomplete({
			minLength: 3,
			delay: 800,
			source: function(request, response){
				$.ajax({
					url: source,
					method: "POST",
					datatype: 'json',
					data: { term: request.term },
					success: function(data){
						response(data);
					}
				});
			},
			select: function(event, ui) {
				input.attr("disabled", "disabled");

				$.each(ui.item, function(index, value){
					curr = elem.find("input[name='" + index + "']");
					if(curr.attr("data-autocomplete-toggle") == "true"){
						curr.attr("disabled", "disabled");
					}

					curr.val(value);
				});
			},
		});
	});
}

jQuery.fn.exists = function(){return this.length>0;}