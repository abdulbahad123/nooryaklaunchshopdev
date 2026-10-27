<div class="modal fade" id="aiItemSeoModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header d-flex align-items-center justify-content-between">
        <h5 class="modal-title" id="aiItemSeoModalTitle">{{ __('AI Generate Item Content') }}</h5>
        @php
          $aiUsage = \App\Http\Helpers\AiEngineHelper::getAiLimits();
          $engineInfo = $aiUsage['engine'];
        @endphp
        <div class="d-inline-flex align-items-center ml-auto mr-3" style="gap: 8px;">
          <span class="badge {{ $engineInfo['badge_class'] }}" style="{{ $engineInfo['badge_style'] }}">
            <i class="fas fa-robot mr-1"></i> {{ __('Mode') }}: {{ $engineInfo['display'] }}
          </span>
          <span class="badge badge-light border text-dark" style="font-size: 11px; padding: 4px 10px; border-radius: 12px;" title="{{ __('Tokens Used / Total Limit') }}">
            <i class="fas fa-font mr-1 text-primary"></i> {{ __('Tokens') }}: {{ $aiUsage['used_tokens'] }} / {{ $aiUsage['token_label'] }}
          </span>
        </div>
        <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="alert alert-info mb-3">
          {{ __('AI will generate content for the selected field and selected language') . '.' }}
        </div>
        <input type="hidden" id="ai_item_field" value="">
        <input type="hidden" id="ai_item_lang" value="">

        <div class="form-group">
          <label class="mb-1 d-block">{{ __('Prompt') }} <span class="text-danger">*</span></label>
          <textarea id="ai_item_prompt" class="form-control" rows="6"
            placeholder="{{ __('Example') . ' :'.__('Premium leather wallet, handmade, slim, RFID, for men. Tone: premium, persuasive. Include keywords: wallet, leather, rfid') . '.' }}"></textarea>
          <small class="text-muted d-block mt-1">
            {{__('Tip'). ': ' . __('Write features, audience, use-case, tone, and keywords') }}
          </small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="btn btn-primary" id="aiItemSeoConfirmBtn">{{ __('Generate') }}</button>
      </div>

    </div>
  </div>
</div>
