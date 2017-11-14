<div class="modal fade" id="updateLink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Link</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {!! Form::open(["url" => "admin/framework/update-link",'class' => 'form-horizontal','files' => true]) !!}
                    {!! Form::hidden('id',null,['id' => 'live-id']) !!}
                    <div>
                        <label for="username">Link</label>
                        {!! Form::text('link',null,['class'=>'form-control','id' => 'update-link']) !!}
                    </div>
                    <div>
                        {!! Form::submit('Save Changes',['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@push('javascript')
    <script>
        $(document).ready(function () {
            $('body').on('click', '.update-live', function () {
                var id = $(this).data('id');
                var link = $(this).data('link');
                $('#updateLink #live-id').val(id);
                $('#update-link').val(link);
                $('#updateLink').modal();
            });
        })

    </script>
@endpush