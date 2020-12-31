<script type="text/javascript" src="{{asset('public/backEnd/js/main.js')}}"></script>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <h1>{{@$course->title}}</h1>
            <img src="{{asset($course->image)}}" class="mt-3 mr-3" style="float: left">
            <h3 class="mt-3">@lang('lang.overview'): </h3>
            <p>{{@$course->overview}}</p>
            <h3 class="mt-3">@lang('lang.outline'): </h3>
            <p>{{@$course->outline}}</p>
            <h3 class="mt-3">@lang('lang.prerequisites'): </h3>
            <p>{{@$course->prerequisites}}</p>
            <h3 class="mt-3">@lang('lang.resources'): </h3>
            <p>{{@$course->resources}}</p>
            <h3 class="mt-3">@lang('lang.stats'): </h3>
            <p>{{@$course->stats}}</p>
        </div>
    </div>

