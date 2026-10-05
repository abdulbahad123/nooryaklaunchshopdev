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
      width: 56px;
      height: 56px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff !important;
      font-size: 25px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
      transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      text-decoration: none !important;
    }
    .wb-floating-action-btn:hover {
      transform: scale(1.18) translateY(-4px) !important;
      color: #ffffff !important;
      animation-play-state: paused !important;
    }
    .wb-floating-call-btn {
      background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    }
    .wb-floating-call-btn.pos-left { left: 24px; right: auto; }
    .wb-floating-call-btn.pos-right { right: 24px; left: auto; }

    .wb-floating-wa-btn {
      background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    }
    .wb-floating-wa-btn.pos-right { right: 24px; left: auto; }
    .wb-floating-wa-btn.pos-left { left: 24px; right: auto; }

    /* Floating Jumping Bounce Animation (Ref Task 2) */
    @keyframes wbJumpBounce {
      0%, 100% {
        transform: translateY(0) scale(1);
      }
      20% {
        transform: translateY(-14px) scale(1.08);
      }
      40% {
        transform: translateY(0) scale(0.96);
      }
      60% {
        transform: translateY(-7px) scale(1.04);
      }
      80% {
        transform: translateY(0) scale(1);
      }
    }

    .wb-btn-jump {
      animation: wbJumpBounce 2.4s cubic-bezier(0.28, 0.84, 0.42, 1) infinite;
    }

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
      50% { transform: scale(1.28); opacity: 0; }
      100% { transform: scale(0.95); opacity: 0; }
    }
  </style>

  @if($enableWa)
    <a href="{{ $waUrl }}" target="_blank" class="wb-floating-action-btn wb-floating-wa-btn wb-btn-pulse wb-btn-jump" style="position: fixed !important; bottom: 25px !important; left: 25px !important; right: auto !important; z-index: 999999 !important;" title="Chat on WhatsApp" aria-label="Chat on WhatsApp">
      <i class="fa-brands fa-whatsapp"></i>
    </a>
  @endif

  @if($enableCall)
    <a href="{{ $callUrl }}" class="wb-floating-action-btn wb-floating-call-btn wb-btn-pulse wb-btn-jump" style="position: fixed !important; bottom: 25px !important; right: 25px !important; left: auto !important; z-index: 999999 !important;" title="Call Us" aria-label="Call Us">
      <i class="fa-solid fa-phone"></i>
    </a>
  @endif
@endif
