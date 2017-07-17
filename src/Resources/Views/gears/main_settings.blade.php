<div class="row">
    <div class="col-xs-12">

        <div class="panel panelDarkBlack">
            <div class="panel-heading">
                <a data-toggle="collapse" href="#header-widgets"><span class="panelArrow"><i
                                class="fa fa-angle-down"></i></span>Style Setting</a>
            </div>
            <div id="header-widgets" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="form-horizontal">
                        {!! $styles !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panelDarkBlack">
            <div class="panel-heading">
                <a data-toggle="collapse" href="#input-settings"><span class="panelArrow"><i
                                class="fa fa-angle-down"></i></span>Field Options </a>
            </div>
            <div class="panel-collapse collapse in">
                <div class="panel-body">
                    {!! $options !!}
                </div>
            </div>
        </div>
    </div>
</div>

{!! Html::style('app/Modules/Resources/Resources/assets/css/settings.css') !!}
