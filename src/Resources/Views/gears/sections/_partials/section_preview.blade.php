{!! HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css') !!}
{!! HTML::style("/js/font-awesome/css/font-awesome.min.css") !!}
{!! HTML::script('/js/jquery-2.1.4.min.js') !!}
{!! HTML::script('/js/bootstrap.min.js') !!}
{!! HTML::style("/js/animate/css/animate.css") !!}
{!! HTML::style("/css/preview-template.css") !!}
{!! HTML::style("/css/core_styles.css") !!}
{!! HTML::style("/css/builder-tool.css") !!}
{!! BBFrameworkCss() !!}
<body>
{!! csrf_field() !!}
@include('resources::assests.magicModal')
<div class="container-fluid">
<div class="body_ui corepreviewSetting previewcontent activeprevew" data-settinglive="content" id="widget_container">
    
    {!! $htmlBody !!}
    
</div>
</div>
<div class="Settings_ui coresetting hide animated bounceInRight" data-settinglive="settings">
      <div class="container-fluid"> 
    {!! Form::model($settings,['url'=>'/admin/uploads/gears/sections/settings/'.$id, 'id'=>'add_custome_page','files'=>true]) !!}
    <input name="itemname" type="hidden" data-parentitemname="itemname" />
    @if($section->have_settings)
        {!! $htmlSettings !!}
    @else
        <div class="col-md-9">No Settings Available</div>
    @endif
</div>
    {!! Form::close() !!}
</div>


<button data-settingaction="save" class="hide" id="settings_savebtn"></button>
<input type="hidden"  id="hidden_data" value='{!!$settings_json!!}'>
<body>
{!! HTML::script("/js/UiElements/bb_styles.js?v.5") !!}
{!! HTML::script("/js/UiElements/ui-preview-setting.js") !!}
{!! HTML::script("/js/UiElements/ui-settings.js") !!}
{!! BBFrameworkJs() !!}
