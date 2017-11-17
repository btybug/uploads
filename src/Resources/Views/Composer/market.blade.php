@extends('btybug::layouts.mTabs',['index'=>'upload_market'])
<!-- Nav tabs -->
@section('tab')
    <div class=" col-sm-12 col-md-12">
        @if(count($output['results']))
            @foreach($output['results'] as $package)
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail">
                        <img src="{!! url('images/plugin.jpg') !!}" alt="plugin">
                        <div class="caption">
                            <h4><a href="{!! $package['url'] !!}" target="_blank">{!! $package['name']!!}</a></h4>
                            <p><a href="{!! $package['repository'] !!}" target="_blank">Repository</a></p>
                            <p>{!! $package['description'] !!}</p>
                            <p>Downloads:{!! $package['downloads'] !!}</p>
                            <p>Favers:{!! $package['favers'] !!}</p>
                            <p><a href="{!! url('admin/avatar/composer?p='.$package['name']) !!}"
                                  class="btn btn-primary" role="button">Install</a> <a href="#" class="btn btn-default"
                                                                                       role="button">Uninstall</a></p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@stop