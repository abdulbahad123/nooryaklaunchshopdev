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

  let isUpiMethod = paymentMethod.toLowerCase().indexOf('upi') !== -1;
  let isOfflineMethod = data.indexOf(paymentMethod) != -1 || isUpiMethod;

  if (isOfflineMethod) {
    let renderUpiBlock = function(customUpiId, instr, desc, isReceipt) {
      let instruction = $("#instructions");
      let instructions = instr ? `<div class="gateway-desc mb-3" style="color: #1e293b !important; font-size: 13.5px; line-height: 1.6; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">${instr}</div>` : '';
      let description = desc ? `<div class="gateway-desc mb-3" style="color: #1e293b !important; font-size: 13.5px; line-height: 1.6; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;"><p style="color: #1e293b !important; margin-bottom: 0;">${desc}</p></div>` : '';

      let isUpi = isUpiMethod ||
                  (instr && instr.toLowerCase().indexOf('upi') !== -1) ||
                  (desc && desc.toLowerCase().indexOf('upi') !== -1);

      let upiBlock = '';
      if (isUpi) {
          let combinedText = (instr || '') + ' ' + (desc || '');
          let upiMatch = combinedText.match(/[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}/);
          let upiId = customUpiId || (upiMatch ? upiMatch[0] : 'abdulbahad833@okicici');
          let amount = $('input[name="price"]').val() || '0';
          let upiUri = 'upi://pay?pa=' + encodeURIComponent(upiId) + '&pn=LaunchShop&am=' + encodeURIComponent(amount) + '&cu=INR';
          let qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(upiUri);

          upiBlock = `
              <div class="p-3 my-3 text-center bg-white rounded shadow-sm border" style="color: #1e293b;">
                  <div class="badge badge-success px-3 py-1 mb-2 font-weight-bold" style="font-size: 11px;">SCAN TO PAY VIA ANY UPI APP</div>
                  <div class="my-2">
                      <img src="${qrUrl}" alt="UPI QR Code" style="width: 170px; height: 170px; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px;" />
                  </div>
                  <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 mt-2">
                      <span class="font-weight-bold" style="font-size: 13px;">UPI ID:</span>
                      <code class="px-2 py-1 bg-light rounded border text-primary font-weight-bold" style="font-size: 13px;">${upiId}</code>
                      <button type="button" class="btn btn-sm btn-outline-primary ml-2 py-1 px-2 font-weight-bold" style="font-size: 11px; border-radius: 6px;" onclick="if(navigator.clipboard){navigator.clipboard.writeText('${upiId}');} var b=this; b.innerHTML='<i class=\\'fas fa-check text-success\\'></i> Copied!'; setTimeout(function(){ b.innerHTML='<i class=\\'fas fa-copy\\'></i> Copy'; }, 2000);">
                          <i class="fas fa-copy"></i> Copy
                      </button>
                  </div>
                  <small class="text-muted d-block mt-2" style="font-size: 11px;">Supports GPay, PhonePe, Paytm, BHIM & NetBanking UPI</small>
              </div>
          `;
      }

      let utrInput = `
          <div class="form-element mb-3" id="utr-group">
              <label class="mb-1 font-weight-bold d-block" style="color: #0f172a !important; font-size: 13px;">Transaction / UTR Reference ID <span class="text-danger">*</span></label>
              <input type="text" id="transaction_id_input" name="transaction_id" class="form-control co-input" placeholder="e.g. 427819028341" required style="border-radius: 8px; background: #fff; color: #1e293b; border: 1.5px solid #cbd5e1; padding: 10px 14px; font-size: 14px;">
              <div id="utr-error-container" class="mt-1" style="display: none;"></div>
          </div>
      `;

      let safeReceiptTxt = (typeof receiptTxt !== 'undefined') ? receiptTxt : 'Payment Receipt';
      let receipt = `
          <div class="form-element mb-3" id="receipt-group">
              <label class="mb-1 font-weight-bold d-block" style="color: #0f172a !important; font-size: 13px;">${safeReceiptTxt} Proof Screenshot <span class="text-danger">* (Required)</span></label>
              <div class="custom-file-upload-wrapper" style="position: relative;">
                <input type="file" id="receipt_input" name="receipt" class="file-input co-input" required accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf" style="color: #1e293b; background: #fff; border: 1.5px dashed #cbd5e1; border-radius: 8px; padding: 10px 14px; width: 100%; cursor: pointer;">
              </div>
              <div id="receipt-file-selected-badge" class="mt-2" style="display: none;">
                <span class="badge py-1 px-2" style="font-size: 12px; background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; border-radius: 6px;">
                  <i class="fas fa-check-circle mr-1"></i> <span id="receipt-file-name"></span>
                </span>
              </div>
              <p class="mb-0 mt-1 text-muted" style="font-size: 12px;"><i class="fas fa-info-circle text-primary"></i> Upload payment receipt screenshot (.jpg, .jpeg, .png, .webp, .pdf)</p>
              <div id="receipt-error-container" class="mt-2" style="display: none;"></div>
          </div>
      `;

      if (isReceipt == 1 || isUpi) {
        $("#is_receipt").val(1);
        let finalInstruction = instructions + description + upiBlock + utrInput + receipt;
        instruction.html(finalInstruction);
      } else {
        $("#is_receipt").val(0);
        let finalInstruction = instructions + description + upiBlock;
        instruction.html(finalInstruction);
      }
      $('#instructions').fadeIn();
    };

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
        renderUpiBlock(null, data.instructions, data.description, data.is_receipt);
      },
      error: function (xhr) {
        renderUpiBlock('abdulbahad833@okicici', '', '', 1);
      }
    });
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

  // Clear receipt error on file change
  $(document).on('change', 'input[name="receipt"]', function () {
    let files = this.files;
    if (files && files.length > 0) {
      $(this).removeClass('is-invalid').css({ 'border-color': '#10b981', 'background-color': '#f0fdf4' });
      $('#receipt-error-container').fadeOut().empty();
      $('#receipt-file-name').text(files[0].name + ' (' + (files[0].size / 1024).toFixed(1) + ' KB)');
      $('#receipt-file-selected-badge').fadeIn();
    } else {
      $('#receipt-file-selected-badge').fadeOut();
    }
  });

  // Clear UTR error on input
  $(document).on('input', 'input[name="transaction_id"]', function () {
    if ($(this).val().trim()) {
      $(this).removeClass('is-invalid').css({ 'border-color': '#cbd5e1', 'background-color': '#fff' });
      $('#utr-error-container').fadeOut().empty();
    }
  });

  $("#my-checkout-form").on('submit', function (e) {
    e.preventDefault();

    // Clear previous validation styles
    $('.co-input').removeClass('is-invalid');
    $('.co-payment-methods-grid').removeClass('is-invalid-grid');
    $('#receipt_input').removeClass('is-invalid').css({ 'border-color': '#cbd5e1', 'background-color': '#fff' });
    $('#transaction_id_input').removeClass('is-invalid').css({ 'border-color': '#cbd5e1', 'background-color': '#fff' });
    $('#receipt-error-container').hide().empty();
    $('#utr-error-container').hide().empty();

    // Validation checks
    let missingFields = [];
    let firstInvalidEl = null;
    
    let shopName = $("#shop_name");
    if (shopName.length > 0 && (!shopName.val() || !shopName.val().trim())) {
      missingFields.push("Shop Name");
      shopName.addClass('is-invalid');
      if (!firstInvalidEl) firstInvalidEl = shopName;
    }
    
    let countryCode = $("#country_code");
    if (countryCode.length > 0 && (!countryCode.val() || !countryCode.val().trim())) {
      missingFields.push("Country Code");
      countryCode.addClass('is-invalid');
      if (!firstInvalidEl) firstInvalidEl = countryCode;
    }
    
    let phone = $("#phone");
    if (phone.length > 0 && (!phone.val() || !phone.val().trim())) {
      missingFields.push("Phone Number");
      phone.addClass('is-invalid');
      if (!firstInvalidEl) firstInvalidEl = phone;
    }
    
    let address = $("#address");
    if (address.length > 0 && (!address.val() || !address.val().trim())) {
      missingFields.push("Street Address");
      address.addClass('is-invalid');
      if (!firstInvalidEl) firstInvalidEl = address;
    }
    
    let city = $("#city");
    if (city.length > 0 && (!city.val() || !city.val().trim())) {
      missingFields.push("City");
      city.addClass('is-invalid');
      if (!firstInvalidEl) firstInvalidEl = city;
    }
    
    let country = $("#country");
    if (country.length > 0 && (!country.val() || !country.val().trim())) {
      missingFields.push("Country");
      country.addClass('is-invalid');
      if (!firstInvalidEl) firstInvalidEl = country;
    }
    
    // Check payment gateway if it's a paid plan
    if (typeof package_price !== 'undefined' && package_price > 0 && typeof is_trial !== 'undefined' && is_trial !== '1') {
      let pg = $("#payment-gateway");
      if (pg.length > 0 && !pg.val()) {
        missingFields.push("Payment Method");
        $('.co-payment-methods-grid').addClass('is-invalid-grid');
        if (!firstInvalidEl) firstInvalidEl = $('.co-payment-methods-grid');
      }
    }

    // Receipt Validation (CRITICAL): If receipt is required, verify file is selected!
    let isReceiptVal = $("#is_receipt").val();
    let receiptEl = $('input[name="receipt"]');
    if (isReceiptVal == '1' || receiptEl.length > 0) {
      let files = receiptEl[0]?.files;
      if (!files || files.length === 0) {
        missingFields.push("Payment Receipt Proof Screenshot");
        receiptEl.addClass('is-invalid').css({ 'border-color': '#ef4444', 'background-color': '#fef2f2' });
        $('#receipt-error-container').html(`
          <div class="alert alert-danger py-2 px-3 mb-0" style="font-size: 13px; font-weight: 600; border-radius: 8px; border-left: 4px solid #ef4444; background: #fef2f2; color: #b91c1c;">
            <i class="fas fa-exclamation-circle mr-1"></i> Payment receipt screenshot is required before placing your order.
          </div>
        `).fadeIn();
        if (!firstInvalidEl) firstInvalidEl = receiptEl;
      }
    }

    // Transaction ID Validation
    let utrEl = $('input[name="transaction_id"]');
    if (utrEl.length > 0 && isReceiptVal == '1') {
      let utrVal = utrEl.val() ? utrEl.val().trim() : '';
      if (!utrVal) {
        missingFields.push("Transaction / UTR Reference ID");
        utrEl.addClass('is-invalid').css({ 'border-color': '#ef4444', 'background-color': '#fef2f2' });
        $('#utr-error-container').html(`
          <div class="alert alert-danger py-2 px-3 mb-0" style="font-size: 13px; font-weight: 600; border-radius: 8px; border-left: 4px solid #ef4444; background: #fef2f2; color: #b91c1c;">
            <i class="fas fa-exclamation-circle mr-1"></i> Please enter the UPI / Bank Transaction or UTR Reference ID.
          </div>
        `).fadeIn();
        if (!firstInvalidEl) firstInvalidEl = utrEl;
      }
    }
    
    if (missingFields.length > 0) {
      // Re-enable and reset confirm button state
      $('#confirmBtn').prop('disabled', false).html('<i class="fas fa-lock"></i> Place Order');
      
      // Scroll smoothly to the first missing field
      if (firstInvalidEl && firstInvalidEl.length) {
        $('html, body').animate({
          scrollTop: firstInvalidEl.offset().top - 140
        }, 400);
      }

      Swal.fire({
        icon: 'warning',
        title: 'Missing Required Information',
        html: `<p style="font-size: 14.5px; color: #64748b; margin-bottom: 16px;">Please complete the required details before placing your order:</p>
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

