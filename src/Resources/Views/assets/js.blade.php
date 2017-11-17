@extends('btybug::layouts.mTabs',['index'=>'upload_assets'])
@section('tab')
    <div class="col-md-12">
        <div class="col-md-12">
            <a href="javascript:void(0)" class="btn btn-warning uplJS pull-right">add new</a>
        </div>
        <h2>Jquery</h2>
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Version</th>
                    <th>Positions</th>
                    <th>Live/Local</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{!! (isset($jquery['backend_jquery'])) ? $jquery['backend_jquery']['version'] : "Inactive" !!}</td>
                    <td>backend</td>
                    <td>local</td>
                    <td>
                        <a href="javascript:void(0)"
                           data-id="{!! (isset($jquery['backend_jquery'])) ? $jquery['backend_jquery']['id'] : "backend_jquery" !!}"
                           data-jquery="true" class="btn btn-info update-js">
                            +add new </a>
                        <a href="javascript:void(0)"
                           data-name="backend_jquery"
                           data-id="{!! (isset($jquery['backend_jquery'])) ? $jquery['backend_jquery']['id'] : "backend_jquery" !!}"
                           class="btn btn-primary change-version"> Change Version </a>
                    </td>
                </tr>
                <tr>
                    <td>{!! (isset($jquery['frontend_jquery'])) ? $jquery['frontend_jquery']['version'] : "Inactive" !!}</td>
                    <td>frontend</td>
                    <td>local</td>
                    <td>
                        <a href="javascript:void(0)"
                           data-id="{!! (isset($jquery['frontend_jquery'])) ? $jquery['frontend_jquery']['id'] : "frontend_jquery" !!}"
                           data-jquery="true" class="btn btn-info update-js">
                            +add new </a>
                        <a href="javascript:void(0)"
                           data-name="frontend_jquery"
                           data-id="{!! (isset($jquery['frontend_jquery'])) ? $jquery['frontend_jquery']['id'] : "frontend_jquery" !!}"
                           class="btn btn-primary change-version"> Change Version </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <h2>JS</h2>

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
                            <td>
                                @if($item->env)
                                    <a href="{!! url('admin/uploads/assets/settings') !!}">Choose from Settings</a>
                                @else
                                    {!! Form::checkbox('is_generated_front',true,$item->is_generated_front,['class' => 'generate','data-id'=>$item->id]) !!}:frontend
                                    {!! Form::checkbox('is_generated',true,$item->is_generated,['class' => 'generate','data-id'=>$item->id])!!}:backend
                                @endif
                            </td>
                            <td>{!! ($item->env) ? "Live" : "Local" !!}</td>
                            <td>
                                @if($item->env)
                                    <a href="javascript:void(0)" data-link="{!! $item->file_name !!}" data-id="{!! $item->id !!}" class="btn btn-info update-live">Update</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{!! $item->id !!}" class="btn btn-info update-js">
                                        +add new </a>
                                    <a href="javascript:void(0)" data-name="{!! $item->name !!}" data-id="{!! $item->id !!}"
                                       class="btn btn-primary change-version"> Change Version </a>
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
                        <td rowspan="5">No libraries</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    @include('btybug::_partials.delete_modal')
    @include('uploads::assets._partials.upload_js_modal')
    @include('uploads::assets._partials.update_js_modal')
    @include('uploads::assets._partials.update_live_modal')
    @include('uploads::assets._partials.change_version_modal')
@stop

@section('CSS')
@stop

@section('JS')
    <script>
        $(document).ready(function () {
            $("body").on("change",".generate",function () {
                var id = $(this).data('id');
                var name = $(this).attr("name");
                var value = this.checked ? 1 : 0;
                $.ajax({
                    type: "post",
                    url: "{!! url('/admin/framework/generate-main-js') !!}",
                    cache: false,
                    datatype: "json",
                    data: {
                        id: id,
                        name: name,
                        value: value
                    },
                    headers: {
                        'X-CSRF-TOKEN': $("[name=_token]").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                        }
                    }
                });
            })
        });
    </script>
@stop