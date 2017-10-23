@if(count($variations))
    @foreach($variations as $variation)
        <div id="viewType" class="col-xs-4">
            <div class="row templates m-b-10 ">
                <div class=" topRow p-l-0 p-r-0">
                    <img src="{!! url('/images/template-3.png')!!}" class="img-responsive"/>
                    <div class="tempalte_icon">
                        <div><a href="{!! url('/admin/console/backend/h-f/settings-live',$variation->id) !!}"
                                class="m-r-10"><i class="fa fa-pencil f-s-14"></i> </a></div>
                        @if(count($variations)>1)
                            <div>
                                <a href="{!! url('/admin/console/backend/h-f/delete-variation',$variation->id) !!}"
                                   class="addons-delete delete_layout">
                                    <i class="fa fa-trash-o f-s-14 "></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class=" templates-header ">
                    <span class=" templates-title text-center"><i class="fa fa-bars f-s-13 m-r-5"
                                                                  aria-hidden="true"></i> {!! $variation->title or $variation->id !!}</span>

                </div>
            </div>
        </div>

    @endforeach
@else
    <div class="col-xs-12 addon-item">
        NO Ui Elements
    </div>
@endif
