@php
  $popupsList = collect([]);
  try {
      if (\Illuminate\Support\Facades\Schema::hasTable('popups')) {
          $popupsList = \App\Models\Popup::where('status', 1)->orderBy('serial_number', 'ASC')->get();
      }
  } catch (\Throwable $e) {
      $popupsList = collect([]);
  }
@endphp

@if (count($popupsList) > 0)
  @foreach ($popupsList as $popup)
    @if ($popup->type == 1 && !empty($popup->image))
      {{-- Type 1: Image Only --}}
      <div id="popup-{{ $popup->id }}" class="modal fade popup-modal" tabindex="-1" role="dialog" aria-hidden="true" data-delay="{{ ($popup->delay ?? 5) * 1000 }}">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content border-0 bg-transparent text-center">
            <button type="button" class="close text-white ml-auto mr-2" data-dismiss="modal" aria-label="Close" style="font-size: 28px; opacity: 0.9;">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body p-0">
              <a href="{{ $popup->button_url ?? '#' }}" target="_blank">
                <img src="{{ asset('assets/front/img/popups/' . $popup->image) }}" class="img-fluid rounded" alt="{{ $popup->name }}">
              </a>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endforeach

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var popups = document.querySelectorAll('.popup-modal');
      popups.forEach(function(popup) {
        var delay = parseInt(popup.getAttribute('data-delay'), 10) || 5000;
        setTimeout(function() {
          if (window.$ && typeof $(popup).modal === 'function') {
            $(popup).modal('show');
          }
        }, delay);
      });
    });
  </script>
@endif