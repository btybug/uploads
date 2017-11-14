{!! HTML::script("public/js/jquery-2.1.4.min.js") !!}
{!! HTML::script("public/js/bootstrap.js?v=".rand('1111','9999')) !!}
{!! BBCss()  !!}
{!! HTML::style("public/css/font-awesome/css/font-awesome.min.css") !!}
{!! HTML::style("public/js/jquery-ui/jquery-ui.min.css") !!}
{!! HTML::style("public/css/preview-template.css") !!}
{!! HTML::style('public/css/cms.css') !!}
{!! HTML::style('custom/css/'.$ui->slug.'.css') !!}
<body>
{!! csrf_field() !!}
@include('resources::assests.magicModal')
<div class="container-fluid">
    <div class="body_ui corepreviewSetting previewcontent activeprevew" data-settinglive="content"
         id="widget_container">
        {!! $htmlBody !!}
    </div>
</div>
<div class="Settings_ui coresetting hide animated bounceInRight" data-settinglive="settings">
    <div class="container-fluid">

        {!! Form::model($settings,['url'=>'/admin/uploads/gears/units/settings/'.$id, 'id'=>'add_custome_page','files'=>true]) !!}
        <input name="itemname" type="hidden" data-parentitemname="itemname"/>
        {!! $htmlSettings !!}
        {!! Form::close() !!}
    </div>
</div>


<button data-settingaction="save" class="hide" id="settings_savebtn"></button>
<input type="hidden" id="hidden_data" value='{!!$settings_json!!}'>
<body>
{!! BBMainFrontJS() !!}
{!! HTML::script("public/js/UiElements/bb_styles.js?v.5") !!}
{!! HTML::script("public/js/UiElements/ui-preview-setting.js?v=999") !!}
{!! HTML::script("public/js/UiElements/ui-settings.js") !!}
@if(isset($ui))
    {!! HTML::script('custom/js/'.str_replace(' ','-',$ui->slug).'.js') !!}
@endif