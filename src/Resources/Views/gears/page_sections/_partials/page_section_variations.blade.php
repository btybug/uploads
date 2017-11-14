@if($pageSections)
    @foreach($pageSections as $ui)
        <div class="col-md-4">
            <div class="row templates m-b-10 ">
                <div class=" topRow p-l-0 p-r-0">
                    <img src="{!! url('/images/template-3.png')!!}" class="img-responsive"/>
                    <div class="tempalte_icon">
                    </div>
                </div>
                {{-- <span>{{ isset($url) ? $url : '' }}</span>--}}
                <div class=" templates-header ">
                    <span class=" templates-title text-center"><i class="fa fa-bars f-s-13 m-r-5"
                                                                  aria-hidden="true"></i> {!! $ui->title!!}</span>

                    <a data-href="{!! url('/admin/uploads/layouts/delete') !!}" data-key="{!! $ui->slug !!}"
                       data-type="{!! $ui->title !!} Layout" class="p-a-r-10-t-0 delete-button btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    <a href="{!! url('/admin/uploads/layouts/variations', $ui->slug) !!}" style="position: absolute;top: 0%;left: 10px;"
                       class="m-r-10 m-l-5 edit-button btn btn-info"><i class="fa fa-pencil-square-o"></i> </a>


                    <div class=" templates-buttons text-center ">
                        <span class="authorColumn"><i class="fa fa-user author-icon" aria-hidden="true"></i>
                            {!! @$unit->author !!},</span> <span class="dateColumn"><i
                                    class="fa fa-calendar calendar-icon"
                                    aria-hidden="true"></i> {!! BBgetDateFormat($ui->created_at) !!}</span>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-xs-12 addon-item">
        NO Results
    </div>
@endif

