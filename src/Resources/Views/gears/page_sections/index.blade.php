@extends('btybug::layouts.mTabs',['index'=>'upload_layouts'])
@section('tab')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-info btn-sm pull-right btnUploadWidgets m-r-15 m-b-15" type="button"
                    data-toggle="modal"
                    data-target="#uploadfile">
                <i class="fa fa-cloud-upload module_upload_icon"></i> <span class="upload_module_text">Upload</span>
            </button>
        </div>
    </div>
    <div class="col-md-4 ">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline" name="input" method="get" action="#" id="filter-tables">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4 class="muted">Date</h4>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio"> All Dates</label>
                                    </div>
                                    <p/>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio"> Especific Date</label>
                                    </div>
                                    <p></p>
                                    <div class="input-daterange input-group" id="datepicker">
                                        <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-calendar"></i></span>
                                        <input type="text" class="input-sm form-control" name="start_date"/>
                                        <span class="input-group-addon"> - </span>
                                        <input type="text" class="input-sm form-control" name="end_date"/>
                                        <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4 class="muted">Persons</h4>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio"> All Persons</label>
                                    </div>
                                    <p/>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio"> Especific Persons</label>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <select class="input-sm form-control">
                                                <option>Person Name 1</option>
                                                <option>Person Name 2</option>
                                                <option>Person Name 3</option>
                                                <option>Person Name 4</option>
                                                <option>Person Name 5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4 class="muted">Files Types</h4>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio"> All Files</label>
                                    </div>
                                    <p/>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio"> Especific Files</label>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <select class="input-sm form-control">
                                                <option>File Name 1</option>
                                                <option>File Name 2</option>
                                                <option>File Name 3</option>
                                                <option>File Name 4</option>
                                                <option>File Name 5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8 ">
        <div class="templates-list">
            <div class="row">
                <div class="raw tpl-list">
                    @include('uploads::gears.page_sections._partials.page_section_variations')
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
        <div class="text-center">
            <button type="button" class="btn btn-lg btn-primary btnLoadmore"><em class="loadImg"></em> Load more
            </button>
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
                    {!! Form::open(['url'=>'/admin/uploads/layouts/upload','class'=>'dropzone', 'id'=>'my-awesome-dropzone']) !!}
                    {!! Form::hidden('data_type','files',['id'=>"dropzone_hiiden_data"]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('btybug::_partials.delete_modal')
@stop
@section('CSS')
    {!! HTML::style('public/css/new-store.css') !!}
    {!! HTML::style('public/js/bootstrap-select/css/bootstrap-select.min.css') !!}
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
    {!! HTML::script('public/js/dropzone/js/dropzone.js') !!}
    {!! HTML::script('public/js/bootstrap-select/js/bootstrap-select.min.js') !!}
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function () {
                this.on("success", function (file) {
                        location.reload();
                });
            }
        };

        function confirm_delete(data) {
            var r = confirm("Are you sure !!!");
            if (r == true) {
                var slug = $(data).data('slug');
                $.ajax({
                    url: '/admin/uploads/layouts/delete',
                    data: {
                        slug: slug
                    }, headers: {
                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        location.reload();
                    },
                    type: 'POST'
                });
            }
        }

        $(document).ready(function () {

            $('body').on("change", ".select-type", function () {
                var val = $(this).val();
                var url = window.location.pathname + "?type=" + val;

                window.location = url;
            });

            $('.rightButtons a').click(function (e) {
                e.preventDefault();
                $(this).addClass('active').siblings().removeClass('active');
            });

            $('.btnListView').click(function (e) {
                e.preventDefault();
                $('#viewType').addClass('listView');
            });

            $('.btnGridView').click(function (e) {
                e.preventDefault();
                $('#viewType').removeClass('listView');
            });


            $('.selectpicker').selectpicker();

            var p = "{!! $_GET['p'] or null !!}";

            if (p.length) {
                $("a[main-type=" + p + "]").click();
            }

        });

    </script>
@stop
