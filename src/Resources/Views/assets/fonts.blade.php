@extends('btybug::layouts.mTabs',['index'=>'upload_assets'])
@section('tab')
    <div class="col-md-12">
        <div class="col-md-12">
            <a href="javascript:void(0)" class="btn btn-warning uplJS pull-right">add new</a>
        </div>
        <h2>Fonts</h2>
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Lib</th>
                    <th>Author</th>
                    <th>Version</th>
                    <th>Positions</th>
                    <th>Live/Local</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($plugins) && isset($plugins))
                    {{--@foreach($plugins as $item)--}}
                        {{--<tr>--}}
                            {{--<td>--}}
                            {{--<input name="assets[{!! $item->id !!}]" value="0" type="hidden" />--}}
                            {{--<input name="assets[{!! $item->id !!}]" value="1" @if($item->is_generated) checked="checked" @endif type="checkbox" />--}}
                            {{--</td>--}}
                            {{--<td>{!! $item->name !!}</td>--}}
                            {{--<td>{!! BBGetUserName($item->author_id) !!}</td>--}}
                            {{--<td>{!! $item->version !!}</td>--}}
                            {{--<td>{!! Form::checkbox('front') !!}:frontend {!! Form::checkbox('back')!!}:backend </td>--}}
                            {{--<td>local</td>--}}
                            {{--<td>--}}
                                {{--<a href="javascript:void(0)" data-id="{!! $item->id !!}" class="btn btn-info update-js">--}}
                                    {{--Update </a>--}}
                                {{--<a href="javascript:void(0)" data-name="{!! $item->name !!}" data-id="{!! $item->id !!}"--}}
                                   {{--class="btn btn-primary change-version"> Change Version </a>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                @else
                    <tr>
                        <td rowspan="4">No libraries</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    @include('btybug::_partials.delete_modal')
    @include('uploads::assets._partials.upload_font_modal')
@stop

@section('CSS')
@stop

@section('JS')
@stop