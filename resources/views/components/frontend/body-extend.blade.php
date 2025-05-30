@if(env('APP_ENV') != 'local')
    @if(Customsetting::get('google_tagmanager_id'))
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{Customsetting::get('google_tagmanager_id')}}"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif
@endif

{!! Customsetting::get('extra_body_scripts') !!}

@if(isset($model))
    {!! $model->metaData->top_body_scripts ?? '' !!}
@endif

@include('cookie-consent::index')
