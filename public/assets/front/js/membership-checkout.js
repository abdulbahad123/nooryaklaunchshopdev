"use strict";

if (stripe_key) {
  // Set your Stripe public key 
  var stripe = Stripe(stripe_key);
  // Create a Stripe Element for the card field
  var elements = stripe.elements();
  var cardElement = elements.create('card', {
    style: {
      base: {
        iconColor: '#454545',
        color: '#454545',
        fontWeight: '500',
        lineHeight: '50px',
        fontSmoothing: 'antialiased',
        backgroundColor: '#f2f2f2',
        ':-webkit-autofill': {
          color: '#454545',
        },
        '::placeholder': {
          color: '#454545',
        },
      }
    },
  });

  // Add an instance of the card Element into the `card-element` div
  cardElement.mount('#stripe-element');
}

$("#payment-gateway").on('change', function () {
  let data = [];
  offline.map(({
    id,
    name
  }) => {
    data.push(name);
  });
  let paymentMethod = $("#payment-gateway").val();

  $(".gateway-details").hide();
  $(".gateway-details input").attr('disabled', true);

  if (paymentMethod == 'Stripe') {
    $("#tab-stripe").show();
    $("#tab-stripe input").removeAttr('disabled');
  } else {
    $("#tab-stripe").hide();
  }

  if (paymentMethod == 'Authorize.net') {
    $("#tab-anet").show();
    $("#tab-anet input").removeAttr('disabled');
  } else {
    $("#tab-anet").hide();
  }

  if (paymentMethod == 'Iyzico') {
    $('.iyzico-element').removeClass('d-none');
  } else {
    $('.iyzico-element').addClass('d-none');
  }

  if (data.indexOf(paymentMethod) != -1) {
    let formData = new FormData();
    formData.append('name', paymentMethod);
    $.ajax({
      url: instruction_url,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      contentType: false,
      processData: false,
      cache: false,
      data: formData,
      success: function (data) {
        let instruction = $("#instructions");
        let instructions = `<div class="gateway-desc">${data.instructions}</div>`;
        if (data.description != null) {
          var description = `<div class="gateway-desc"><p>${data.description}</p></div>`;
        } else {
          var description = `<div></div>`;
        }
        let receipt = `<div class="form-element mb-2">
                                              <label class="text-white mb-1">Receipt <span class="text-danger">**</span></label><br>
                                              <input type="file" name="receipt" value="" class="file-input" required>
                                              <p class="mb-0 text-warning">** ${receiptTxt} .jpg / .jpeg / .png</p>
                                           </div>`;
        if (data.is_receipt == 1) {
          $("#is_receipt").val(1);
          let finalInstruction = instructions + description + receipt;
          instruction.html(finalInstruction);
        } else {
          $("#is_receipt").val(0);
          let finalInstruction = instructions + description;
          instruction.html(finalInstruction);
        }
        $('#instructions').fadeIn();
      },
      error: function (data) { }
    })
  } else {
    $('#instructions').fadeOut();
    $('#instructions').html('');
  }
});

$(document).ready(function () {
  // Payment card selection click handler
  $('.co-pay-method-card').on('click', function() {
    $('.co-pay-method-card').removeClass('active');
    $(this).addClass('active');
    var val = $(this).data('value');
    $('#payment-gateway').val(val).trigger('change');
    $('.co-payment-methods-grid').removeClass('is-invalid-grid');
  });

  // On page load, if a payment method is active, trigger change handler
  var initialPayment = $('.co-pay-method-card.active').data('value');
  if (initialPayment) {
    $('#payment-gateway').val(initialPayment).trigger('change');
  }

  // Clear input errors on user interaction
  $('.co-input').on('input change', function() {
    $(this).removeClass('is-invalid');
  });

  $("#my-checkout-form").on('submit', function (e) {
    e.preventDefault();

    // Clear previous validation styles
    $('.co-input').removeClass('is-invalid');
    $('.co-payment-methods-grid').removeClass('is-invalid-grid');

    // Validation checks
    let missingFields = [];
    
    let shopName = $("#shop_name");
    if (shopName.length > 0 && (!shopName.val() || !shopName.val().trim())) {
      missingFields.push("Shop Name");
      shopName.addClass('is-invalid');
    }
    
    let countryCode = $("#country_code");
    if (countryCode.length > 0 && (!countryCode.val() || !countryCode.val().trim())) {
      missingFields.push("Country Code");
      countryCode.addClass('is-invalid');
    }
    
    let phone = $("#phone");
    if (phone.length > 0 && (!phone.val() || !phone.val().trim())) {
      missingFields.push("Phone Number");
      phone.addClass('is-invalid');
    }
    
    let address = $("#address");
    if (address.length > 0 && (!address.val() || !address.val().trim())) {
      missingFields.push("Street Address");
      address.addClass('is-invalid');
    }
    
    let city = $("#city");
    if (city.length > 0 && (!city.val() || !city.val().trim())) {
      missingFields.push("City");
      city.addClass('is-invalid');
    }
    
    let country = $("#country");
    if (country.length > 0 && (!country.val() || !country.val().trim())) {
      missingFields.push("Country");
      country.addClass('is-invalid');
    }
    
    // Check payment gateway if it's a paid plan
    if (typeof package_price !== 'undefined' && package_price > 0 && typeof is_trial !== 'undefined' && is_trial !== '1') {
      let pg = $("#payment-gateway");
      if (pg.length > 0 && !pg.val()) {
        missingFields.push("Payment Method");
        $('.co-payment-methods-grid').addClass('is-invalid-grid');
      }
    }
    
    if (missingFields.length > 0) {
      // Re-enable and reset confirm button state
      $('#confirmBtn').prop('disabled', false).html('<i class="fas fa-lock"></i> Place Order');
      
      Swal.fire({
        icon: 'warning',
        title: 'Missing Required Fields',
        html: `<p style="font-size: 14.5px; color: #64748b; margin-bottom: 16px;">Please complete the following details before proceeding:</p>
               <div style="text-align: left; display: inline-block; margin: 0 auto;">
                 ${missingFields.map(f => `<div style="font-size: 14px; font-weight: 600; color: #ef4444; margin-bottom: 6px;"><i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>${f}</div>`).join('')}
               </div>`,
        confirmButtonText: 'Review Details',
        confirmButtonColor: '#ff5a2c',
        background: '#ffffff',
        customClass: {
          popup: 'swal2-premium-popup',
          title: 'swal2-premium-title',
          confirmButton: 'swal2-premium-confirm'
        }
      });
      return false;
    }

    $('#confirmBtn').prop('disabled', true).text(processing_text);
    let val = $("#payment-gateway").val();
    if (val == 'Stripe') {
      stripe.createToken(cardElement).then(function (result) {
        if (result.error) {
          // Display errors to the customer
          var errorElement = document.getElementById('stripe-errors');
          errorElement.textContent = result.error.message;
          $("#confirmBtn").prop('disabled', false).text(confirm_text);
        } else {
          // Send the token to your server
          stripeTokenHandler(result.token);
        }
      });
    } else if (val == 'Authorize.net') {
      sendPaymentDataToAnet();
    } else {
      $(this).unbind('submit').submit();
    }
  });
});

//stripe token handler
// Send the token to your server
function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('my-checkout-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  form.submit();
}

function sendPaymentDataToAnet() {
  // Set up authorisation to access the gateway.
  var authData = {};
  authData.clientKey = authorize_public_key;
  authData.apiLoginID = authorize_login_id;

  var cardData = {};
  cardData.cardNumber = document.getElementById("anetCardNumber").value;
  cardData.month = document.getElementById("anetExpMonth").value;
  cardData.year = document.getElementById("anetExpYear").value;
  cardData.cardCode = document.getElementById("anetCardCode").value;

  // Now send the card data to the gateway for tokenisation.
  // The responseHandler function will handle the response.
  var secureData = {};
  secureData.authData = authData;
  secureData.cardData = cardData;
  Accept.dispatchData(secureData, responseHandler);
}

function responseHandler(response) {
  if (response.messages.resultCode === "Error") {
    var i = 0;
    let errorLists = ``;
    while (i < response.messages.message.length) {
      errorLists += `<li class="text-danger">${response.messages.message[i].text}</li>`;

      i = i + 1;
    }
    $("#anetErrors").show();
    $("#anetErrors").html(errorLists);
    $("#confirmBtn").prop('disabled', false).text(confirm_text);
  } else {
    paymentFormUpdate(response.opaqueData);
  }
}

function paymentFormUpdate(opaqueData) {
  document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
  document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
  document.getElementById("my-checkout-form").submit();
}
//authorize code start end

