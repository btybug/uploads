@extends('btybug::layouts.uiPreview')

@section('content')
    <div class="previewlivesettingifream">
        <iframe src="{!! $data['body'] !!}">

        </iframe>
    </div>
@stop

@section('CSS')

    {!! HTML::style('/css/preview-template.css') !!}
    {!! HTML::style("/js/animate/css/animate.css") !!}
    {!! HTML::style("/css/preview-template.css") !!}
@stop
@section('JS')
    {!! HTML::script("/js/UiElements/ui-preview-setting.js?v=99") !!}
    {!! HTML::script("/js/UiElements/ui-settings.js") !!}
    {!! HTML::script("/js/UiElements/bb_div.js") !!}
@stop
