@extends('btybug::layouts.admin')

@section('content')
    <div class="row list_222">
        <div id="msg" style="background-color: #DFF2BF; color: #4F8A10; margin: 10px 0px; text-align: center; "></div>
        {!! HTML::image('/images/ajax-loader5.gif', 'a picture', array('class' => 'thumb img-loader hide')) !!}
        <div class="tpl-list">
            @include('uploads::assets.profile._partials.list_cube')
        </div>
    </div>
    @include('resources::assests.deleteModal',['title'=>'Delete Style'])
@stop
@section('CSS')
    {!! HTML::style('app/Modules/Resources/css/new-store.css') !!}
    {!! HTML::style('app/Modules/Resources/css/style_cube.css') !!}
    <style>
        #editor {
            width: 100%;
            height: 100px;
            margin-bottom: 20px;
        }
    </style>
    <style data-preview="css"></style>
@stop
@section('JS')
    {!! HTML::script('/js/dropzone/js/dropzone.js') !!}
    {!! HTML::script("/js/code_editor/edit_area_full.js") !!}
    {!! HTML::script("https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/ace.js") !!}
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function () {
                this.on("success", function (file) {
                    location.reload();
                });
            }
        };


        $(document).ready(function () {

            $("body").on('click', '.make-default-style', function () {
                var style_id = $(this).data("style");
                var profile_id = $(this).data("profile");
                var default_style = $(".default_style").val();

                $.ajax({
                    url: '/admin/uploads/assets/profiles/edit/default',
                    data: {
                        style_id: style_id,
                        profile_id: profile_id,
                        default_style: default_style
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                            window.location.reload();
                        }
                    },
                    type: 'POST'
                });

            });


            var scrollTop = $(window).scrollTop();
            $(window).resize(function () {
//                $('body').find('.popup_div').css({
//                    'height':$( window ).height()-227,
//                    "top":scrollTop
//                });
                var scrollTop = $(window).scrollTop();
                $('body').find(".popup_div_").css({
                    'height': (scrollTop < 182 ? $(window).height() - (182 - scrollTop) - 10 : $(window).height() - 20),
                    "top": (scrollTop < 182 ? 0 : scrollTop - 182 + 10)
                });
            });

            $("body").on("change", ".sort-items", function () {
                var ID = "{{ $style->id }}";
                var val = $(this).val();
                var main_type = $(this).attr('data-type');
                var sub = $(this).attr('data-sub');
                var profile_id = "{{ $profile->id }}";

                if (!sub) {
                    sub = false;
                }

                $.ajax({
                    url: '/admin/uploads/assets/styles/style_preview/' + ID,
                    data: {
                        main_type: main_type,
                        sub: sub,
                        sort: val,
                        sorting: true
                    },
                    dataType: 'json',
                    beforeSend: function () {
//                        $('.tpl-list').html('');
                        $('.img-loader').removeClass('hide');

                    },
                    headers: {
                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                    },
                    success: function (data) {
                        $('.img-loader').addClass('hide');
                        if (!data.error) {
                            $('.tpl-list').html(data.html);
                        } else {
                            alert(data.message);
                        }
                    },
                    type: 'POST'
                });
            });

            $("body").on("click", ".add-class-modal", function () {
                $('#class-type').val($(this).attr('data-type'));
                $('#addSub').modal();
            });

            $("body").on("click", ".tpl-left-items", function () {
                var main_type = $(this).attr('main-type');
                var sub = $(this).attr('sub');

                if (!sub) {
                    sub = false;
                }

                $('.tpl-left-items').parent().removeClass('active_class');

                $('*[main-type="' + main_type + '"]').parent().addClass('active_class');

                $.ajax({
                    url: '/admin/uploads/assets/styles/render-styles',
                    data: {
                        main_type: main_type,
                        sub: sub
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        $('.tpl-list').html('');
                        $('.img-loader').removeClass('hide');
                    },
                    headers: {
                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                    },
                    success: function (data) {
                        $('.img-loader').addClass('hide');
                        if (!data.error) {
                            if (sub) {
                                $('#sub_item_upl').val(sub);
                            }

                            $('.tpl-list').html(data.html);
                        } else {
                            alert(data.message);
                        }
                    },
                    type: 'POST'
                });
            });

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

            $('.tpl-list').on("click", '.button_title', function () {
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
