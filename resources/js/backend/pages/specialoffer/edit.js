var SpecialOfferEdit = function () {

	var initFormValidations = function () {
		var offerForm = $('.edit-offer-form');

		offerForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "image") {
					$(e).parents('.form-group .logo-input').append(error);
				}else {
				$(e).parents('.form-group').append(error);
				}
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			unhighlight: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'title': {
					required: true,
				},
				"products[]" : {
					required: true,
				},
				'image': {
					required: function(text) {
						return $('#image_file_name').val() ? false : true;
					},
					accept: "image/png",
					extension: "png",
					icondimension: [840, 630,'image'],
				},
			},
			messages: {
				'image': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			}
			
		});
	};

	var manageOnLoad = function () {
		$('#productsFilter option').on('click', function () {

			var productId = $(this).attr('value');
			if ($('#edit_custom_option').find('#' + productId).length) {
				$('#' + productId).remove();
			} else {
				var addCustomOptionId = $(this).attr('value');
				var productName = $(this).text();
				addCustomOption(addCustomOptionId, productName, productId);
			}
		});
	};
	var getTypewiseProduct = function(){
		$(document).on('change', "input[name=type]", function(){
			$.ajax({
				type: "POST",
				url: $(this).data('url'),
				data: { 'type': $(this).val() },
				success: function(response){
					$('#products option').detach();
					$('#edit_custom_option').html('');
					$.each( response, function( key, value ) {
						console.log(value);
					  	var data = {
		                	id: key,
		                	text: value.title
		            	};
		            	var newOption = new Option(data.text, data.id, false, false);
		            	newOption.setAttribute('data-final-price',value.final_price);
		            	$('#products').append(newOption);
					});
				}
			});
		});
	}

	return {
		init: function () {
			manageOnLoad();
			initFormValidations();
			formAvailFansOption()
			formAvailOption();
			getTypewiseProduct();
		}
	};
}();

//Initialize when page loads
jQuery(function () {
	SpecialOfferEdit.init();
});

$("input[name=discount_type]").on("change", function() {
	formAvailOption();
});

function formAvailOption() {
	var discount_type = $(document).find("input[name=discount_type]:checked").val();
	if(discount_type == "fixed_amount") {
		$('.custom-option-discount-cls').each(function () {
			$(this).rules('add', {
				required: true,
				number: true,
				min: 0,
				max: parseInt($(this).data('final-price')),
				messages: {
					min: "Please enter a value less than or equal to " + $(this).data('final-price') + " (the product base price)",
					max: "Please enter a value less than or equal to " + $(this).data('final-price') + " (the product base price)",
				}
			});
		});
	} else {
		$('.custom-option-discount-cls').each(function () {
			$(this).rules('add', {
				required: true,
				number: true,
				min: 0,
				max: 100,
				messages: {
					min: "Discount should be in between 0 to 100.",
					max: "Discount should be in between 0 to 100.",
				}
			});
		});
	}
}

function addCustomOption(addCustomOptionId, productName, productId, productPrice) {
	var addCustomOptionHtml = '<div class="block block-bordered block-default block-rounded js-home-main-div" id="' + productId + '"><div class="block-header block-header-default"><div>' + productName + '</div></div><div class="block-content"><div class="row"><div class="col-xl-7"><div class="form-group"><label for="discount_amount" class="required">Discount:</label><input type="text" class="form-control custom-option-discount-cls" data-final-price="' + productPrice + '" id="discount_amount' + addCustomOptionId + '" name="discount_amount['+addCustomOptionId+']" value=""><input type="hidden"  name="product_id['+addCustomOptionId+']" value="' + productId + '"></div></div></div></div></div>';
	$('#edit_custom_option').append('<div class="add-home-team" id=' + addCustomOptionId + '>' + addCustomOptionHtml + '</div>');
	formAvailOption();
}

function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			if ($('#special_offer_image').hasClass('d-md-none')) {
				$('#special_offer_image').removeClass('d-md-none');
			}

			$('#logo_preview_container').html('<div class="logo_preview_container"><img id="image_preview" src="' + e.target.result + '" /></div>');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#image").change(function() {
	readLogoURL(this);
});

$(document).on('click','#remove',function() {
	$('#logo').val('');
	let lbl = document.getElementById('lbl_logo');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('#remove').addClass('d-md-none');
	$('.js-manage-logo-width').removeClass('col-9').addClass('col-12');
});

function formAvailFansOption() {
	$('.custom-avail-fans-cls').each(function () {
		$(this).rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});
};


$("#products").select2();

$("#products").on("select2:select", function (e) {
  	var data = $('#products').select2('data');
	data.forEach(function (item) {
		if ($('#edit_custom_option').find('#' + item.id).length) {} else {
		    addCustomOption(item.id, item.text, item.id, item.element.getAttribute('data-final-price'))
		}
	})
});

$("#products").on("select2:unselect", function (e) {
	var value = e.params.data.id;
	if ($('#edit_custom_option').find('#' + value).length) {
		$('#' + value).remove();
	}
});

$(".default-package").click(function() {
    if(this.checked) {
        $(".premium-package").prop('checked', true);
        $(".premium-package").prop('disabled', true);
    } else {
        $(".premium-package").prop('checked', false);
        $(".premium-package").prop('disabled', false);
    }
});
