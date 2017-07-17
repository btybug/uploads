@if(count($files))
    @foreach($files as $file)
        <div class="col-xs-2">
            <div class="row templates m-b-10">
                <div class="col-xs-12 p-l-0 p-r-0">
                    <img src="{!! url($file->image)!!}" class="img-responsive"/>
                    <div class="tempalte_icon">
                        <div>
                            <button data-key="{!! $file->slug !!}" type={!! $file->type !!}""
                                    main_type="{!! @$file->main_type !!}" class="addons-delete delete_layout"><i
                                        class="fa fa-trash-o f-s-14 "></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 templates-header p-t-10 p-b-10">
                        <span class="col-xs-12 templates-title f-s-15 text-center"><i class="fa fa-bars f-s-13 m-r-5"
                                                                                      aria-hidden="true"></i> {!! $file->title or $file->slug !!}</span>
                    <div class="col-xs-12 templates-buttons text-center ">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-xs-12 addon-item">
        NO Files
    </div>
@endif
