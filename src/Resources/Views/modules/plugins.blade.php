@if(!empty($addons))
    <div class="col-xs-12">
        @foreach($addons as $addon)
            <div class="col-xs-12 addon-item">
                <div class="col-xs-8">
                                <span class="addon-name">
                                    {!! $addon->name !!}
                                </span>
                </div>
                <div class="col-xs-4 pull-right">
                    @if( $addon->have_setting==1)
                        <p>
                            <a href="{!! url('admin/plugins/setting',$addon->slug) !!}"
                               class="btn btn-default">&nbsp;<i
                                        class="fa fa-cog"></i>&nbsp;</a>
                        </p>
                    @endif
                    <p>
                        @if($addon->enabled)
                            <a href="#" namespace="{!! $addon->slug !!}" data-action="disable"
                               class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left enb-disb deactivate"><i
                                        class="fa fa-power-off f-s-14 m-r-10"></i> Deactivate</a>
                        @else
                            <a href="#" namespace="{!! $addon->slug !!}" data-action="core-enable"
                               style="background: #7fff00;color: #000000"
                               class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left  enb-disb"><i
                                        class="fa fa-plug f-s-14 m-r-10"></i>Activate</a>
                        @endif
                    </p>

                    <a href="#" namespace="{!! $addon->slug !!}"
                       class="btn  btn-sm  m-b-5 p-l-20 p-r-20 width-150 text-left delete del-module"><i
                                class="fa fa-trash-o f-s-14 m-r-10"></i> Delete</a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="col-xs-12">
        No Plugins
    </div>
@endif