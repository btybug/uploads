@extends('cms::layouts.admin')
@section('content')
    <div class="main_lay_cont">
        <div class="row for_title_row">
            <h1 class="text-center">Uploads Assets</h1>
        </div>
        <div class="row layouts_row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 items_links">
                <a href="{!! url('admin/uploads/assets/profiles') !!}" class="ly_items">
                    <h3>Profiles</h3>
                    <h2><i class="fa fa-trello" aria-hidden="true"></i></h2>
                </a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 items_links">
                <a href="{!! url('admin/uploads/assets/styles') !!}" class="ly_items">
                    <h3>Styles</h3>
                    <h2><i class="fa fa-columns" aria-hidden="true"></i></h2>
                </a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 items_links">
                <a href="{!! url('admin/uploads/assets/files') !!}" class="ly_items">
                    <h3>Files</h3>
                    <h2><i class="fa fa-square-o" aria-hidden="true"></i></h2>
                </a>
            </div>
        </div>
    </div>
@stop
@section('CSS')
    {!! HTML::style('/css/backend_layouts_style.css') !!}
@stop
@section('JS')
@stop