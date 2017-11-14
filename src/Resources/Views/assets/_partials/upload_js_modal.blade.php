<div class="modal fade" id="uploadJs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Upload JS</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {!! Form::open(["url" => "admin/framework",'class' => 'form-horizontal','files' => true]) !!}
                    {!! Form::hidden('type','js') !!}
                    <div>
                        <label for="username">Name</label>
                        {!! Form::text('name',null,['class' => 'form-control', 'placeholder' => 'Enter name']) !!}
                    </div>
                    <div>
                        <label for="username">Version</label>
                        {!! Form::text('version',null,['class' => 'form-control', 'placeholder' => 'Enter Version']) !!}
                    </div>
                    <div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="checkboxes">Select Content Type</label>
                            <div class="col-md-4">
                                <label class="checkbox-inline" for="checkboxes-0">
                                    <input type="radio" name="env"  checked value="file">
                                    File
                                </label>
                                <label class="checkbox-inline" for="checkboxes-1">
                                    <input type="radio" name="env"  value="link">
                                   Link
                                </label>
                            </div>
                        </div>
                        <div class="file_content">
                        <label for="username">Upload file</label>
                        {!! Form::file('file',['class' => 'form-control']) !!}
                        </div>
                        <div class="link_content" style="display: none">
                        <label for="username"  class="link_content">Add link</label>
                        {!! Form::text('link',null,['class' => 'form-control']) !!}
                        </div>
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
            $('body').on('click', '.uplJS', function () {
                $('#uploadJs').modal();
            });
            $('input[name=env]').on('change',function () {
                var type=$(this).val();
                switch (type){
                    case 'file':
                        $('.link_content').hide();
                        $('.file_content').show();
                        break;
                    case 'link':
                        $('.file_content').hide();
                        $('.link_content').show();
                        break;
                }

            });
        })

    </script>
@endpush