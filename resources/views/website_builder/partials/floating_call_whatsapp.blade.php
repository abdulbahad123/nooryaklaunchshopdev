@php
  $agencyObj = $agency ?? $interior ?? null;
  $custObj = $customer ?? null;
  
  $isCallWhatsappAllowed = true;
  if ($agencyObj && method_exists($agencyObj, 'isFeatureEnabled')) {
      $isCallWhatsappAllowed = $agencyObj->isFeatureEnabled('call_whatsapp', $custObj);
  }

  $enableCall = $agencyObj->enable_call_btn ?? true;
  $callNumber = $agencyObj->call_phone_number ?? $agencyObj->phone ?? '';
  $callPos    = $agencyObj->call_btn_position ?? 'left';

  $enableWa   = $agencyObj->enable_whatsapp_btn ?? true;
  $waNumber   = $agencyObj->whatsapp_number ?? $agencyObj->phone ?? '';
  $waPos      = $agencyObj->whatsapp_btn_position ?? 'right';
  $waMsg      = $agencyObj->whatsapp_default_msg ?? 'Hello! I am interested in your services.';

  $cleanWaNumber = preg_replace('/[^0-9]/', '', $waNumber);
  $waUrl = !empty($cleanWaNumber) ? 'https://wa.me/' . $cleanWaNumber . '?text=' . urlencode($waMsg) : '#';
  $callUrl = !empty($callNumber) ? 'tel:' . preg_replace('/[^0-9\+]/', '', $callNumber) : '#';
@endphp

@if($isCallWhatsappAllowed)
  <style>
    .wb-floating-action-btn {
      position: fixed;
      bottom: 24px;
      z-index: 99999;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff !important;
      font-size: 24px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.22);
      transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      text-decoration: none !important;
    }
    .wb-floating-action-btn:hover {
      transform: scale(1.12) translateY(-3px);
      color: #ffffff !important;
    }
    .wb-floating-call-btn {
      background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    }
    .wb-floating-call-btn.pos-left { left: 24px; }
    .wb-floating-call-btn.pos-right { right: 24px; }

    .wb-floating-wa-btn {
      background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    }
    .wb-floating-wa-btn.pos-right { right: 24px; }
    .wb-floating-wa-btn.pos-left { left: 24px; }

    .wb-btn-pulse {
      position: relative;
    }
    .wb-btn-pulse::before {
      content: '';
      position: absolute;
      top: -4px; left: -4px; right: -4px; bottom: -4px;
      border-radius: 50%;
      border: 2px solid currentColor;
      opacity: 0.6;
      animation: wbPulseRing 2s infinite cubic-bezier(0.455, 0.03, 0.515, 0.955);
    }
    @keyframes wbPulseRing {
      0% { transform: scale(0.95); opacity: 0.8; }
      50% { transform: scale(1.25); opacity: 0; }
      100% { transform: scale(0.95); opacity: 0; }
    }
  </style>

  @if($enableCall && !empty($callNumber))
    <a href="{{ $callUrl }}" class="wb-floating-action-btn wb-floating-call-btn pos-{{ $callPos }} wb-btn-pulse" title="Call Us" aria-label="Call Us">
      <i class="fa-solid fa-phone"></i>
    </a>
  @endif

  @if($enableWa && !empty($waNumber))
    <a href="{{ $waUrl }}" target="_blank" class="wb-floating-action-btn wb-floating-wa-btn pos-{{ $waPos }} wb-btn-pulse" title="Chat on WhatsApp" aria-label="Chat on WhatsApp">
      <i class="fa-brands fa-whatsapp"></i>
    </a>
  @endif
@endif
