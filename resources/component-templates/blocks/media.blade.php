<section class="@if($data['top_margin'] ?? false) pt-16 sm:pt-24 @endif @if($data['bottom_margin'] ?? false) pb-16 sm:pb-24 @endif">
    <x-container :show="$data['in_container'] ?? true">
        <div class="w-full mx-auto" style="max-width: {{ $data['max_width_number'] . $data['max_width_type'] }};">
            <x-dashed-files::image
                class="block w-full mx-auto rounded-lg"
                :mediaId="$data['media']"
                :manipulations="[
                        'widen' => $data['max_width_type'] == 'px' ? $data['max_width_number'] : 1000,
                    ]"
            />
        </div>
    </x-container>
</section>
