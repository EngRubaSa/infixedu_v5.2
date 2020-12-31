@extends('backEnd.master')
@section('mainContent')
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <select class="niceSelect w-100 bb">
                                        <option data-display="Select Class">@lang('lang.select_month')</option>
                                        <option value="1"> @lang('lang.may') </option>
                                        <option value="2"> @lang('lang.june') </option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mt-30-md">
                                    <select class="niceSelect w-100 bb">
                                        <option data-display="Select Class">@lang('lang.select_package')</option>
                                        <option value="1">@lang('lang.infix_edu')</option>
                                        <option value="2">@lang('lang.infix_clasified')</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('lang.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>

            <div class="row mt-40">
                

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-20">@lang('lang.purchase_list')</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                    		<div class="white-box">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                              
                                    <tr>
                                        <th>@lang('lang.sl') @lang('lang.no').</th> 
                                        <th>@lang('lang.package')</th>
                                        <th>@lang('lang.purchase_date')</th>
                                        <th>@lang('lang.expaire_date')</th>
                                        <th>@lang('lang.price')</th>
                                        <th>@lang('lang.paid_amount')</th>
                                        <th>@lang('lang.due_amount')</th>
                                        <th>@lang('lang.action')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($ProductPurchase as $p)
                                    <tr>
                                        <td>{{ @$p->id}}</td>
                                        <td>{{ @$p->package}}</td> 
                                        <td>{{ @$p->purchase_date}}</td> 
                                        <td>{{ @$p->expaire_date}}</td> 
                                        <td>{{ @$p->price}}</td> 
                                        <td>{{ @$p->paid_amount}}</td> 
                                        <td>{{ @$p->due_amount}}</td> 
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    Edit
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">@lang('lang.view')</a>
                                                    <a class="dropdown-item" href="#">@lang('lang.download')</a> 
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            
    </div>
</section>
  

@endsection
