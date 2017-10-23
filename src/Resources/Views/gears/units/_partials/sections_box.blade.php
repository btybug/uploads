@if($current)
    <div class="layout clearfix">
        <img src="/app/Modules/Resources/img/layout-img.jpg" alt="" class="layoutImg">
        <div class="layoutData">
            <div class="layoutCol">
                <h4>{!! $current->title !!}</h4>
                <p>{!! @$current->description !!}</p>

                @if($current && !$current->is_core)
                    <a data-href="{!! url('/admin/uploads/gears/sections/delete') !!}" data-key="{!! $current->slug !!}"
                       data-type="Section" class="p-a-r-10-t-0 delete-button btn btn-danger"><i
                                class="fa fa-trash-o"></i></a>
                @endif
            </div>
            <div class="layoutFooter row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                    <span class="textWrap"><a href="{!! @$current->author_site !!}"
                                              class="link"><i>{!! @$current->author_site !!}</i></a></span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4  centerText">
                    <span class="iconRefresh"><i class="fa fa-refresh"></i></span> {!! @$current->version !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 rightText">
                    <i class="fa fa-user"></i> {!! @$current->author !!}, {!! BBgetDateFormat(@$current->created_at) !!}
                </div>

            </div>
        </div>
    </div>
@endif
