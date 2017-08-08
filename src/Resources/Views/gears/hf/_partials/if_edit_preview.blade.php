{!! HTML::script("/js/jquery-2.1.4.min.js") !!}
{!! HTML::script("/js/jqueryui/js/jquery-ui.min.js") !!}
{!! HTML::script("/js/bootstrap.min.js") !!}
{!! HTML::script("/js/UiElements/bb_styles.js?v.5") !!}
{!! HTML::style("/css/bootstrap.css") !!}
{!! HTML::style("/js/font-awesome/css/font-awesome.min.css") !!}
{!! HTML::style("/js/jqueryui/css/jquery-ui.min.css") !!}
{!! HTML::style('/css/preview-template.css') !!}
{!! HTML::style("/css/core_styles.css") !!}
{!! HTML::style("/css/builder-tool.css") !!}


{!! csrf_field() !!}
<div class="body_ui previewlivesettingifream" data-settinglive="content" id="widget_container">
    {!! $htmlBody !!}
</div>
<div class="layoutCoresetting hide animated bounceInRight" data-settinglive="settings">
 <div class="container-fluid">
  {!! Form::model($settings,['url'=>'/admin/uploads/gears/h-f/settings/'.$id, 'id'=>'add_custome_page','files'=>true]) !!}
  <input name="itemname" type="hidden" data-parentitemname="itemname" />
  {!! $htmlSettings !!}
  <button class="btn btn-success hide" id="settings_savebtn" data-settingaction="save"> save</button>
  {!! Form::close() !!}

  </div>
</div>

@include('resources::assests.magicModal')
{!! HTML::script("/js/jquery-2.1.4.min.js") !!}
{!! HTML::script("/js/jqueryui/js/jquery-ui.min.js") !!}
{!! HTML::script("/js/bootstrap.min.js") !!}
{!! HTML::script("/js/UiElements/bb_styles.js?v.5") !!}
{!! HTML::script("/js/UiElements/ui-preview-setting.js") !!}
{!! HTML::script("/js/UiElements/ui-settings.js") !!}



@yield('JS')

@stack('javascript')