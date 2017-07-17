@if(count($ui_elemements))
    @foreach($ui_elemements as $ui)
        <div id="viewType" class="col-xs-4">
            <div class="row templates m-b-10 ">
                <div class=" topRow p-l-0 p-r-0">
                    <img src="{!! url('resources/assets/images/template-3.png')!!}" class="img-responsive"/>
                    <div class="tempalte_icon">
                        <div><a href="{!! url('admin/uploads/gears/widgets-variations',$ui->slug) !!}"
                                class="m-r-10"><i class="fa fa-pencil f-s-14"></i> </a></div>
                        @if($ui->default == 0)
                            <div>
                                <button data-key="{!! $ui->slug !!}" type={!! $ui->type !!}""
                                        main_type="{!! @$ui->main_type !!}" class="addons-delete delete_layout"><i
                                            class="fa fa-trash-o f-s-14 "></i></button>
                                @if($ui->main_type == 'fields')
                                    <a href="{!! url('admin/resources/widgets/make-default',[$ui->slug]) !!}" class="addons-delete"><i
                                                class="fa fa-legal f-s-14 "></i></a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
               {{-- <span>{{ isset($url) ? $url : '' }}</span>--}}
                <div class=" templates-header ">
                    <span class=" templates-title text-center"><i class="fa fa-bars f-s-13 m-r-5"
                                                                                  aria-hidden="true"></i> {!! $ui->title or $ui->slug !!}</span>
                    <div class=" templates-buttons text-center ">
                        <span class="authorColumn"><i class="fa fa-user author-icon" aria-hidden="true"></i>
                        author,</span> <span class="dateColumn"><i class="fa fa-calendar calendar-icon" aria-hidden="true"></i> {!! BBgetDateFormat($ui->created_at) !!}</span>

                    </div>
                </div>
            </div>
        </div>

    @endforeach
@else
    <div class="col-xs-12 addon-item">
        NO Ui Elements
    </div>
@endif
