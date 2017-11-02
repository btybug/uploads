@extends('btybug::layouts.mTabs',['index'=>'uploads_assets'])
<!-- Nav tabs -->
@section('tab')
    <div class="row list_222">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cms_module_list module_list_1">
            <h3 class="menuText f-s-17">
                <span class="module_icon_main"></span>

                <span class="module_icon_main_text"> Styles
                    {!! Form::select('types',$types,$main_type,['class' => 'form-control select-types']) !!}
                </span>
            </h3>
            <hr>
            <ul class="list-unstyled menuList" id="components-list">
                {{--main types--}}
                @foreach($subs as $key => $value)
                    @if(isset($_GET['sub']) && $_GET['sub'] == $key)
                        <li class="active_class">
                    @else
                        @if($loop->first and ! isset($_GET['sub']))
                            <li class="active_class">
                        @else
                            <li>
                                @endif
                                @endif

                                <a href="?type={{$main_type}}&sub={{$key}}" main-type="{{$key}}" rel="tab"
                                   class="tpl-left-items">
                                    <span class="module_icon"></span> {{$value}}
                                    <a data-type="{{$key}}" class="add-class-modal pull-right gettype"></a>
                                </a>
                            </li>
                            @endforeach
            </ul>
        </div>
        {!! HTML::image('/images/ajax-loader5.gif', 'a picture', array('class' => 'thumb img-loader hide')) !!}
        <div class="tpl-list col-md-9 col-lg-9">
            @include('uploads::assets.styles._partials.list_cube')
            {{--@include('uploads::assets.styles._partials.subs_list')--}}
        </div>
    </div>

    <div class="modal fade" id="uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url'=>'/admin/uploads/assets/styles/upload','class'=>'dropzone', 'id'=>'my-awesome-dropzone']) !!}
                    {!! Form::hidden('style_id',null,['id' => 'sub_item_upl']) !!}

                    {!! Form::close() !!} </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Sub</h4>
                </div>
                <div class="modal-body">
                {!! Form::open(['url'=>'/admin/uploads/assets/styles/add-sub','class' => 'form-horizontal']) !!}
                {!! Form::hidden('type',null,['id' => 'class-type']) !!}
                <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="name">Sub Name</label>
                        <div class="col-md-4">
                            {!! Form::text('name',null,['class' => 'form-control input-md']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="name"></label>
                        <div class="col-md-4">
                            {!! Form::submit('Create',['class' => 'btn btn-success']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!} </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Sub</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    @include('resources::assests.deleteModal',['title'=>'Delete Style'])
@stop
@section('CSS')
    {!! HTML::style('app/Modules/Resources/css/new-store.css') !!}
    {!! HTML::style('app/Modules/Resources/css/style_cube.css') !!}
@stop
@section('JS')
    {!! HTML::script('/js/dropzone/js/dropzone.js') !!}
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function () {
                this.on("success", function (file) {
//                    location.reload();
                });
            }
        };


        $(document).ready(function () {

            $(".select-types").on('change', function () {
                console.log($(this).val());
                window.location.href = window.location.pathname + "?type=" + $(this).val();
            });

            var p = "{!! $_GET['sub'] or null !!}";


            var scrollTop = $(window).scrollTop();
            $(window).resize(function () {
                $('body').find('.popup_div').css({
                    'height': $(window).height() - 227,
                    "top": scrollTop
                });
            });

            $("body").on("click", ".add-class-modal", function () {
                $('#class-type').val($('.active_class').find($('.gettype')).attr('data-type'));
                $('#addSub').modal();
            });

            if (p != '') $("a[main-type=" + p + "]").click();


            $('body').on('click', 'button[data-action]', function () {
                if ($(this).attr('data-action') == 'edit') {
                    var divForm = $('<div/>', {
                        class: "form-horizontal",
                    });
                    var divFormGroup = $('<div/>', {
                        class: "form-group",
                    });
                    var div = $('<div/>', {
                        class: "col-md-4"
                    });
                    var lable = $('<lable/>', {
                        class: "col-md-4 control-label",
                        text: "Enter Sub Name"
                    });
                    var input = $('<input/>', {
                        class: "form-control",
                        value: $(this).attr('data-edit'),
                        "data-id": $(this).attr('data-id')
                    });
                    var row = divForm.append(divFormGroup.append(lable).append(div.append(input))).clone();

                    $('#edit_delete_modal .modal-body').empty();
                    $('#edit_delete_modal .modal-body').append(row);
                    $('#edit_delete_modal').modal();
                }

            })

            $("body").on("click", '.button_title', function () {
//                var id = $(this).attr('data-id');
//                 $(".popup_div_" + id).slideToggle("slow");
                var id = $(this).attr('data-id');
                var s_id = $(this).attr('data-styleId');
                var selector = ".popup_div_" + id;

                $.ajax({
                    url: '/admin/uploads/assets/styles/show_popup',
                    data: {
                        id: id,
                        s_id: s_id,
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        $('.add_popup').html('');
                    },
                    headers: {
                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                    },
                    success: function (data) {

                        $('.add_popup').html(data.html);
                        if (!data.error) {
                            var scrollTop = $(window).scrollTop();
                            var myWidth = $(document).width();
                            if (myWidth > 765) {
                                $('body').find(selector).css({
                                    'height': (scrollTop < 182 ? $(window).height() - (182 - scrollTop) - 10 : $(window).height() - 20),
                                    "top": (scrollTop < 182 ? 0 : scrollTop - 182 + 10)
                                });
                                $('body').find(selector).slideToggle("slow");
                                $("body").css("overflow", "hidden");
                            }
                            else if (myWidth < 765) {
                                $('body').find(selector).css({
                                    'height': (scrollTop < 225 ? $(window).height() - (225 - scrollTop) - 10 : $(window).height() - 20),
                                    "top": (scrollTop < 225 ? 0 : scrollTop - 225 + 10)
                                });
                                $('body').find(selector).slideToggle("slow");
                                $("body").css("overflow", "hidden");
                            }

                            var editor = ace.edit("editor");
                            editor.setTheme("ace/theme/monokai");
                            editor.getSession().on('change', function () {
                                var getcss = editor.getSession().getValue();
                                var outgetcss = '.outputpreview .item-to-change{' + getcss + '}';
                                $('#css_editor').val(getcss);
                                $('[ data-preview="css"]').html(outgetcss);
                            });
                            var getcss = editor.getSession().getValue();
                            var outgetcss = '.outputpreview .item-to-change{' + getcss + '}';
                            $('[ data-preview="css"]').html(outgetcss);

                            $(".saveCss").on("click", function () {
                                var css = $('#css_editor').val();
                                var id = $('input[name="styleitom"]').val();
                                $.ajax({
                                    url: '/admin/uploads/assets/styles/style_preview/css',
                                    data: {
                                        css: css,
                                        id: id
                                    },
                                    dataType: 'json',
                                    headers: {
                                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                                    },
                                    success: function (data) {
                                        if (!data.error) {
                                            $('#msg').html(data).fadeIn('slow');
                                            $('#msg').html("Style updated successfully !").fadeIn('slow');
                                            $('#msg').delay(4000).fadeOut('slow');
                                        } else {
                                            alert(data.message);
                                        }
                                    },
                                    type: 'POST'
                                });
                            });

                        } else {
                            alert(data.message);
                        }
                        var css = $('#editor').text();
                        $('.item-to-change').attr('style', css)


                    },
                    type: 'POST'
                });


            });


            $('.tpl-list').on("click", '.close_icon', function () {
                var id = $(this).attr('data-id');
                $(".popup_div_" + id).slideToggle("slow");
                $("body").css("overflow", "visible");
            });

        });
    </script>
@stop
