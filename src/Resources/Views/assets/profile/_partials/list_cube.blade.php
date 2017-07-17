<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="container_222">
        <div class="templates-list  m-t-20 m-b-10">
            <div class="row templates m-b-10">
                <h3>Profile "{!! $profile->name !!}" Edit styles</h3>

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
                                        <div><span class="col-xs-12 templates-title f-s-15 text-center "
                                                    id="title_span" class="styles_title">
                                                @if($default && $style->id == $default->id)
                                                    <i class="fa fa-check-circle f-s-13 m-r-5"
                                                       style="color: #f1f3f0;background: #1ab394;"
                                                       aria-hidden="true"></i>
                                                    {!! Form::hidden('default',$default->id,['class' => 'default_style']) !!}
                                                @else
                                                    <a data-profile="{!!  $profile->id !!}" data-style="{!! $style->id !!}" href="#"
                                                       class="link_title_1 make-default-style"><i
                                                                class="fa fa-retweet f-s-13 m-r-5"
                                                                style="color: #ffffff;background: #4d6e8a;padding: 7px;border-radius: 50%;"
                                                                aria-hidden="true"></i></a>
                                                @endif
                                                <div class="button_title" style="cursor: pointer;" data-styleId="{{$style->style_id}}"
                                                     data-id="{{ $style->id }}" >{!! $style->name !!}</div></span></div>

                                        <div class="col-xs-12 templates-buttons text-center for_author">
                                            <i class="fa fa-user f-s-13 author-icon" aria-hidden="true"></i>
                                            author, {!! BBgetDateFormat($style->created_at) !!}

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