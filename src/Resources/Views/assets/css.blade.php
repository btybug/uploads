@extends('btybug::layouts.mTabs',['index'=>'upload_assets'])
@section('tab')
    <div class="col-md-12">
        <div class="col-md-12">
            <a href="javascript:void(0)" class="btn btn-warning pull-right uplCss">add new</a>
        </div>
        <h2>CSS</h2>

        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Lib</th>
                    <th>Author</th>
                    <th>Version</th>
                    <th>Live/Local</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($plugins))
                    @foreach($plugins as $item)
                        <tr>
                            {{--<td>--}}
                            {{--<input name="assets[{!! $item->id !!}]" value="0" type="hidden" />--}}
                            {{--<input name="assets[{!! $item->id !!}]" value="1" @if($item->is_generated) checked="checked" @endif type="checkbox" />--}}
                            {{--</td>--}}
                            <td>{!! $item->name !!}</td>
                            <td>{!! BBGetUserName($item->author_id) !!}</td>
                            <td>{!! $item->version !!}</td>
                            <td>{!! ($item->env) ? "Live" : "Local" !!}</td>
                            <td>
                                @if($item->env)
                                    <a href="javascript:void(0)" data-link="{!! $item->file_name !!}" data-id="{!! $item->id !!}" class="btn btn-info update-live">Update</a>
                                @endif

                                <a data-href="{!! url('admin/uploads/assets/delete') !!}"
                                   data-key="{!! $item->id !!}" data-type="{{ $item->type.' '.$item->name }}"
                                   class="delete-button btn btn-danger"><i
                                            class="fa fa-trash-o f-s-14 "></i></a>
                            </td>
                        </tr>
                    @endforeach
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
    @include('uploads::assets._partials.upload_modal')

    @include('framework::versions._partials.update_live_modal')
@stop

@section('CSS')
@stop

@section('JS')
@stop