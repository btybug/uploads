@extends('layouts.uiPreview')

@section('content')
    <div class="previewlivesettingifream">
        <iframe src="{!! $ifrem['body'] !!}">

        </iframe>
    </div>
@stop

@section('CSS')

    {!! HTML::style('/resources/assets/css/preview-template.css') !!}
    {!! HTML::style("resources/assets/js/animate/css/animate.css") !!}
    {!! HTML::style("resources/assets/css/preview-template.css") !!}
@stop
@section('JS')
    {!! HTML::script("resources/assets/js/UiElements/ui-preview-setting.js") !!}
    {!! HTML::script("resources/assets/js/UiElements/ui-settings.js") !!}
@stop
