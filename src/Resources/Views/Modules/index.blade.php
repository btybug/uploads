@extends('btybug::layouts.mTabs',['index'=>'upload_modules'])
@section('tab')
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cms_module_list">
            <h3 class="menuText f-s-17">
                <span class="module_icon_main"></span>
            </h3>
            <hr>
            <ul class="list-unstyled menuList" id="components-list">
                @foreach($plugins as $plugin)
                    <li class=" @if($selected->name==$plugin['name']) active @endif ">
                        <a href="{!! route('modules_index',['p'=>$plugin['name']]) !!}"> <span
                                    class="module_icon"></span> {!! $plugin['name'] !!}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-6">
                            <a class="btn btn-sm  m-b-10 upload_module" href="{!! route('composer_index') !!}">
                                <i class="fa fa-steam-square" aria-hidden="true"></i>
                                <span class="upload_module_text">Installer</span>

                            </a>
                        </div>
                        <div class="col-xs-6">
                        </div>
                    </div>
                    @if($selected)
                        <div class="row module_detail">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <img src="{!! url('images/module.jpg') !!}" alt=""
                                     class="img-rounded img-responsive"/>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="module-title"> name:{!! $selected->name or null !!}</div>
                                <div class="module-title"> version:{!! $selected->version or null !!}</div>
                                <div class="module-desc">
                                    {!! $selected->description or null !!}
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 pull-right text-right m-t-20">
                                {{--@if(isset($module->have_setting) and $module->have_setting==1)--}}
                                <a href="{!! url('#/setting','id') !!}"
                                   class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left settings"><i
                                            class="fa fa-pencil f-s-14 m-r-10"></i> Settings</a>
                                {{--@endif--}}
                                <a href="{!! url('admin/uploads/modules/'.$selected->name.'/explore') !!}"
                                   class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left"><i
                                            class="fa fa-cogs f-s-14 m-r-10"></i>Explore</a>
                            </div>
                        </div>

                        <div class="row module_detail_link">
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 m-t-10 m-b-10">
                                <a href="{!! 'Author' !!}"
                                   class="module_detail_author_link">{!!'Site Url' !!}</a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 module_author_detail m-t-10 m-b-10">
                                <div class="pull-left">
                                    <i class="fa fa-bars f-s-15" aria-hidden="true"></i>
                                    Version {!! 'version' !!}
                                </div>
                                <div class="pull-right">
                                    <i class="fa fa-user f-s-15" aria-hidden="true"></i>
                                    {!! 'Author'!!}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
            @if($selected)
                <div class="row">
                    <div class="m-t-15 col-xs-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#installed_add_ons"
                                                                      aria-controls="installed_add_ons" role="tab"
                                                                      data-toggle="tab">Installed Plugins</a></li>
                            <li role="presentation"><a href="#related_add_ons" aria-controls="related_add_ons"
                                                       role="tab"
                                                       data-toggle="tab">Related Add-Ons</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content m-t-15">
                            <div role="tabpanel" class="tab-pane active" id="installed_add_ons">
                                {{--@include("console::modules.plugins")--}}
                            </div>
                            <div role="tabpanel" class="tab-pane" id="related_add_ons">
                                <table class="table addon-list table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Version</th>
                                        <th class="text-center">Authors</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($selected->children() as $k=> $child)

                                        <tr>
                                            <td>{!! $k+1 !!}</td>
                                            <td>{!! $child->name !!}</td>
                                            <td>{!! $child->version !!}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($child->authors as $author)
                                                        <tr>
                                                            <td>{!! $author['name'] !!}</td>
                                                            <td>{!! $author['email'] !!}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <p></p>

                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>

    </div>

@stop
@section('CSS')
    {!! HTML::style('public/css/new-store.css') !!}
@stop
@section('JS')
    <script>
        $(function () {
            $('body').on('click', '.enb-disb', function () {
                var namespace = $(this).attr('namespace');
                var action = $(this).attr('data-action');
                $.ajax({
                    url: '{!! route('on_off') !!}',
                    data: {
                        namespace: namespace,
                        action: action,
                        _token: $('input[name=_token]').val()
                    },
                    success: function (data) {
                        location.reload();
                    },
                    type: 'POST'
                });
            });
        })
    </script>
@stop