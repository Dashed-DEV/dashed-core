@props(['icon'])

<button
    {{ $attributes->class('inline-flex items-center justify-center relative text-gray-600 hover:text-primary-500 size-5') }}
    type="button"
>
{{--    <div class="-inset-2 rounded-full absolute hover:bg-gray-100/50 transition"></div>--}}

    <x-dynamic-component :component="$icon" class="size-5" />
</button>