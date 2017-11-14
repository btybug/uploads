<div class="col-md-12">
    @if(count($data))
        {!! Form::open(['url' => 'admin/framework/generate-main-js']) !!}
        {!! Form::hidden('section',$section,['id' => 'jsSection']) !!}
        @foreach($data as $key => $item)
            <label class="col-md-3 version-item-box">
                {!! $item->version !!}
                {!! Form::hidden("assets[$item->id]",0) !!}
                {!! Form::checkbox("assets[$item->id]",1) !!}
            </label>
        @endforeach
        <div class="col-md-12">
            {!! Form::submit("Change",['class' => 'btn btn-warning pull-right']) !!}
        </div>
        {!! Form::close() !!}
    @else
        No Versions
    @endif
</div>

<style>
    .version-item-box {
        height: 80px;
        text-align: center;
        border: 1px solid;
        background: #00cdac;
        border-radius: 20px;
        padding-top: 20px;
        cursor: pointer;
    }
</style>