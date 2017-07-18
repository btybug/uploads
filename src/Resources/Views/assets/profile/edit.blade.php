@extends('layouts.admin')
@section('content')
    <div class="row list_222">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cms_module_list module_list_1">
            <h3 class="menuText f-s-17">
                <span class="module_icon_main"></span>

                <span class="module_icon_main_text">
                    {!! Form::select('styles',\Sahakavatar\Resources\Models\Styles::$stylesTypes,$type,['class' => 'form-control select-type']) !!}
                </span>
            </h3>
            <hr>
            <ul class="list-unstyled menuList" id="components-list">
                {{--main types--}}
                @if(count($styles))
                    @foreach($styles as $style)
                        @if($style->slug == $p)
                            <li class="active_class">
                        @else
                            <li>
                        @endif
                            <a href="?type={{ $type }}&p={{ $style->slug }}" profile-id="{{ $profile->id }}" main-type="{{ $style->slug }}" rel="tab" class="tpl-left-items">
                                <span class="module_icon"></span> {{ $style->name }}
                                <a data-type="{{ $style->slug }}" class="add-class-modal pull-right gettype"></a>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li>No styles</li>
                @endif
            </ul>
        </div>
        {!! HTML::image('resources/assets/images/ajax-loader5.gif', 'a picture', array('class' => 'thumb img-loader hide')) !!}
        <div class="tpl-list">
            <button class="btn btn-primary select-">
                Select Item
            </button>
        </div>
    </div>

    @include('resources::assests.deleteModal',['title'=>'Delete Style'])
@stop
@section('CSS')
    {!! HTML::style('app/Modules/Resources/Resources/assets/css/new-store.css') !!}
    {!! HTML::style('app/Modules/Resources/Resources/assets/css/style_cube.css') !!}
@stop
@section('JS')
    {!! HTML::script('resources/assets/js/dropzone/js/dropzone.js') !!}
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function () {
                this.on("success", function (file) {
//                    location.reload();
                });
            }
        };


        $(document).ready(function () {
            $("body").on('change','.select-type',function(){
                var value = $(this).val();
                window.location.href = window.location.pathname + "?type=" + value;
            });


            var p = "{!! $_GET['p'] or null !!}";
            var scrollTop = $(window).scrollTop();
            $(window).resize(function () {
                $('body').find('.popup_div').css({
                    'height': $(window).height() - 227,
                    "top": scrollTop
                });
            });

            $("body").on("change", ".sort-items", function () {
                var val = $(this).val();
                var main_type = $(this).attr('data-type');
                var sub = $(this).attr('data-sub');
                var profile_id = $(this).attr('profile_id');

                if (!sub) {
                    sub = false;
                }

                $.ajax({
                    url: '/admin/uploads/assets/profiles/render-styles',
                    data: {
                        main_type: main_type,
                        sub: sub,
                        sort: val,
                        sorting: true,
                        profile_id: profile_id
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
                            $('#sub_item_upl').val(sub);
                            $('.tpl-list').html(data.html);
                        } else {
                            alert(data.message);
                        }
                    },
                    type: 'POST'
                });
            });

            $('.list-unstyled').on('click', '.tpl-left-items', function(e) {
                e.preventDefault();
                var main_type = $(this).attr('main-type');
                var sub = $(this).attr('sub');
                var pageurl = $(this).attr('href');
                var profile_id = $(this).attr('profile-id');

                if (!sub) {
                    sub = false;
                }

                $('.tpl-left-items').parent().removeClass('active_class');

                $('*[main-type="' + main_type + '"]').parent().addClass('active_class');

                $.ajax({
                    url: '/admin/uploads/assets/profiles/render-styles',
                    data: {
                        main_type: main_type,
                        url:pageurl+'?rel=tab',
                        sub: sub,
                        profile_id:profile_id
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
                if(pageurl!=window.location){
                    window.history.pushState({path:pageurl},'',pageurl);
                }
                return false;
            });

//            $("a[main-type=" + p + "]").click();

            $('.tpl-list').on("click", '.close_icon', function () {
                var id = $(this).attr('data-id');
                $(".popup_div_" + id).slideToggle("slow");
                $("body").css("overflow", "visible");
            });

        });
    </script>
@stop
