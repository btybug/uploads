<div style="margin-bottom: 200px;" class="popup_div popup_div_{!!$style_id!!}">
       <div class="col-xs-6 col-md-6 left_side">
        @php
            $css= str_replace( ".", " " , "$style_item->css_data");
            $css = str_replace( "{", " ", "$css");
            $css = str_replace( "}", " ", "$css");
            $css = str_replace( "$style_item->slug", " ", "$css");
            $css= trim($css);
        @endphp

        {{ HTML::ul($errors->all()) }}

        {{ Form::open(array('method'=>'post', 'url' => '/admin/uploads/assets/styles/style_preview/css', 'id'=>'cssedit') )}}
          
      <textarea id="css_editor" style="height: 350px; width: 100%; display: none;" name="editor">
             {!! $css!!}
       </textarea>
       <div id="editor">{!! $css!!}</div>
         
        <input type="hidden" name="styleitom" value="{!! $style_item->id !!}">

        {{ Form::button('Save!', array('class' => 'btn btn-primary saveCss')) }}

        {{ Form::close() }}

        </div>
    <div class="col-xs-6 col-md-6 right_side"><div id="output" class="outputpreview">{!! $style_item->html !!}</div></div>
    <button class="close_icon" data-id="{!!$style_id!!}"><i class="fa fa-times" aria-hidden="true"></i></button>
    <div id="msg"></div>
</div>

