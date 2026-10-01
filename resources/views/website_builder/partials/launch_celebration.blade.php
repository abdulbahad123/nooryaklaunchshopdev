@if (request()->has('launched') || session()->has('success') || request()->has('celebrate'))
<!-- Celebration Crackers & Toast Banner -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

<div id="celebration_toast" class="position-fixed" style="top: 24px; right: 24px; z-index: 999999; animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
  <div class="card border-0 shadow-lg p-3 text-white rounded-4 d-flex flex-row align-items-center gap-3" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: 1px solid rgba(255,255,255,0.3) !important; box-shadow: 0 20px 40px rgba(16,185,129,0.35) !important;">
    <div class="fs-2 text-white flex-shrink-0">🎉</div>
    <div class="flex-grow-1" style="min-width: 220px;">
      <div class="fw-extrabold" style="font-size: 15px; letter-spacing: -0.2px;">Congratulations! 🚀</div>
      <div class="small opacity-90" style="font-size: 13px; line-height: 1.35;">{{ session('success') ?? 'Your store website has been launched live successfully!' }}</div>
    </div>
    <button type="button" class="btn-close btn-close-white ms-auto small flex-shrink-0" onclick="document.getElementById('celebration_toast').style.display='none'"></button>
  </div>
</div>

<style>
@keyframes slideInRight {
  from { transform: translateX(120%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fire top celebration crackers for 2.5 seconds
  if (typeof confetti === 'function') {
    var duration = 2.5 * 1000;
    var animationEnd = Date.now() + duration;
    var defaults = { startVelocity: 35, spread: 360, ticks: 70, zIndex: 999999 };

    function randomInRange(min, max) {
      return Math.random() * (max - min) + min;
    }

    var interval = setInterval(function() {
      var timeLeft = animationEnd - Date.now();

      if (timeLeft <= 0) {
        return clearInterval(interval);
      }

      var particleCount = 50 * (timeLeft / duration);
      // Top Left Crackers
      confetti(Object.assign({}, defaults, { particleCount: particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() * 0.2 } }));
      // Top Right Crackers
      confetti(Object.assign({}, defaults, { particleCount: particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() * 0.2 } }));
    }, 250);
  }

  // Hide toast after 4 seconds
  setTimeout(function() {
    var toast = document.getElementById('celebration_toast');
    if (toast) {
      toast.style.transition = 'opacity 0.5s ease';
      toast.style.opacity = '0';
      setTimeout(function() { toast.style.display = 'none'; }, 500);
    }
  }, 4000);
});
</script>
@endif
