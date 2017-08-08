{!! HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css') !!}
{!! HTML::style("/resources/views/layouts/themes/admintheme/css/font-awesome/css/font-awesome.min.css") !!}
{!! HTML::style("/js/jqueryui/css/jquery-ui.min.css") !!}
{!! BBFrameworkCss() !!}
{!! HTML::script('/js/jquery-2.1.4.min.js') !!}
{!! HTML::script('/js/bootstrap.min.js') !!}
{!! HTML::style('/css/jquery.tagit.css') !!}


@if($edit)
    <style>
        [data-layoutplaceholders] {
            cursor: cell
        }
    </style>
@endif
<body>
{!! csrf_field() !!}
{!! Form::open(['url'=>'/admin/resources/templates/settings/'.$id, 'id'=>'add_custome_page','files'=>true]) !!}
<div class="body_ui previewlivesettingifream" id="widget_container">
    {!! $htmlBody !!}
</div>
{!! Form::close() !!}
@include('resources::assests.magicModal')
<body>
@if($edit)
    {!! HTML::script("/js/jqueryui/js/jquery-ui.min.js") !!}
    {!! HTML::script("/js/UiElements/bb_styles.js?v.5") !!}
    {!! HTML::script("/js/UiElements/ui-preview-setting.js") !!}
    {!! HTML::script("/js/UiElements/ui-settings.js") !!}
    {!! HTML::script('/public/js/tag-it.js') !!}
@endif