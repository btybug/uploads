@extends('cms::layouts.uiPreview')

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
    {!! HTML::script("/js/UiElements/ui-preview-setting.js") !!}
    {!! HTML::script("/js/UiElements/ui-settings.js") !!}
@stop
