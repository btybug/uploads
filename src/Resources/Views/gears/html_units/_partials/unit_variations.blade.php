@if($unit)
    @foreach($unit->variations() as $ui)
        <div id="viewType" class="col-xs-4">
            <div class="row templates m-b-10 ">
                <div class=" topRow p-l-0 p-r-0">
                    <img src="{!! url('resources/assets/images/template-3.png')!!}" class="img-responsive"/>
                    <div class="tempalte_icon">
                        <div><a href="{!! url('/admin/uploads/gears/component/settings', $ui->id) !!}"
                                class="m-r-10"><i class="fa fa-pencil f-s-14"></i> </a></div>
                        @if($ui->default == 0)
                            <div>
                                @if(count($unit->variations()) > 1)
                                <a data-href="{!! url('/admin/uploads/gears/units/delete-variation') !!}" data-key="{!! $ui->id !!}" data-type="Unit Variation" class="delete-button addons-delete delete_layout"><i
                                            class="fa fa-trash-o f-s-14 "></i></a>
                                @endif
                                <a href="{!! url('admin/uploads/gears/component/make-default-variation',[$ui->id,$unit->id]) !!}" class="addons-delete"><i
                                                class="fa fa-legal f-s-14 "></i></a>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- <span>{{ isset($url) ? $url : '' }}</span>--}}
                <div class=" templates-header " style="height: 90px!important;">
                    <span class=" templates-title text-center"><i class="fa fa-bars f-s-13 m-r-5"
                                                                  aria-hidden="true"></i> {!! $ui->title!!}</span>
                    <div class=" templates-buttons text-center ">
                        <span class="authorColumn"><i class="fa fa-user author-icon" aria-hidden="true"></i>
                        {!! @$unit->author !!},</span> <span class="dateColumn"><i class="fa fa-calendar calendar-icon" aria-hidden="true"></i> {!! BBgetDateFormat($ui->created_at) !!}</span>,
                       <div style="font-size: 13px;">ShortCode: {!! htmlentities('[component id=' . $ui->id . ']') !!}</div>

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

