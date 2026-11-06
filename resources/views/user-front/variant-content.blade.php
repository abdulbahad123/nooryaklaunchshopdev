@php
  $seen_variations = [];
  $currSym = !empty($userCurrentCurr) ? $userCurrentCurr->symbol : (currency_sign() ?: '₹');
  $currPos = !empty($userCurrentCurr) ? $userCurrentCurr->symbol_position : 'left';
@endphp
@foreach ($product_variations as $product_variation)
  @php
    $product_variation_content = App\Models\User\ProductVariationContent::where([
        ['product_variation_id', $product_variation->id],
        ['language_id', $language_id],
    ])->first();

    if (!$product_variation_content) {
        $product_variation_content = App\Models\User\ProductVariationContent::where('product_variation_id', $product_variation->id)->first();
    }

    $vTitle = function_exists('resolveProductVariationTitle')
        ? resolveProductVariationTitle($product_variation, $product_variation_content, $language_id, $loop->iteration)
        : ($product_variation_content?->variation_name ?? ('Option ' . $loop->iteration));

    // Prevent duplicate variation groups (e.g. "Pack" rendered twice)
    if (isset($seen_variations[$vTitle])) {
        continue;
    }
    $seen_variations[$vTitle] = true;

    $variant_content_options = App\Models\User\ProductVariantOption::where([
        ['product_variation_id', $product_variation->id],
    ])->get();
  @endphp
  @if (count($variant_content_options) > 0)
    <div class="col-lg-6 mb-20">
      <h4 class="mb-2 color-primary fw-semibold" style="font-size: 14px;">{{ $vTitle }}</h4>
      @foreach ($variant_content_options as $variant_content_option)
        <div class="d-flex justify-content-left gap-10 py-1">
          @php
            $variant_option_contents = App\Models\User\ProductVariantOptionContent::where([
                ['product_variant_option_id', $variant_content_option->id],
                ['language_id', $language_id],
            ])->first();

            if (!$variant_option_contents) {
                $variant_option_contents = App\Models\User\ProductVariantOptionContent::where('product_variant_option_id', $variant_content_option->id)->first();
            }

            $opt_name = function_exists('resolveProductVariantOptionLabel')
                ? resolveProductVariantOptionLabel($variant_option_contents, $variant_content_option, $language_id)
                : ($variant_option_contents?->option_name ?? ('Option ' . $variant_content_option->id));

            $sup_option_content = make_input_name($opt_name);
            $id_name = make_input_name($opt_name);
            $main_id = $sup_option_content . '_' . $id_name . '_' . $variant_content_option->id;
            $opt_group_name = $vTitle;
          @endphp
          <div>
            <input class="voptions form-check-input" data-option_id="{{ $variant_content_option->id }}"
              data-variation_id="{{ $product_variation->id }}" data-option="{{ $opt_group_name }}"
              data-name="{{ $opt_name }}"
              data-price="{{ currency_converter($variant_content_option->price) }}"
              data-stock="{{ $variant_content_option->stock }}" type="radio"
              name="{{ make_input_name($opt_group_name) }}[]" value=""
              id="voptions_{{ $main_id }}">
            <label for="voptions_{{ $main_id }}">
              {{ $opt_name }}</label>
          </div>

          <span class="variants_price">(<i
              class="fas fa-plus"></i>{{ symbolPrice($currPos, $currSym, currency_converter($variant_content_option->price)) }})</span>
        </div>
      @endforeach
    </div>
  @endif
@endforeach
@if (empty($seen_variations))
  <h4 class="text-center">{{ $keywords['No Variation Found'] ?? __('No Variation Found') }}</h4>
@endif
