@if($currentMainBody)
    <div class="layout clearfix">
        <img src="/app/Modules/Resources/img/layout-img.jpg" alt="" class="layoutImg">
        <div class="layoutData">
            <div class="layoutCol">
                <h4>{!! $currentMainBody->title !!}</h4>
                <p>{!! @$currentMainBody->description !!}</p>

                @if($currentMainBody && !$currentMainBody->is_core)
                    <a data-href="{!! url('/admin/uploads/gears/main-body/delete') !!}"
                       data-key="{!! $currentMainBody->slug !!}" data-type="Main Body"
                       class="p-a-r-10-t-0 delete-button btn btn-danger"><i class="fa fa-trash-o"></i></a>
                @endif
            </div>
            <div class="layoutFooter row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                    <span class="textWrap"><a href="{!! @$currentMainBody->author_site !!}"
                                              class="link"><i>{!! @$currentMainBody->author_site !!}</i></a></span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4  centerText">
                    <span class="iconRefresh"><i class="fa fa-refresh"></i></span> {!! @$currentMainBody->version !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 rightText">
                    <i class="fa fa-user"></i> {!! @$currentMainBody->author !!}
                    , {!! BBgetDateFormat(@$currentMainBody->created_at) !!}
                </div>

            </div>
        </div>
    </div>
@endif
