<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row template-search div_top_31">
        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 template-search-box m-t-10 m-b-10">
            <form class="form-horizontal form_sort_31">
                <div class="form-group m-b-0">
                    <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Sort By</label>
                    <div class="col-sm-5">
                        {!! Form::select('sort',
                            [
                                ''=>'Order By',
                                'created_at.asc' => 'Recently Added',
                                'created_at.desc' => 'Old Added',
                                'name.asc' => 'Name by asc',
                                'name.desc' => 'Name by desc'
                            ],(isset($sort)?$sort : null),['data-sub' => (isset($subItem)?$subItem : null),'data-type' => (isset($main_type)?$main_type : 'text'),'class' => 'form-control sort-items']
                        ) !!}

                    </div>
                    <div class="col-sm-3 col-md-2 pull-right for_search_20">
                        <a class="btn btn-default"><i class="fa fa-search f-s-15" aria-hidden="true"></i></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 template-upload-button p-l-0 p-r-0">
            <button class="btn btn-sm  pull-right m-b-10 " type="button" data-toggle="modal"
                    data-target="#uploadfile">
                <span class="module_upload_icon m-l-20"></span> <span class="upload_module_text">Upload</span>
            </button>
        </div>
    </div>
    <div class="container_222">
        <div class="templates-list  m-t-20 m-b-10">
            <div class="row templates m-b-10">
                @if(count($styleItems))
                    @foreach($styleItems as $style)
                        <div class="raw">
                            {{--div styles--}}
                            <div class="col-xs-12  col-md-6 col-lg-3">
                                <div class="row templates m-b-10" id="styles_div">
                                    <div class="col-xs-12 p-l-0 p-r-0 preview_div"> {!! $style->html !!}
                                        <div class="tempalte_icon">
                                            {{--<div><a href="{!! url('admin/assets/classes/variation-class',$style->id) !!}" class="m-r-10"><i class="fa fa-desktop f-s-14"></i> </a></div>--}}
                                            {{--<div><a href="{!! url('admin/assets/classes/settings',$style->id) !!}" class="m-r-10"><i class="fa fa-cog f-s-14"></i> </a></div>--}}
                                            {{--<div><a href="javascript:void(0)" slug="slug" class="addons-delete del-tpl"><i class="fa fa-trash-o f-s-14 "></i> </a></div>--}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 templates-header p-t-10 p-b-10 div_title_1">
                                        <button class="button_title" data-styleId="{{$style->sub}}"
                                                data-id="{{ $style->id }}"><span
                                                    class="col-xs-12 templates-title f-s-15 text-center "
                                                    id="title_span" class="styles_title">
                                                @if($style->is_default)
                                                    <i class="fa fa-check-circle f-s-13 m-r-5"
                                                       style="color: #f1f3f0;background: #1ab394;"
                                                       aria-hidden="true"></i>

                                                @else
                                                    <a href="{!! url('admin/uploads/assets/styles/make-default',[$style->sub,$style->id]) !!}"
                                                       class="link_title_1"><i
                                                                class="fa fa-retweet f-s-13 m-r-5"
                                                                style="color: #ffffff;background: #4d6e8a;padding: 7px;border-radius: 50%;"
                                                                aria-hidden="true"></i></a>
                                                @endif
                                                {!! $style->name !!}</span></button>

                                        <div class="col-xs-12 templates-buttons text-center for_author">
                                            <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i>
                                            author, {!! BBgetDateFormat($style->created_at) !!}

                                        </div>
                                        <div class="col-xs-12 templates-buttons text-center link_del">
                                            <a href="{!! url('admin/uploads/assets/styles/delete',[$style->id])  !!}"
                                               data-toggle="modal" id="confirm-delete"><i class="fa fa-trash"
                                                                                          aria-hidden="true"></i>

                                                Delete</a>

                                        </div>
                                    </div>

                                </div>
                                {{--end div style--}}

                            </div>
                            <div class="add_popup">

                            </div>
                        </div>
                    @endforeach
            </div>
        </div>
    </div>
</div>




@else
    <div class="col-xs-12 addon-item no_style">
        NO Styles
    </div>
@endif