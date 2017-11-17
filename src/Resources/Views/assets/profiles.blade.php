@extends('btybug::layouts.mTabs',['index'=>'upload_assets'])
@section('tab')
    <div class="container">
        <h2>Profiles</h2>
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Back.js
                    </td>
                    <td>
                        <a href="javascript:void(0)" data-section="back" class="btn btn-primary generate-version-js">
                            <i class="fa fa-plus"></i> Create</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Front.js
                    </td>
                    <td>
                        <a href="javascript:void(0)" data-section="front" class="btn btn-primary generate-version-js">
                            <i class="fa fa-plus"></i> Create</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="selectVersionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Generate JS</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row active-versions">

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('btybug::_partials.delete_modal')
    @include('framework::versions._partials.upload_js_modal')
@stop

@section('CSS')
@stop

@section('JS')
    <script>
        $(document).ready(function () {
            $('body').on('click', '.generate-version-js', function () {
                var section = $(this).data('section');
                $.ajax({
                    type: "post",
                    url: "{!! url('/admin/framework/get-active-versions') !!}",
                    cache: false,
                    datatype: "json",
                    data: {
                        section: section
                    },
                    headers: {
                        'X-CSRF-TOKEN': $("[name=_token]").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                            $('.active-versions').html(data.html);
                            $('#selectVersionsModal').modal();
                        }
                    }
                });
            });
        })
    </script>
@stop