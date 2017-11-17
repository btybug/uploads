@extends('btybug::layouts.admin')
@section('content')

    <ol class="breadcrumb">
        <li><a href="/">Dashboard</a></li>
        <li class="active">Unit variations</li>
    </ol>

    <div class="row">

        <div id="var_listind" class="col-md-8  table-responsive p-0">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        Variations for [{{ $unit->title }}] unit
                        <a href="{!! url('/admin/uploads/gears/settings', $unit->slug) !!}"
                           class="btn btn-xs btn-success pull-right" id="new-variation"
                           style="color:#fff;">New Variation</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <table width="100%" class="table table-bordered m-0">
                        <thead>
                        <tr class="bg-black-darker text-white">
                            <th>Variation Name</th>
                            <th width="150">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($variations as $variation_data)
                            <tr>
                                <td><a href="#" class="editable" data-type="text" data-pk="{{$variation_data->id}}"
                                       data-title="Template Variation Title">{{$variation_data->title}}</a></td>
                                </td>
                                <td>
                                    <a href="{!! url('/admin/uploads/gears/settings', $variation_data->id) !!}"
                                       class="btn btn-default btn-warning btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                    <a href="/admin/uploads/gears/delete-variation/{{ $variation_data->id }}"
                                       class="btn btn-danger btn-xs"
                                       onclick="return confirm('Are you sure to delete')"> &nbsp;<i
                                                class="fa fa-trash"></i> &nbsp;</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 new-variation @if(empty($variation)) hide @endif">

        </div>


    </div>

@stop

@section('CSS')
    {!! HTML::style('public/js/bootstrap-editable/css/bootstrap-editable.css') !!}
@stop

@section('JS')
    {!! HTML::script('public/js/bootstrap-editable/js/bootstrap-editable.min.js') !!}
    <script>
        $('.myTabs li:eq(0) a').tab('show');
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('.new-variation').addClass('hide');
        })
        $('.cancel').click(function () {
            $('.new-variation').addClass('hide');
        });
    </script>

@stop
