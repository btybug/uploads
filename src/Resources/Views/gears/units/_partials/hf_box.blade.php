@if($currentTemplate)
    <div class="layout clearfix">
        <img src="/app/Modules/Resources/img/layout-img.jpg" alt="" class="layoutImg">
        <div class="layoutData">
            <div class="layoutCol">
                <h4>{!! $currentTemplate->title !!}</h4>
                <p>{!! @$currentTemplate->description !!}</p>

                @if($currentTemplate && !$currentTemplate->is_core)
                    <a data-href="{!! url('/admin/uploads/gears/h-f/delete') !!}"
                       data-key="{!! $currentTemplate->slug !!}" data-type="H&F"
                       class="delete-button btn btn-danger p-a-r-10-t-0"><i class="fa fa-trash-o"></i></a>
                @endif
            </div>
            <div class="layoutFooter row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                    <span class="textWrap"><a href="{!! @$currentTemplate->author_site !!}"
                                              class="link"><i>{!! @$currentTemplate->author_site !!}</i></a></span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4  centerText">
                    <span class="iconRefresh"><i class="fa fa-refresh"></i></span> {!! @$currentTemplate->version !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 rightText">
                    <i class="fa fa-user"></i> {!! @$currentTemplate->author !!}
                    , {!! BBgetDateFormat(@$currentTemplate->created_at) !!}
                </div>

            </div>
        </div>
    </div>
@endif
