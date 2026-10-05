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
  $waPos      = $agencyObj->whatsapp_btn_position ?? 'left';
  $waMsg      = $agencyObj->whatsapp_default_msg ?? 'Hello! I am interested in your services.';

  $cleanWaNumber = preg_replace('/[^0-9]/', '', $waNumber);
  $callUrl = !empty($callNumber) ? 'tel:' . preg_replace('/[^0-9\+]/', '', $callNumber) : '#';
  $siteTitle = $agencyObj->site_title ?? 'Support Team';
@endphp

@if($isCallWhatsappAllowed)
  <style>
    /* Floating Action Buttons */
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
      border: none;
      cursor: pointer;
    }
    .wb-floating-action-btn:hover {
      transform: scale(1.15) translateY(-3px) !important;
      color: #ffffff !important;
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

    /* Jumping Bounce Animation */
    @keyframes wbJumpBounce {
      0%, 100% { transform: translateY(0) scale(1); }
      20% { transform: translateY(-12px) scale(1.06); }
      40% { transform: translateY(0) scale(0.96); }
      60% { transform: translateY(-6px) scale(1.03); }
      80% { transform: translateY(0) scale(1); }
    }
    .wb-btn-jump {
      animation: wbJumpBounce 2.6s cubic-bezier(0.28, 0.84, 0.42, 1) infinite;
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

    /* WhatsApp Chatbot Widget Popup Window */
    .wb-wa-chatbot-popup {
      position: fixed;
      bottom: 90px;
      z-index: 999999;
      width: 360px;
      max-width: calc(100vw - 32px);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      display: none;
      flex-direction: column;
      background: #efeae2;
      animation: wbWaPopIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .wb-wa-chatbot-popup.pos-left { left: 24px; right: auto; }
    .wb-wa-chatbot-popup.pos-right { right: 24px; left: auto; }

    @keyframes wbWaPopIn {
      from { opacity: 0; transform: translateY(30px) scale(0.92); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Chatbot Header */
    .wb-wa-header {
      background: linear-gradient(135deg, #075E54 0%, #128C7E 100%);
      color: #ffffff;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
    }
    .wb-wa-header-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .wb-wa-avatar {
      position: relative;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #075E54;
      font-size: 22px;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .wb-wa-avatar-status {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 12px;
      height: 12px;
      background: #25D366;
      border: 2px solid #075E54;
      border-radius: 50%;
    }
    .wb-wa-title {
      font-weight: 700;
      font-size: 15px;
      line-height: 1.2;
      margin: 0;
    }
    .wb-wa-subtitle {
      font-size: 12px;
      opacity: 0.85;
      margin: 2px 0 0 0;
    }
    .wb-wa-close-btn {
      background: rgba(255,255,255,0.15);
      border: none;
      color: #ffffff;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.2s;
    }
    .wb-wa-close-btn:hover {
      background: rgba(255,255,255,0.3);
    }

    /* Chatbot Body */
    .wb-wa-body {
      padding: 16px;
      max-height: 320px;
      overflow-y: auto;
      background-color: #efeae2;
      background-image: radial-gradient(#cbd5e1 1px, transparent 0);
      background-size: 16px 16px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .wb-wa-date-badge {
      align-self: center;
      background: rgba(255,255,255,0.85);
      color: #64748b;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 12px;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .wb-wa-msg-bubble {
      background: #ffffff;
      padding: 12px 14px;
      border-radius: 0 14px 14px 14px;
      max-width: 88%;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      position: relative;
      font-size: 13.5px;
      line-height: 1.5;
      color: #1e293b;
    }
    .wb-wa-msg-author {
      font-size: 11px;
      font-weight: 700;
      color: #075E54;
      margin-bottom: 3px;
    }
    .wb-wa-msg-time {
      font-size: 10px;
      color: #94a3b8;
      text-align: right;
      margin-top: 4px;
    }
    .wb-wa-presets {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-top: 4px;
    }
    .wb-wa-preset-btn {
      background: #ffffff;
      border: 1px solid #25D366;
      color: #075E54;
      font-size: 12.5px;
      font-weight: 600;
      padding: 8px 12px;
      border-radius: 20px;
      text-align: left;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .wb-wa-preset-btn:hover {
      background: #25D366;
      color: #ffffff;
      transform: translateX(3px);
    }

    /* Chatbot Footer Input */
    .wb-wa-footer {
      background: #f0f0f0;
      padding: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-top: 1px solid #e2e8f0;
    }
    .wb-wa-input {
      flex: 1;
      border: 1px solid #cbd5e1;
      border-radius: 20px;
      padding: 10px 16px;
      font-size: 13px;
      outline: none;
      background: #ffffff;
      color: #0f172a;
      transition: border-color 0.2s;
    }
    .wb-wa-input:focus {
      border-color: #128C7E;
    }
    .wb-wa-send-btn {
      background: #25D366;
      color: #ffffff;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 16px;
      box-shadow: 0 4px 10px rgba(37, 211, 102, 0.4);
      transition: transform 0.2s;
    }
    .wb-wa-send-btn:hover {
      transform: scale(1.1);
      background: #128C7E;
    }
  </style>

  <!-- FLOATING BUTTONS -->
  @if($enableWa)
    <button type="button" onclick="toggleWbWhatsappChatbot()" class="wb-floating-action-btn wb-floating-wa-btn wb-btn-pulse wb-btn-jump pos-{{ $waPos }}" style="position: fixed !important; bottom: 25px !important; z-index: 999999 !important;" title="Chat with us on WhatsApp" aria-label="WhatsApp Chatbot">
      <i class="fa-brands fa-whatsapp"></i>
    </button>
  @endif

  @if($enableCall)
    <a href="{{ $callUrl }}" class="wb-floating-action-btn wb-floating-call-btn wb-btn-pulse wb-btn-jump pos-{{ $callPos }}" style="position: fixed !important; bottom: 25px !important; z-index: 999999 !important;" title="Call Us" aria-label="Call Us">
      <i class="fa-solid fa-phone"></i>
    </a>
  @endif

  <!-- INTERACTIVE WHATSAPP CHATBOT POPUP WIDGET -->
  @if($enableWa)
    <div id="wbWhatsappChatWidget" class="wb-wa-chatbot-popup pos-{{ $waPos }}">
      <!-- Header -->
      <div class="wb-wa-header">
        <div class="wb-wa-header-info">
          <div class="wb-wa-avatar">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="wb-wa-avatar-status"></span>
          </div>
          <div>
            <h6 class="wb-wa-title">{{ $siteTitle }}</h6>
            <p class="wb-wa-subtitle"><i class="fa-solid fa-circle text-success me-1" style="font-size: 8px;"></i> Online • Typically replies instantly</p>
          </div>
        </div>
        <button type="button" class="wb-wa-close-btn" onclick="toggleWbWhatsappChatbot()" title="Close chat">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Body -->
      <div class="wb-wa-body">
        <div class="wb-wa-date-badge">Today</div>
        
        <div class="wb-wa-msg-bubble">
          <div class="wb-wa-msg-author">{{ $siteTitle }} Customer Support</div>
          Hi there! 👋 Welcome to {{ $siteTitle }}. How can we help you today? Type your message below or pick an option:
          <div class="wb-wa-msg-time">{{ date('h:i A') }}</div>
        </div>

        <!-- Quick Preset Options -->
        <div class="wb-wa-presets">
          <button type="button" class="wb-wa-preset-btn" onclick="setWbWaMsg('Hello! I would like to inquire about your pricing and packages.')">
            <i class="fa-solid fa-tag"></i> Ask about Pricing & Packages
          </button>
          <button type="button" class="wb-wa-preset-btn" onclick="setWbWaMsg('Hi! I need help with booking a service / appointment.')">
            <i class="fa-solid fa-calendar-check"></i> Book a Service / Appointment
          </button>
          <button type="button" class="wb-wa-preset-btn" onclick="setWbWaMsg('Hello! I would like to speak directly with a representative.')">
            <i class="fa-solid fa-headset"></i> Connect with Customer Agent
          </button>
        </div>
      </div>

      <!-- Footer Input -->
      <div class="wb-wa-footer">
        <input type="text" id="wbWaInputText" class="wb-wa-input" value="{{ $waMsg }}" placeholder="Type your message..." onkeypress="if(event.key==='Enter') sendWbWaChat();">
        <button type="button" class="wb-wa-send-btn" onclick="sendWbWaChat()" title="Start WhatsApp Chat">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </div>
    </div>

    <script>
      const cleanWaPhone = "{{ $cleanWaNumber }}";

      function toggleWbWhatsappChatbot() {
        const widget = document.getElementById('wbWhatsappChatWidget');
        if (!widget) return;
        if (widget.style.display === 'flex') {
          widget.style.display = 'none';
        } else {
          widget.style.display = 'flex';
          const input = document.getElementById('wbWaInputText');
          if (input) {
            input.focus();
          }
        }
      }

      function setWbWaMsg(msgText) {
        const input = document.getElementById('wbWaInputText');
        if (input) {
          input.value = msgText;
          input.focus();
        }
      }

      function sendWbWaChat() {
        const input = document.getElementById('wbWaInputText');
        const text = input ? input.value.trim() : "";
        if (!cleanWaPhone) {
          alert('WhatsApp phone number is not configured yet.');
          return;
        }
        const waUrl = "https://wa.me/" + cleanWaPhone + "?text=" + encodeURIComponent(text);
        window.open(waUrl, '_blank');
      }
    </script>
  @endif
@endif
