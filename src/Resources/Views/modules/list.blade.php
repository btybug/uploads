@extends('cms::layouts.admin')
@section('content')
    {!! HTML::style('app/Modules/Modules/css/new-store.css') !!}
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cms_module_list">
            <h3 class="menuText f-s-17"><span class="module_icon_main"></span> <span class="module_icon_main_text">Extra Modules</span>
            </h3>
            <hr>
            <ul class="list-unstyled menuList" id="components-list">
                @if(count($extras))
                    @foreach($extras as $m)
                        <li class=" @if($m->slug == $module->slug) active @endif">
                            <a href="{!! url('admin/uploads/modules?m='. $m->slug) !!}">
                                <span class="module_icon"></span>
                                {!! $m->name !!}
                            </a>
                        </li>
                    @endforeach
                @else
                    <li>No Modules</li>
                @endif
            </ul>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-6">
                            <a class="btn btn-sm  m-b-10 upload_module" href="/admin/uploads/optimisation">
                                <i class="fa fa-steam-square" aria-hidden="true"></i>
                                <span class="upload_module_text">Modules Optimize</span>

                            </a>
                        </div>

                        <div class="col-xs-6">
                            <button class="btn btn-sm  pull-right m-b-10 upload_module" type="button"
                                    data-toggle="modal" data-target="#uploadfile">
                                <span class="module_upload_icon"></span> <span class="upload_module_text">Upload</span>
                            </button>
                        </div>
                    </div>
                    @if($module)
                        <div class="row module_detail">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <img src="{!! url('/images/module.jpg') !!}" alt="" class="img-rounded img-responsive"/>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="module-title">{!! $module->name !!}</div>
                                <div class="module-desc">
                                    {!! $module->description !!}
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 pull-right text-right m-t-20">
                                @if(isset($module->have_setting) and $module->have_setting==1)
                                    <a href="{!! url('admin/plugins/setting',$module->id) !!}"
                                       class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left settings"><i
                                                class="fa fa-pencil f-s-14 m-r-10"></i> Settings</a>
                                @endif
                            </div>
                        </div>

                        <div class="row module_detail_link">
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 m-t-10 m-b-10">
                                <a href="{!! $module->author_site or null !!}"
                                   class="module_detail_author_link">{!! $module->author_site or null !!}</a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 module_author_detail m-t-10 m-b-10">
                                <div class="pull-left">
                                    <i class="fa fa-bars f-s-15" aria-hidden="true"></i>
                                    Version {!! $module->version !!}
                                </div>
                                <div class="pull-right">
                                    <i class="fa fa-user f-s-15" aria-hidden="true"></i>
                                    {!! $module->author or null!!}
                                    , {!! BBgetDateFormat(isset($module->created_at)?$module->created_at :null) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
            <div class="col-md-7">
                <p>
                    @if($module->enabled)
                        <a href="#" namespace="{!! $module->slug !!}" data-action="disable"
                           class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left enb-disb deactivate"><i
                                    class="fa fa-power-off f-s-14 m-r-10"></i> Deactivate</a>
                    @else
                        <a href="#" namespace="{!! $module->slug !!}" data-action="core-enable"
                           style="background: #7fff00;color: #000000"
                           class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left  enb-disb"><i
                                    class="fa fa-plug f-s-14 m-r-10"></i>Activate</a>
                    @endif
                </p>
            </div>
            @if($module)
                <div class="row">
                    <div class="m-t-15 col-xs-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#installed_add_ons"
                                                                      aria-controls="installed_add_ons" role="tab"
                                                                      data-toggle="tab">Installed Plugins</a></li>
                            <li role="presentation"><a href="#related_add_ons" aria-controls="related_add_ons"
                                                       role="tab" data-toggle="tab">Related Add-Ons</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content m-t-15">
                            <div role="tabpanel" class="tab-pane active" id="installed_add_ons">
                                @include("uploads::modules.plugins")
                            </div>
                            <div role="tabpanel" class="tab-pane" id="related_add_ons">...cc</div>
                        </div>

                    </div>
                </div>
            @endif
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
                    {!! Form::open(['url'=>'/admin/uploads/modules/upload','class'=>'dropzone', 'id'=>'my-awesome-dropzone']) !!}

                    {!! Form::close() !!} </div>
            </div>
        </div>
    </div>
@stop
@section('CSS')
    {!! HTML::style('/js/bootstrap-switch/css/bootstrap-switch.min.css') !!}
    {!! HTML::style('app/Modules/Modules/css/store.css') !!}
@stop
@section('JS')
    {!! HTML::script('/js/dropzone/js/dropzone.js') !!}
    <script>

        Dropzone.options.myAwesomeDropzone = {

            init: function () {

                this.on("error", function (file, progress) {
                    alert('An Error occurred in Plugin, It can\'t be installed !');
//                    location.reload();
                });

                this.on("success", function (file, progress) {
                    if (progress.error) {
                        alert(progress.message);
                    }
//                    location.reload();

                });

            }

        };

        $(document).ready(function () {
//            $('body').on('click', '.del-module', function () {
//                var namespace = $(this).attr('namespace');
//                $.ajax({
//                    url: '/admin/modules/delete',
//                    data: {
//                        namespace: namespace,
//                        _token: $('input[name=_token]').val()
//                    },
//                    dataType: 'json',
//                    success: function (data) {
//                        console.log(data);
//                        if (!data.error) {
//                            location.reload();
//                        } else {
//                            $('#message-modal .modal-body').html(data.message);
//                            $('#message-modal').modal();
//                        }
//                    },
//                    type: 'POST'
//                });
//            });
            $('body').on('click', '.enb-disb', function () {
                var namespace = $(this).attr('namespace');
                var action = $(this).attr('data-action');
                $.ajax({
                    url: '/admin/modules/' + action,
                    data: {
                        namespace: namespace,
                        _token: $('input[name=_token]').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (!data.error) {
                            location.reload();
                        } else {
                            $('#message-modal .modal-body').html(data.message);
                            $('#message-modal').modal();
                        }
                    },
                    type: 'POST'
                });
            });
        });

    </script>
@stop

