@extends('layouts.admin')
@section('content')

{!! HTML::style('appdata/app/Modules/Cloud/Resources/assets/css/new-store.css') !!}
<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cms_module_list">
        <h3 class="menuText f-s-17">
            <span class="module_icon_main"></span> 
            <span class="module_icon_main_text"> Modules</span> 
        </h3>
        <hr>
        <ul class="list-unstyled menuList" id="components-list">
            {{--main types--}}
            <li main-type="header" >
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Header
                </a>
            </li>
            <li main-type="footer">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Footer
                </a>
            </li>
            <li main-type="content">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> General Contents
                </a>

            {{--General Content types--}}
            <li class=" m-l-30" main-type="all_section" general-type="content">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Section page
                </a>
            </li>
            <li  class=" m-l-30"  main-type="single_section" general-type="content">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Single page
                </a>
            </li>
            <li  class=" m-l-30" main-type="taxonomy" general-type="content">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Taxonomy
                </a>
            </li>
            <li  class=" m-l-30" main-type="term" general-type="content">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Term
                </a>
            </li>
            <li  class=" m-l-30" main-type="tags" general-type="content" >
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Tags
                </a>
            </li>
            <li  class=" m-l-30" main-type="custom" general-type="content">
                <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Custom conetnt
                </a>
            </li>
            <li class=" m-l-30"  main-type="email" general-type="content">
              <a href="#" class="tpl-left-items">
                    <span class="module_icon"></span> Email
                </a>
            </li>
           
        </ul> 
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
       <div class="row template-search">
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 template-search-box m-t-10 m-b-10">
                <form class="form-horizontal">
                    <div class="form-group m-b-0">
                        <label for="inputEmail3" class="col-sm-2 control-label">Sort By</label>
                        <div class="col-sm-4">
                            <select class="form-control">
                                <option>Recently Added</option>
                            </select>
                        </div>
                        <div class="col-sm-2 pull-right">
                            <a class="btn btn-default"><i class="fa fa-search f-s-15" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 template-upload-button p-l-0 p-r-0">
                <button class="btn btn-sm  pull-right m-b-10 " type="button" data-toggle="modal" data-target="#uploadfile">
                    <span class="module_upload_icon m-l-20"></span> <span class="upload_module_text">Upload</span>
                </button>
            </div>  
        </div>
        <div class=" templates-list  m-t-20 m-b-10">
            

                     <div class="row templates m-b-10">
                        {!! HTML::image('public/img/ajax-loader5.gif', 'a picture', array('class' => 'thumb img-loader hide')) !!}
                        <div class="raw tpl-list">
                            @include('packeges::templates.tpl_list')
                        </div> 
                     </div>     
                    <!-- new template layouts -->
                    <div class="row templates m-b-10">
                        <div class="col-xs-12 p-l-0 p-r-0">
                                <img src="{!! url('public/img/template-1.png')!!}" class="img-responsive"/>
                        </div>
                        <div class="col-xs-12 templates-header p-t-10 p-b-10"> 
                            <span class="pull-left templates-title f-s-15 col-xs-6 col-sm-6 col-md-6 col-lg-6 p-l-0  "><i class="fa fa-bars f-s-13 m-r-5" aria-hidden="true"></i>  Test</span>
                            <div class="pull-right templates-buttons col-xs-6 col-sm-6 col-md-6 col-lg-6 p-r-0 text-right ">
                                <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i> 
                                Author name, 20/07/2016 
                                <a href="#" class="addons-settings  m-l-10 m-r-10"><i class="fa fa-eye f-s-14"></i> </a>
                                <a href="#" class="addons-deactivate  m-r-10"><i class="fa fa-pencil f-s-14"></i> </a>
                                <a href="#" class="addons-delete"><i class="fa fa-trash-o f-s-14 "></i> </a>

                            </div>
                        </div>
                    </div>

                    <div class="row templates m-b-10">
                        <div class="col-xs-12 p-l-0 p-r-0">
                                <img src="{!! url('public/img/template-2.png')!!}" class="img-responsive"/>
                        </div>
                        <div class="col-xs-12 templates-header p-t-10 p-b-10"> 
                            <span class="pull-left templates-title f-s-15 col-xs-6 col-sm-6 col-md-6 col-lg-6 p-l-0  "><i class="fa fa-bars f-s-13 m-r-5" aria-hidden="true"></i>  Test</span>
                            <div class="pull-right templates-buttons col-xs-6 col-sm-6 col-md-6 col-lg-6 p-r-0 text-right ">
                                <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i> 
                                Author name, 20/07/2016 
                                <a href="#" class="addons-settings  m-l-10 m-r-10"><i class="fa fa-eye f-s-14"></i> </a>
                                <a href="#" class="addons-deactivate  m-r-10"><i class="fa fa-pencil f-s-14"></i> </a>
                                <a href="#" class="addons-delete"><i class="fa fa-trash-o f-s-14 "></i> </a>

                            </div>
                        </div>
                    </div>

                    
                    <div class="row  m-b-10">
                        <div class="col-xs-4">
                            <div class="row templates m-b-10">
                                <div class="col-xs-12 p-l-0 p-r-0">
                                        <img src="{!! url('public/img/template-3.png')!!}" class="img-responsive"/>
                                        <div class="tempalte_icon">
                                            <div><a href="#" class="addons-settings  m-r-10"><i class="fa fa-eye f-s-14"></i> </a></div>
                                            <div><a href="#" class="addons-deactivate  m-r-10"><i class="fa fa-pencil f-s-14"></i> </a></div>
                                            <div><a href="#" class="addons-delete"><i class="fa fa-trash-o f-s-14 "></i> </a></div>
                                        </div>
                                </div>
                                <div class="col-xs-12 templates-header p-t-10 p-b-10"> 
                                    <span class="col-xs-12 templates-title f-s-15 text-center"><i class="fa fa-bars f-s-13 m-r-5" aria-hidden="true"></i>  Test</span>
                                    <div class="col-xs-12 templates-buttons text-center ">
                                        <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i> 
                                        Author name, 20/07/2016 

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="row templates m-b-10">
                                <div class="col-xs-12 p-l-0 p-r-0">
                                        <img src="{!! url('public/img/template-3.png')!!}" class="img-responsive"/>
                                        <div class="tempalte_icon">
                                            <div><a href="#" class="addons-settings  m-r-10"><i class="fa fa-eye f-s-14"></i> </a></div>
                                            <div><a href="#" class="addons-deactivate  m-r-10"><i class="fa fa-pencil f-s-14"></i> </a></div>
                                            <div><a href="#" class="addons-delete"><i class="fa fa-trash-o f-s-14 "></i> </a></div>
                                        </div>
                                </div>
                                <div class="col-xs-12 templates-header p-t-10 p-b-10"> 
                                    <span class="col-xs-12 templates-title f-s-15 text-center"><i class="fa fa-bars f-s-13 m-r-5" aria-hidden="true"></i>  Test</span>
                                    <div class="col-xs-12 templates-buttons text-center ">
                                        <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i> 
                                        Author name, 20/07/2016 

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="row templates m-b-10">
                                <div class="col-xs-12 p-l-0 p-r-0">
                                        <img src="{!! url('public/img/template-3.png')!!}" class="img-responsive"/>
                                        <div class="tempalte_icon">
                                            <div><a href="#" class="addons-settings  m-r-10"><i class="fa fa-eye f-s-14"></i> </a></div>
                                            <div><a href="#" class="addons-deactivate  m-r-10"><i class="fa fa-pencil f-s-14"></i> </a></div>
                                            <div><a href="#" class="addons-delete"><i class="fa fa-trash-o f-s-14 "></i> </a></div>
                                        </div>
                                </div>
                                <div class="col-xs-12 templates-header p-t-10 p-b-10"> 
                                    <span class="col-xs-12 templates-title f-s-15 text-center"><i class="fa fa-bars f-s-13 m-r-5" aria-hidden="true"></i>  Test</span>
                                    <div class="col-xs-12 templates-buttons text-center ">
                                        <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i> 
                                        Author name, 20/07/2016 

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>            
                     
            
        </div>

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
                {!! Form::open(['url'=>'/admin/templates/upload-template','class'=>'dropzone', 'id'=>'my-awesome-dropzone']) !!}

                {!! Form::close() !!} </div>
        </div>
    </div>
</div>
@stop
@section('CSS')
    <style>
        .module-item {
            text-align: center;
            line-height: 40px;
            font-size: 20px;
            border-radius: 15px;
            background-color: #00abf5;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .active {
            background-color: #fed63b;
        }

        .module-preview {
            min-height: 200px;
            border: 1px solid;
            border-radius: 15px;
            background-color: #00abf5;
        }

        .module-data {
            border: 1px solid black;
            min-height: 150px;
            margin-top: 23px;
            margin-right: 2%;
            border-radius: 10px;
            text-align: center;
            background: #fed63b;
            color: white;
        }

        .m-title {
            line-height: 150px;
            font-size: 20px;
            font-weight: bold;
        }

        .m-desc {
            font-size: 17px;
            min-height: 110px;
        }
        .m-desc-bottom{
            border: 1px solid black;
            border-radius: 10px;
            min-height: 25px;
        }
        .addon-item{
            border-bottom: 3px solid black;
        }
        .addon-name{
            min-height: 100px;
            line-height: 90px;
            font-size: 20px;
            font-weight: bold;
            background-color: #bbd0ca;
            padding: 30px;
            border-radius: 15px;
        }
        .img-loader{
            width: 70px;
            height: 70px;
            position: absolute;
            top: 50px;
            left: 40%;
        }
    </style>
@stop
@section('JS')
    {!! HTML::script('public/libs/dropzone/js/dropzone.js') !!}
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function() {
                this.on("success", function(file) {
                    location.reload();
                });
            }
        };

        $(document).ready(function(){

            $("body").on("click",".tpl-left-items", function(){
                var main_type = $(this).attr('main-type');
                var general_type = $(this).attr('general-type');

                $('.tpl-left-items').removeClass('active');
                if(general_type){
                    $('*[main-type="content"]').addClass('active');
                    $('*[main-type="'+ main_type +'"]').addClass('active');
                }else{
                    $('*[main-type="'+ main_type +'"]').addClass('active');
                }

                $.ajax({
                    url: '/admin/templates/templates-with-type',
                    data: {
                        main_type: main_type,
                        type: general_type,
                        _token: $('#token').val()
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('.tpl-list').html('');
                        $('.img-loader').removeClass('hide');
                    },
                    success: function (data) {
                        $('.img-loader').addClass('hide');
                        if(! data.error) {

                            $('.tpl-list').html(data.html);
                        }
                    },
                    type: 'POST'
                });
            });

            $('body').on('click','.del-tpl',function(){
                var slug = $(this).attr('slug');
                $.ajax({
                    url: '/admin/templates/delete',
                    data: {
                        slug: slug,
                        _token: $('#token').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        location.reload();
                    },
                    type: 'POST'
                });
            });
        });
    </script>
@stop

