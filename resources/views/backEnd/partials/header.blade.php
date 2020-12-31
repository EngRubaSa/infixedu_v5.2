<!DOCTYPE html>
@php
    $generalSetting = generalSetting()
@endphp

<html lang="{{ app()->getLocale() }}"
 @if(textDirection() ==1) dir="rtl" class="rtl" @endif >
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    @if( ! is_null(schoolConfig()->favicon ))
    <link rel="icon" href="{{asset(schoolConfig()->favicon)}}" type="image/png"/>
@else 
<link rel="icon" href="{{asset('public/uploads/settings/favicon.png')}}" type="image/png"/>
@endif

    <title>{{@schoolConfig()->school_name ? @schoolConfig()->school_name : 'Infix Edu ERP'}} |
        {{schoolConfig()->site_title ? schoolConfig()->site_title : 'School Management System'}}</title>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <!-- Bootstrap CSS -->

    @if( textDirection() ==1)

        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/rtl/bootstrap.min.css"/>
    @else

        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css"/>
    @endif


    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/jquery-ui.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/jquery.data-tables.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/responsive.dataTables.min.css">


    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap-datetimepicker.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/themify-icons.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/flaticon.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/nice-select.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/magnific-popup.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/fastselect.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/js/select2/select2.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/fullcalendar.min.css">
    {{-- <link rel="stylesheet" href="{{asset('public/landing/css/toastr.css')}}"> --}}
    @yield('css')
    <link rel="stylesheet" href="{{asset('public/backEnd/css/loade.css')}}"/>
    @if(textDirection() ==1)

        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/rtl/style.css"/>
        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/rtl/infix.css"/>
    @else
    
        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/{{activeStyle()->path_main_style}}"/>
        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/{{activeStyle()->path_infix_style}}"/>
    @endif
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: {{@activeStyle()->primary_color2}}  !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: {{@activeStyle()->primary_color2}}   !important;
        }

        ::placeholder {
            color: {{@activeStyle()->primary_color}}   !important;
        }

        .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-bottom {
            z-index: 99999999999 !important;
            background: #fff !important;
        }

        .input-effect {
            float: left;
            width: 100%;
        }
    </style>

    

</head>
<?php
if (empty(dashboardBackground())) {
    $css = "background: url('/public/backEnd/img/body-bg.jpg')  no-repeat center; background-size: cover; ";
} else {
    if (!empty(dashboardBackground()->image)) {
        $css = "background: url('" . url(dashboardBackground()->image) . "')  no-repeat center; background-size: cover; ";
    } else {
        $css = "background:" . dashboardBackground()->color;
    }
}
?>
<body class="admin"
      style=" @if(@activeStyle()->id==1) {{$css}} @else background:{{@activeStyle()->dashboardbackground}} !important; @endif ">

    @php

        if (file_exists(generalSetting()->logo)) {
            $tt = file_get_contents(url('/').'/'.generalSetting()->logo);
        } else {
             $tt = file_get_contents(url('/').'/public/uploads/settings/logo.png');
        }

    @endphp

    <input type="text" hidden value="{{ base64_encode($tt) }}" id="logo_img">
    <input type="text" hidden value="{{ @generalSetting()->school_name }}" id="logo_title">
    <div class="main-wrapper" style="min-height: 600px">
        <!-- Sidebar  -->

    @if(moduleStatusCheck('SaasSubscription')== TRUE)
        @if(\Modules\SaasSubscription\Entities\SmPackagePlan::isSubscriptionAutheticate())

            @include('backEnd.partials.sidebar')

        @else

            @include('saassubscription::menu.SaasSubscriptionSchool_trial')

        @endif

    @else

        @include('backEnd.partials.sidebar')

    @endif

    <!-- Page Content  -->
        <div id="main-content">
            <input type="hidden" name="url" id="url" value="{{url('/')}}">

@include('backEnd.partials.menu')
