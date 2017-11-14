<div class="col-md-12">
    @if(count($data))
        {!! Form::open(['url' => 'admin/framework/change-version']) !!}
        @foreach($data as $key => $item)
            <label class="col-md-3 version-item-box">
                {!! $item->version !!}
                {!! Form::radio('id',$item->id) !!}
            </label>
        @endforeach
        <div class="col-md-12">
            {!! Form::submit("Change",['class' => 'btn btn-success pull-right']) !!}
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
        background: indianred;
        border-radius: 20px;
        padding-top: 20px;
        cursor: pointer;
    }
</style>