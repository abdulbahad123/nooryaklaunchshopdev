@if (empty($user) || empty($user->preview_template) || $user->preview_template != 1 || request()->getHost() != env('WEBSITE_HOST'))
@php
  $pwaTextIosTitle = $keywords['Install App'] ?? __('Install on iPhone/iPad');
  $pwaTextIosMsg = $keywords['To install this app, tap the Share icon at the bottom of Safari, then select "Add to Home Screen".'] ?? __('To install this app, tap the Share icon at the bottom of Safari, then select "Add to Home Screen".');
  $pwaTextInstalledTitle = $keywords['App Already Installed'] ?? __('App Already Installed');
  $pwaTextInstalledMsg = $keywords['This app is already installed! You can launch it from your home screen or app launcher.'] ?? __('This app is already installed! You can launch it from your home screen or app launcher.');
  $pwaTextDefaultTitle = $keywords['Install App'] ?? __('Install App');
  $pwaTextDefaultMsg = $keywords['To install this app, tap your browser menu (⋮ or Share icon) and select "Add to Home Screen" or "Install App".'] ?? __('To install this app, tap your browser menu (⋮ or Share icon) and select "Add to Home Screen" or "Install App".');
@endphp
<!-- PWA Sticky Bottom Install Banner -->
<div id="pwa-install-banner" class="pwa-install-banner-bar" style="display:none;">
  <div style="display:flex;align-items:center;gap:14px;">
    <img src="{{ !empty($userBs) && !empty($userBs->web_app_image) ? asset('assets/front/img/user/' . $userBs->web_app_image) : (!empty($userBs) && !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : asset('assets/front/img/673095353bc62.png')) }}"
         style="width:44px;height:44px;object-fit:contain;border-radius:10px;border:1px solid #e2e8f0;padding:2px;background:#fff;" alt="">
    <div>
      <div style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">
        Install Our {{ ($userBs->website_title ?? null) ?? (($user->shop_name ?? null) ?? ($user->username ?? 'Store')) }} App
      </div>
      <div style="font-size:11px;color:#64748b;margin-top:1px;">
        Install for a faster app-like experience
      </div>
    </div>
  </div>
  <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
    <button id="pwa-install-btn" onclick="triggerPwaInstall()"
            style="background:#0f172a;color:#fff;border:none;border-radius:6px;padding:8px 16px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:5px;outline:none;white-space:nowrap;">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
        <polyline points="7 10 12 15 17 10"></polyline>
        <line x1="12" y1="15" x2="12" y2="3"></line>
      </svg>
      Install
    </button>
    <button onclick="dismissPwaBanner()"
            style="background:transparent;border:none;font-size:20px;color:#94a3b8;cursor:pointer;padding:2px 6px;line-height:1;">&times;</button>
  </div>
</div>

<div id="pwa-install-fallback" class="pwa-install-fallback" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="pwa-fallback-title">
  <div class="pwa-install-fallback__panel">
    <h3 id="pwa-fallback-title">{{ $keywords['Install App'] ?? __('Install App') }}</h3>
    <p id="pwa-fallback-desc">{{ $keywords['To install this app, tap your browser menu and select Add to Home Screen or Install App.'] ?? __('To install this app, tap your browser menu (⋮ or Share icon) and select "Add to Home Screen" or "Install App".') }}</p>
    <button type="button" onclick="closePwaInstallFallback()">OK</button>
  </div>
</div>

<style>
.pwa-install-banner-bar{
  position:fixed; bottom:0; left:0; right:0; z-index:999999;
  background:#fff; border-top:2px solid #e2e8f0;
  box-shadow:0 -4px 20px rgba(0,0,0,.12);
  padding:10px 20px; display:flex; align-items:center;
  justify-content:space-between; gap:10px;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
}
.pwa-install-fallback{
  position:fixed; inset:0; z-index:1000000;
  background:rgba(15,23,42,.55);
  display:flex; align-items:center; justify-content:center;
  padding:20px;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
}
.pwa-install-fallback__panel{
  width:100%; max-width:420px; background:#fff; color:#0f172a;
  border-radius:12px; padding:24px; box-shadow:0 20px 50px rgba(0,0,0,.25);
}
.pwa-install-fallback__panel h3{
  margin:0 0 10px; font-size:18px; font-weight:700;
}
.pwa-install-fallback__panel p{
  margin:0 0 18px; font-size:14px; line-height:1.5; color:#475569;
}
.pwa-install-fallback__panel button{
  background:#0f172a; color:#fff; border:none; border-radius:8px;
  padding:10px 18px; font-size:14px; font-weight:600; cursor:pointer;
}
.pwa-install-nav-item {
  display: none !important;
}
</style>

<script>
  window.pwaInstallLabel = {!! json_encode($pwaTextDefaultTitle) !!};

  function isPwaInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches ||
           window.navigator.standalone === true ||
           localStorage.getItem('pwa_installed') === '1';
  }

  function checkInstalledRelatedApps() {
    if ('getInstalledRelatedApps' in navigator) {
      navigator.getInstalledRelatedApps().then(function(apps) {
        if (apps && apps.length > 0) {
          try { localStorage.setItem('pwa_installed', '1'); } catch(e) {}
          updatePwaInstallUi();
        }
      }).catch(function() {});
    }
  }

  function updatePwaInstallUi() {
    if (isPwaInstalled()) {
      var banner = document.getElementById('pwa-install-banner');
      if (banner) banner.style.display = 'none';
      return;
    }

    if (window.deferredPwaPrompt && !localStorage.getItem('pwa_dismissed')) {
      var banner = document.getElementById('pwa-install-banner');
      if (banner) banner.style.display = 'flex';
    }
  }

  function injectPwaNavLinks() {
    // Disabled to remove "Install App" from header menu across all themes
  }

  function showPwaFallback(title, message) {
    var titleEl = document.getElementById('pwa-fallback-title');
    var descEl = document.getElementById('pwa-fallback-desc');
    var fallback = document.getElementById('pwa-install-fallback');
    if (titleEl) titleEl.textContent = title;
    if (descEl) descEl.textContent = message;
    if (fallback) fallback.style.display = 'flex';
  }

  function triggerPwaInstall() {
    if (window.deferredPwaPrompt) {
      try {
        window.deferredPwaPrompt.prompt();
        window.deferredPwaPrompt.userChoice.then(function(result) {
          if (result && result.outcome === 'accepted') {
            try { localStorage.setItem('pwa_installed', '1'); } catch(e) {}
            dismissPwaBanner();
            updatePwaInstallUi();
          }
          window.deferredPwaPrompt = null;
        });
        return;
      } catch (err) {
        console.warn('PWA prompt error:', err);
      }
    }

    if (!window.pwaPromptAttempted && !isPwaInstalled()) {
      window.pwaPromptAttempted = true;
      var pollCount = 0;
      var checkInterval = setInterval(function() {
        pollCount++;
        if (window.deferredPwaPrompt) {
          clearInterval(checkInterval);
          window.pwaPromptAttempted = false;
          triggerPwaInstall();
        } else if (pollCount >= 10) {
          clearInterval(checkInterval);
          executePwaFallback();
        }
      }, 150);
      return;
    }

    executePwaFallback();
  }

  function executePwaFallback() {
    var isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    if (isIos) {
      showPwaFallback(
        {!! json_encode($pwaTextIosTitle) !!},
        {!! json_encode($pwaTextIosMsg) !!}
      );
      return;
    }

    if (isPwaInstalled()) {
      showPwaFallback(
        {!! json_encode($pwaTextInstalledTitle) !!},
        {!! json_encode($pwaTextInstalledMsg) !!}
      );
      return;
    }

    var isDesktop = !(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
    if (isDesktop) {
      showPwaFallback(
        "Install App on Computer",
        "To install this app on your PC or Mac, click the Install icon in the right side of your address bar (next to the bookmark star), or click Chrome Menu (⋮) → 'Install App'."
      );
      return;
    }

    showPwaFallback(
      {!! json_encode($pwaTextDefaultTitle) !!},
      {!! json_encode($pwaTextDefaultMsg) !!}
    );
  }

  function closePwaInstallFallback() {
    var fallback = document.getElementById('pwa-install-fallback');
    if (fallback) fallback.style.display = 'none';
  }

  function dismissPwaBanner() {
    var el = document.getElementById('pwa-install-banner');
    if (el) el.style.display = 'none';
    try { localStorage.setItem('pwa_dismissed', '1'); } catch(e) {}
  }

  document.addEventListener('DOMContentLoaded', function() {
    injectPwaNavLinks();
    checkInstalledRelatedApps();
    updatePwaInstallUi();
  });

  window.addEventListener('beforeinstallprompt', function(e) {
    if (e) {
      e.preventDefault();
      window.deferredPwaPrompt = e;
    }
    injectPwaNavLinks();
    updatePwaInstallUi();
  });

  window.addEventListener('appinstalled', function() {
    try { localStorage.setItem('pwa_installed', '1'); } catch(e) {}
    window.deferredPwaPrompt = null;
    closePwaInstallFallback();
    updatePwaInstallUi();
  });

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js', { scope: '/' })
      .then(function(r){ console.log('[PWA] SW registered', r.scope); })
      .catch(function(e){ console.warn('[PWA] SW failed', e); });
  }
</script>
@endif
