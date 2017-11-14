@extends('layouts.uiPreview')

@section('content')
    <div class="previewlivesettingifream">
        <iframe src="{!! $ifrem['body'] !!}">

        </iframe>
    </div>
@stop

@section('CSS')

    {!! HTML::style('public/css/preview-template.css') !!}
    {!! HTML::style("public/js/animate/css/animate.css") !!}
    {!! HTML::style("public/css/preview-template.css") !!}
@stop
@section('JS')
    {!! HTML::script("public/js/UiElements/ui-preview-setting.js") !!}
    {!! HTML::script("public/js/UiElements/ui-settings.js") !!}
    {!! HTML::script("public/js/UiElements/bb_div.js") !!}
@stop
