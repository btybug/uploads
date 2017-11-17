@extends('btybug::layouts.admin')
@section('content')
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
        <div class="row">
            <div class="col-xs-12 col-sm-12 unit-box">
                {{--@include('uploads::gears.units._partials.unit_box')--}}
            </div>
        </div>


        <div class="templates-list">
            <div class="row">
                <div class="raw tpl-list">
                    @if($units)
                        @foreach($units as $ui)
                            <div class="col-md-4">
                                <div class="row templates m-b-10 ">
                                    <div class=" topRow p-l-0 p-r-0">
                                        <img src="{!! url('/images/template-3.png')!!}" class="img-responsive"/>
                                        <div class="tempalte_icon">

                                            <div>
                                                <a href="{!! url('/admin/uploads/units/units-variations', $ui->slug) !!}"
                                                   class="m-r-10"><i class="fa fa-puzzle-piece f-s-14"></i> </a></div>

                                        </div>
                                    </div>
                                    {{-- <span>{{ isset($url) ? $url : '' }}</span>--}}
                                    <div class=" templates-header ">
                    <span class=" templates-title text-center"><i class="fa fa-bars f-s-13 m-r-5"
                                                                  aria-hidden="true"></i> {!! $ui->title!!}</span>
                                        <div class=" templates-buttons text-center ">
                        <span class="authorColumn"><i class="fa fa-user author-icon" aria-hidden="true"></i>
                            {!! @$unit->author !!},</span> <span class="dateColumn"><i
                                                        class="fa fa-calendar calendar-icon"
                                                        aria-hidden="true"></i> {!! BBgetDateFormat($ui->created_at) !!}</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-xs-12 addon-item">
                            NO Results
                        </div>
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
    </div>

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
    {!! HTML::script('public/js/bootstrap-select/js/bootstrap-select.min.js') !!}
    <script>


        function confirm_delete(data) {
            var r = confirm("Are you sure !!!");
            if (r == true) {
                var slug = $(data).data('slug');
                $.ajax({
                    url: '/admin/uploads/gears/units/delete',
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
