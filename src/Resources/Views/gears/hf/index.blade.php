@extends('layouts.mTabs',['index'=>'frontend_gears'])
<!-- Nav tabs -->
@section('tab')
    {!! HTML::style('app/Modules/Uploads/Resources/assets/css/new-store.css') !!}
    <div class="row">
        <button class="btn btn-info btn-sm pull-right btnUploadWidgets m-r-15 m-b-15" type="button" data-toggle="modal"
                data-target="#uploadfile">
            <i class="fa fa-cloud-upload module_upload_icon"></i> <span class="upload_module_text">Upload</span>
        </button>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cms_module_list">
            <h3 class="menuText f-s-17 hide">
                <span class="module_icon_main"></span>
                <span class="module_icon_main_text">Units</span>
            </h3>
            <div class=" menuBox">
                <div class="selectCat">
                    {!! Form::select('type',['header'=>'Headers','footer'=>'Footers'],$type,['class' => 'selectpicker select-type','data-style' => 'selectCatMenu',' data-width' => '100%']) !!}
                </div>
            </div>
            <hr>
            <ul class="list-unstyled menuList" id="components-list">
                @if(count($templates))
                    @foreach($templates as $template)
                        @if($template)
                            @if($currentTemplate->slug == $template->slug)
                                <li class="active">
                            @else
                                <li class="">
                            @endif
                        @else
                            @if($templates[0]->slug == $template->slug)
                                <li class="active">
                            @else
                                <li class="">
                                    @endif
                                    @endif
                                    <a href="?p={!! $template->slug !!}{!! ($type)? "&type=".$type : "" !!}"  rel="unit" data-slug="{{ $template->slug }}" class="tpl-left-items">
                                        <span class="module_icon"></span> {{ $template->title }}
                                    </a>
                                </li>
                                @endforeach
                                @else
                                    No Templates
                                @endif
            </ul>
        </div>



        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <div class="row">
                <div class="col-xs-12 col-sm-12 unit-box">
                    @include('uploads::gears.units._partials.hf_box')
                </div>
            </div>
            <div class="row template-search">
                @if($currentTemplate)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 template-search-box m-t-10 m-b-10">
                    <form class="form-horizontal">
                        <div class="form-group m-b-0  ">
                            <label for="inputEmail3" class="control-label text-left"><i class="fa fa-sort-amount-desc"></i> Sort By</label>
                            <select class="selectpicker" data-style="selectCatMenu" data-width="50%">
                                <option>Recently Added</option>
                            </select>

                        </div>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 p-l-0 p-r-0">
                    <div class="template-upload-button clearfix">
                        <div class="rightButtons">
                            <div class="btn-group listType">
                                <a href="#" class="btn btnListView"><i class="fa fa fa-th-list"></i></a>
                                <a href="#" class="btn btnGridView active"><i class="fa fa-th-large"></i></a>
                            </div>
                            <a class="btn btn-default searchBtn"><i class="fa fa-search " aria-hidden="true"></i></a>
                        </div>

                        <ul class="editIcons list-unstyled ">
                            <li>
                                <a class="btn trashBtn"><i class="fa fa-trash-o"></i></a>
                            </li>
                            <li><a href="#" class="btn copyBtn"><i class="fa fa-clone"></i></a></li>
                            <li><a href="#" class="btn editBtn"><i class="fa fa-pencil"></i></a></li>
                        </ul>

                        <a href="{!! url('/admin/uploads/gears/h-f/settings',$currentTemplate->slug) !!}" id="viewType" class="btn btn-sm pull-right btnUploadWidgets">
                                <i class="fa fa-plus module_upload_icon" aria-hidden="true"></i>
                                <span  class="upload_module_text">Add Variation </span>
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="templates-list  m-t-20 m-b-10">
                <div class="row m-b-10">
                    {!! HTML::image('resources/assets/images/ajax-loader5.gif', 'a picture', array('class' => 'thumb img-loader hide')) !!}
                    <div class="raw tpl-list">
                        @if($currentTemplate)
                            @include('uploads::gears.hf._partials.hf_variations')
                        @endif
                    </div>
                </div>
            </div>

            <div class="loadding"><em class="loadImg"></em></div>
            <nav aria-label="" class="text-center">
                <ul class="pagination paginationStyle">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active">
                        <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
            <div class="text-center"><button type="button" class="btn btn-lg btn-primary btnLoadmore"><em class="loadImg"></em> Load more</button></div>

        </div>
    </div>

    <div class="modal fade" id="uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url'=>'/admin/uploads/gears/h-f/upload','class'=>'dropzone', 'id'=>'my-awesome-dropzone']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @include('resources::assests.deleteModal',['title'=>'Delete Widget'])
    </div>
    @include('_partials.delete_modal')
@stop
@section('CSS')
    {!! HTML::style('/resources/assets/js/bootstrap-select/css/bootstrap-select.min.css') !!}
    <style>
        .child-tpl {
            width: 95% !important;
        }

        .img-loader {
            width: 70px;
            height: 70px;
            position: absolute;
            top: 50px;
            left: 40%;
        }

    </style>
@stop
@section('JS')
    {!! HTML::script('resources/assets/js/dropzone/js/dropzone.js') !!}
    {!! HTML::script('/resources/assets/js/bootstrap-select/js/bootstrap-select.min.js') !!}
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function() {
                this.on("success", function(file) {
                    location.reload();

                });
            }
        };

        $(document).ready(function () {

            $('body').on("change",".select-type",function(){
                var val = $(this).val();
                var url = window.location.pathname +"?type="+val;

                window.location = url;
            });

            $('.rightButtons a').click(function(e){
                e.preventDefault();
                $(this).addClass('active').siblings().removeClass('active');
            });

            $('.btnListView').click(function(e){
                e.preventDefault();
                $('#viewType').addClass('listView');
            });

            $('.btnGridView').click(function(e){
                e.preventDefault();
                $('#viewType').removeClass('listView');
            });


            $('.selectpicker').selectpicker();

            var p="{!! $_GET['p'] or null !!}";
//            $('body').on('click','.del-tpl',function(){
//                var slug = $(this).attr('slug');
//                $.ajax({
//                    url: '/admin/resources/widgets/delete',
//                    data: {
//                        slug: slug
//                    },headers: {
//                        'X-CSRF-TOKEN':$("input[name='_token']").val()
//                    },
//                    dataType: 'json',
//                    success: function (data) {
//                        // location.reload();
//
//                    },
//                    type: 'POST'
//                });
//            });


//            $('.tab-content').on('click','.delete_layout', function () {
//                console.log(1);
//                var key = $(this).attr('data-key');
//                $('.delete_modal .modal-footer a')
//                        .attr('href', '#')
//                        .attr('slug', $(this).attr('data-key'))
//                        .addClass('del-tpl');
//                $('.modal-body').html("<p>atre you sure you want to delete Widget <b>" + $(this).attr('data-title') + '<b> ?');
//                $('.delete_modal').modal();
//            });

            //Change browser url without page reloading with ajax request

//            $("a[rel='unit']").click(function(e){
//                e.preventDefault();
//                var main_type = $(this).attr('main-type');
//                var pageurl = $(this).attr('href');
//                $('.tpl-left-items').parent().removeClass('active');
//                var general_type = $(this).attr('type');
//
//                if(general_type){
//                    $('*[main-type="'+general_type+'"]').parent().addClass('active');
//                    $('*[main-type="'+ main_type +'"][type="'+general_type+'"]').parent().addClass('active');
//                }else{
//                    $('*[main-type="'+ main_type +'"]').parent().addClass('active');
//                }
//
//                $.ajax({
//                    url: '/admin/resources/widgets/widget-with-type',
//                    data: {
//                        main_type: main_type,
//                        url:pageurl+'?rel=tab',
//                        type: general_type
//                    },headers: {
//                        'X-CSRF-TOKEN':$("input[name='_token']").val()
//                    },
//                    dataType: 'json',
//                    beforeSend: function () {
//                        $('.tpl-list').html('');
//                        $('.img-loader').removeClass('hide');
//                    },
//                    success: function (data) {
//
//                        $('.img-loader').addClass('hide');
//                        if (!data.error) {
//                            $('.tpl-list').html(data.html);
//                        }
//                    },
//                    type: 'POST'
//                });
//                if(pageurl!=window.location){
//                    window.history.pushState({path:pageurl},'',pageurl);
//                }
//                return false;
//            });

            $("a[main-type="+p+"]").click();



        });

    </script>
@stop