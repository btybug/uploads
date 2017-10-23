{!! BBCss()  !!}
{!! HTML::style("/css/font-awesome/css/font-awesome.min.css") !!}
{!! HTML::style("/js/jquery-ui/jquery-ui.min.css") !!}
{!! HTML::style("/css/preview-template.css") !!}
{!! HTML::style('/css/cms.css') !!}
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
{!! BBJquery() !!}
{!! BBMainFrontJS() !!}
{!! HTML::script("/js/UiElements/bb_styles.js?v.5") !!}
{!! HTML::script("/js/UiElements/ui-preview-setting.js") !!}
{!! HTML::script("/js/UiElements/ui-settings.js") !!}
@if(isset($ui))
    {!! HTML::script('custom/js/'.str_replace(' ','-',$ui->slug).'.js') !!}
@endif