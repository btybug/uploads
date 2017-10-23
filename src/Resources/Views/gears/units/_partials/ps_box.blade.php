@if($currentPageSection)
    <div class="layout clearfix">
        <img src="/app/Modules/Resources/img/layout-img.jpg" alt="" class="layoutImg">
        <div class="layoutData">
            <div class="layoutCol">
                <h4>{!! $currentPageSection->title !!}</h4>
                <p>{!! @$currentPageSection->description !!}</p>

                @if($currentPageSection && !$currentPageSection->is_core)
                    <a data-href="{!! url('/admin/uploads/gears/page-sections/delete') !!}"
                       data-key="{!! $currentPageSection->slug !!}" data-type="Page Section"
                       class="p-a-r-10-t-0 delete-button btn btn-danger"><i class="fa fa-trash-o"></i></a>
                @endif
            </div>
            <div class="layoutFooter row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                    <span class="textWrap"><a href="{!! @$currentPageSection->author_site !!}"
                                              class="link"><i>{!! @$currentPageSection->author_site !!}</i></a></span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4  centerText">
                    <span class="iconRefresh"><i class="fa fa-refresh"></i></span> {!! @$currentPageSection->version !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 rightText">
                    <i class="fa fa-user"></i> {!! @$currentPageSection->author !!}
                    , {!! BBgetDateFormat(@$currentPageSection->created_at) !!}
                </div>

            </div>
        </div>
    </div>
@endif
