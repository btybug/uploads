{!! HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css') !!}
{!! HTML::style("/resources/views/layouts/themes/admintheme/css/font-awesome/css/font-awesome.min.css") !!}

{!! HTML::script('/resources/assets/js/jquery-2.1.4.min.js') !!}
{!! HTML::script("resources/assets/js/jqueryui/js/jquery-ui.min.js") !!}
{!! HTML::script('/resources/assets/js/bootstrap.min.js') !!}
{!! HTML::style("resources/assets/js/animate/css/animate.css") !!}
{!! HTML::style("resources/assets/css/preview-template.css") !!}
{!! HTML::style("resources/assets/css/builder-tool.css") !!}
{!! HTML::style("https://jqueryvalidation.org/files/demo/site-demos.css") !!}

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
    {!! Form::model($settings,['url'=>'/admin/uploads/gears/settings/'.$id, 'id'=>'add_custome_page','files'=>true]) !!}

    @if($ui->have_settings)
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
{!! HTML::style("resources/assets/css/core_styles.css") !!}
{!! HTML::script("resources/assets/js/UiElements/bb_styles.js?v.5") !!}
{!! HTML::script("resources/assets/js/UiElements/ui-preview-setting.js") !!}
{!! HTML::script("resources/assets/js/UiElements/ui-settings.js") !!}
{!! HTML::script("https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js") !!}
{!! HTML::script("https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js") !!}


