@extends('btybug::layouts.units')

@section('content')
    <div class="previewlivesettingifream">
        <iframe src="{!! $data['body'] !!}">

        </iframe>
    </div>
@stop

@section('CSS')

    {!! HTML::style('public/css/preview-template.css') !!}
    {!! HTML::style("public/css/animate.css") !!}
    {!! HTML::style("public/css/preview-template.css") !!}
@stop
@section('JS')
    {!! HTML::script("public/js/UiElements/ui-preview-setting.js?v=999") !!}
    {!! HTML::script("public/js/UiElements/ui-settings.js") !!}
@stop
