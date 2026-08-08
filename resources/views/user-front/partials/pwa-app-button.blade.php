@if (empty($user->preview_template) || $user->preview_template != 1 || request()->getHost() != env('WEBSITE_HOST'))
<div class="get-our-app-widget my-3">
  <span class="d-block text-muted small font-weight-bold text-uppercase mb-2" style="letter-spacing: 1px; font-size: 11px; color: #888;">{{ $keywords['GET OUR APP'] ?? __('GET OUR APP') }}</span>
  <button type="button" onclick="triggerPwaInstall()" class="btn-install-our-app d-inline-flex align-items-center px-3 py-2 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; text-decoration: none; outline: none;">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="margin-right: 10px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg">
      <path d="M3.60938 1.8125C3.3125 1.95312 3 2.25 3 2.65625V21.3438C3 21.75 3.3125 22.0469 3.60938 22.1875L13.8281 12L3.60938 1.8125Z" fill="#00D2FF"/>
      <path d="M17.1562 8.67188L14.7188 11.1094L14.7188 12.8906L17.1562 15.3281L20.8125 13.2656C21.4688 12.8906 21.4688 12.1094 20.8125 11.7344L17.1562 8.67188Z" fill="#FFC107"/>
      <path d="M14.7188 12.8906L13.8281 12L3.60938 22.1875C3.79688 22.2812 4.07812 22.2812 4.35938 22.1406L14.7188 16.3125L14.7188 12.8906Z" fill="#FF3D00"/>
      <path d="M14.7188 11.1094L4.35938 5.28125C4.07812 5.14062 3.79688 5.14062 3.60938 5.23438L13.8281 12L14.7188 11.1094Z" fill="#4CAF50"/>
    </svg>
    <div style="text-align: left; line-height: 1.1;">
      <div style="font-size: 8px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">{{ $keywords['DOWNLOAD & INSTALL'] ?? __('DOWNLOAD & INSTALL') }}</div>
      <div style="font-size: 13px; font-weight: 700; white-space: nowrap;">{{ $keywords['Our Mobile App'] ?? __('Our Mobile App') }}</div>
    </div>
  </button>
</div>
@endif
