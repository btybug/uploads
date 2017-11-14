<div class="modal fade" id="changeVersion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Version</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row versions-box">

                </div>
            </div>
        </div>
    </div>
</div>
@push('javascript')
    <script>
        $(document).ready(function () {
            $('body').on('click', '.change-version', function () {
                var name = $(this).data('name');
                var id = $(this).data('id');
                $.ajax({
                    type: "post",
                    url: "{!! url('/admin/framework/get-versions') !!}",
                    cache: false,
                    datatype: "json",
                    data: {
                        id: id,
                        name: name,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $("[name=_token]").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                            $('.versions-box').html(data.html);
                            $('#changeVersion').modal();
                        }
                    }
                });
            });
        })

    </script>
@endpush