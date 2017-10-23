<div id="edit_variation_form" class="col-md-4 new-variation ">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                Edit Template Variation
            </h4>
        </div>
        <div class="panel-body">
            {!! Form::model($variation,['method'=>'POST','url'=>'/admin/cloud/edit-variation']) !!}
            {!! Form::hidden('ui_slug',$slug[0]) !!}
            @if(!empty($variation))
                {!! Form::hidden('id',old('id')) !!}
            @endif
            <div class="form-group">
                {!! Form::label('title','Varitation Name') !!}
                {!! Form::text('title',old('title'), ['class' => 'form-control']) !!}
            </div>
            @if(isset($sections))
                <div class="form-group">
                    {!! Form::label('section','Section') !!}
                    {!! Form::select('section_id',[null=>'Select section']+$sections,old('section_id'),['class' => 'form-control']) !!}
                </div>
                @if(empty($variation))
                    <div class="form-group">
                        {!! Form::label('make_active','Make active') !!}
                        {!! Form::checkbox('make_active','active', false) !!}
                    </div>
                @endif
            @endif
            <button class="btn btn-sm btn-success mrg-btm-10" type="submit"><i class="fa fa-save"></i> Save Variation
            </button>
            <button class="btn btn-sm btn-default mrg-btm-10 cancel" type="button">Cancel</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>