@extends('cms::layouts.mTabs',['index'=>'uploads_assets'])
<!-- Nav tabs -->
@section('tab')
    <div class="collection_page">
        <div class="row top_buttons">
            <div class="pull-right">
                <button class="create_col add_Profile"><i class="fa fa-plus" aria-hidden="true"></i>Create Collection
                </button>
                <button class="upload_col"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Upload New Collection
                </button>
            </div>
        </div>
        <div class="row colections_container">
            @if(count($profiles))
                @foreach($profiles as $profile)
                    <div class="collections">
                        <div class="col-md-3 first_area">
                            <a href="">{{ $profile->name }}</a>
                        </div>
                        <div class="col-md-4 second_area">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span class="name_auth">{{ $profile->user->username }}
                                , {{ BBgetDateFormat($profile->created_at) }}</span>
                        </div>
                        <div class="col-md-3 third_area">
                            @if($profile->is_default)
                                <button class="make_def default">
                                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>Default
                                </button>
                            @else
                                <button data-id="{!! $profile->id !!}" class="make_def make_default">
                                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>Make Default
                                </button>
                            @endif
                        </div>
                        <div class="col-md-2 fourth_area pull-right">
                            <div class="">
                                @if(! $profile->is_default)
                                    <button data-id="{!! $profile->id !!}" class="trash_btn del_profile"><i
                                                class="fa fa-trash-o" aria-hidden="true"></i></button>
                                @endif
                                <a href="{!! url('admin/uploads/assets/profiles/edit',$profile->id) !!}"
                                   class="pencil_btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                No Prpfiles
            @endif
        </div>
    </div>

    <div class="modal fade" id="addProfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Profile</h4>
                </div>
                <div class="modal-body">
                {!! Form::open(['url'=>'/admin/uploads/assets/profiles/create','class' => 'form-horizontal']) !!}
                <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="name">Profile Name</label>
                        <div class="col-md-4">
                            {!! Form::text('name',null,['class' => 'form-control input-md']) !!}
                            <span class="help-block">all default styles will applied on create</span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="name"></label>
                        <div class="col-md-4">
                            {!! Form::submit('Create',['class' => 'btn btn-info']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!} </div>
            </div>
        </div>
    </div>
@stop

@section('CSS')
    {!! HTML::style('/css/create_pages.css') !!}
@stop
@section('JS')
    <script>
        $(document).ready(function () {
            $(".add_Profile").on("click", function () {
                $("#addProfile").modal();
            });
            $(".make_default").on("click", function () {
                var id = $(this).data("id");
                $.ajax({
                    type: "post",
                    url: "/admin/uploads/assets/profiles/make-active",
                    cache: false,
                    datatype: "json",
                    data: {id: id},
                    headers: {
                        'X-CSRF-TOKEN': $("[name=_token]").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                            window.location.reload();
                        }
                    }
                });
            });

            $(".del_profile").on("click", function () {
                var id = $(this).data("id");
                $.ajax({
                    type: "post",
                    url: "/admin/uploads/assets/profiles/delete",
                    cache: false,
                    datatype: "json",
                    data: {id: id},
                    headers: {
                        'X-CSRF-TOKEN': $("[name=_token]").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                            window.location.reload();
                        }
                    }
                });
            });
        });
    </script>
@stop