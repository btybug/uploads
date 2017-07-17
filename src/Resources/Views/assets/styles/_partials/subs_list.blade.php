<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
    <div class="row template-search top_div_sort_41">
        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 template-search-box m-t-10 m-b-10">
            <form class="form-horizontal form_sort_31">
                <div class="form-group m-b-0">
                    <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label" style="font-weight: bold;">Sort
                        By</label>
                    <div class="col-sm-5">
                        {!! Form::select('sort',
                            [
                                ''=>'Order By',
                                'created_at.asc' => 'Recently Added',
                                'created_at.desc' => 'Old Added',
                                'name.asc' => 'Name by asc',
                                'name.desc' => 'Name by desc'
                            ], (isset($sort)?$sort : null),['data-sub' => (isset($subItem)?$subItem : null),'data-type' => (isset($main_type)?$main_type : 'text'),'class' => 'form-control sort-items']
                        )
                        !!}

                    </div>
                </div>
            </form>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 for_icons_17">
            <a class="pull-right for_search_17">
                <i class="fa fa-search f-s-15" aria-hidden="true"></i>
            </a>
            <a data-type="" class="add-class-modal pull-right for_add_17">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
            <input type="hidden" value="" name="addsub"/>
        </div>
    </div>
    <div class="container_222">
        <div class="templates-list  m-t-20 m-b-10">
            <div class="row templates m-b-10">
                @if(count($styles))
                    @foreach($styles as $slug => $style)
                        <div class="raw">
                            {{--div subs--}}
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="row templates m-b-10" id="styles_div">
                                    <div class="col-xs-12 p-l-0 p-r-0 main_div_for_styles_1">
                                        <div class="top_div_17 col-xs-12 col-md-12">
                                            <div class="for_image_17">
                                                <img src="{!! url('app/Modules/Resources/Resources/assets/img/img_1.png') !!}"
                                                     alt="image">
                                            </div>
                                        </div>

                                        <div class="for_title_17 col-xs-12 col-md-12"><a
                                                    href="{!! url('admin/uploads/assets/styles/style_preview',[$main_type,$slug]) !!}">{{ $style }}</a>
                                        </div>
                                        <div class="for_number_17 col-xs-12 col-md-12">ITEMS:
                                            <div class="for_number_circle_17">{{ count(0) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--end div style--}}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

