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
        let instructions = data.instructions ? `<div class="gateway-desc mb-2 text-white">${data.instructions}</div>` : '';
        let description = data.description ? `<div class="gateway-desc mb-2 text-white"><p>${data.description}</p></div>` : '';

        let isUpi = paymentMethod.toLowerCase().indexOf('upi') !== -1 ||
                    (data.instructions && data.instructions.toLowerCase().indexOf('upi') !== -1) ||
                    (data.description && data.description.toLowerCase().indexOf('upi') !== -1);

        let upiBlock = '';
        if (isUpi) {
            let combinedText = (data.instructions || '') + ' ' + (data.description || '');
            let upiMatch = combinedText.match(/[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}/);
            let upiId = upiMatch ? upiMatch[0] : 'youverse@hdfcbank';
            let amount = $('input[name="price"]').val() || '0';
            let upiUri = 'upi://pay?pa=' + encodeURIComponent(upiId) + '&pn=LaunchShop&am=' + encodeURIComponent(amount) + '&cu=INR';
            let qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(upiUri);

            upiBlock = `
                <div class="p-3 my-3 text-center bg-white rounded shadow-sm border" style="color: #1e293b;">
                    <div class="badge badge-success px-3 py-1 mb-2 font-weight-bold" style="font-size: 11px;">SCAN TO PAY VIA UPI APP</div>
                    <div class="my-2">
                        <img src="${qrUrl}" alt="UPI QR Code" style="width: 170px; height: 170px; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px;" />
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
                        <span class="font-weight-bold" style="font-size: 13px;">UPI ID:</span>
                        <code class="px-2 py-1 bg-light rounded border text-primary font-weight-bold" style="font-size: 13px;">${upiId}</code>
                        <button type="button" class="btn btn-sm btn-outline-primary ml-2 py-0 px-2" style="font-size: 11px; border-radius: 6px;" onclick="navigator.clipboard.writeText('${upiId}'); alert('UPI ID copied: ${upiId}');">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            `;
        }

        let utrInput = `
            <div class="form-element mb-3">
                <label class="text-white mb-1 font-weight-bold">Transaction / UTR Reference ID <span class="text-danger">**</span></label>
                <input type="text" name="transaction_id" class="form-control" placeholder="e.g. 427819028341" required style="border-radius: 8px; background: #fff; color: #1e293b;">
            </div>
        `;

        let safeReceiptTxt = (typeof receiptTxt !== 'undefined') ? receiptTxt : 'Receipt';
        let receipt = `<div class="form-element mb-3">
                          <label class="text-white mb-1 font-weight-bold">${safeReceiptTxt} Proof Screenshot <span class="text-danger">**</span></label><br>
                          <input type="file" name="receipt" class="file-input" required accept="image/*,.pdf" style="color: #fff;">
                          <p class="mb-0 text-warning" style="font-size: 12px;">** Upload payment receipt (.jpg, .jpeg, .png, .pdf)</p>
                       </div>`;

        if (data.is_receipt == 1 || isUpi) {
          $("#is_receipt").val(1);
          let finalInstruction = instructions + description + upiBlock + utrInput + receipt;
          instruction.html(finalInstruction);
        } else {
          $("#is_receipt").val(0);
          let finalInstruction = instructions + description + upiBlock;
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
    $('#payment').val(val);
    $('.co-payment-methods-grid').removeClass('is-invalid-grid');
  });

  // On page load, if a payment method is active, trigger change handler
  var initialPayment = $('.co-pay-method-card.active').data('value') || $('#payment-gateway').val() || $('#payment').val();
  if (initialPayment) {
    $('#payment-gateway').val(initialPayment).trigger('change');
    $('#payment').val(initialPayment);
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

    try {
      var usernameVal = document.querySelector('input[name="username"]')?.value || '';
      var emailVal = document.querySelector('input[name="email"]')?.value || '';
      var shopNameVal = document.querySelector('input[name="shop_name"]')?.value || '';
      var firstNameVal = document.querySelector('input[name="first_name"]')?.value || '';
      var phoneVal = document.querySelector('input[name="phone"]')?.value || '';
      var countryCodeVal = document.querySelector('input[name="country_code"]')?.value || '+91';
      var passwordVal = document.querySelector('input[name="password"]')?.value || '';
      var packageIdVal = document.querySelector('input[name="package_id"]')?.value || '';
      var selectedTemplateVal = document.querySelector('input[name="selected_template"]')?.value || '';
      var categoryVal = document.querySelector('input[name="category"]')?.value || '';

      var pendingLS = {
        username: usernameVal,
        email: emailVal,
        shop_name: shopNameVal,
        first_name: firstNameVal,
        phone: phoneVal,
        country_code: countryCodeVal,
        password: passwordVal,
        package_id: packageIdVal,
        selected_template: selectedTemplateVal,
        category: categoryVal,
        timestamp: Date.now()
      };
      localStorage.setItem('ls_pending_checkout_user', JSON.stringify(pendingLS));
    } catch(e) {}

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

