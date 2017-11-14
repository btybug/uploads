<div class="modal fade" id="uploadUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Version</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {!! Form::open(["url" => "admin/framework/upload-version",'class' => 'form-horizontal','files' => true]) !!}
                    {!! Form::hidden('parent_id',null,['id' => 'parentVersionID']) !!}
                    {!! Form::hidden('type','js',['id' => 'version-data-type']) !!}
                    <div>
                        <label for="username">Version</label>
                        {!! Form::text('version',null,['class' => 'form-control', 'placeholder' => 'Enter Version']) !!}
                    </div>
                    <div>
                        <label for="username">Upload file</label>
                        {!! Form::file('file',['class' => 'form-control']) !!}
                    </div>
                    <div>
                        {!! Form::submit('Upload',['class' => 'btn btn-success']) !!}
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
            $('body').on('click', '.update-js', function () {
                var id = $(this).data('id');
                console.log($(this).attr("data-jquery"))
                if($(this).attr("data-jquery")){
                    $("#version-data-type").val('jquery');
                }else{
                    $("#version-data-type").val('js');
                }
                $('#parentVersionID').val(id);
                $('#uploadUpdate').modal();
            });
        })

    </script>
@endpush