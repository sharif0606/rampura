@extends('layout.app')
@section('pageTitle',trans('Dashboard'))

@section('content')
@php
    $purCount = 0;
    $purAmount = 0;
    $todayPurAmount = 0;
@endphp

<div class="page-content py-3">
    <section class="row">
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-aqua">
                <i class="bi bi-bag icon"></i>
                </span>
                <div class="info-box-content">
                    @php
                        $purAmount = $totalPurchaseAmount + $totalRegularPurchaseAmount + $totalBeparianPurchaseAmount;
                    @endphp
                    <span class="text-bold text-uppercase">Total Purchase</span><br>
                    <span class="info-box-number">৳  {{money_format($purAmount)}}</span>
                </div>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow">
                <i class="bi bi-currency-dollar icon"></i>
                </span>
                <div class="info-box-content">
                    <span class="text-bold text-uppercase">Total Sales Due</span>
                    <span class="info-box-number">৳  0.00</span>
                </div>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-green">
                <i class="bi bi-cart icon"></i>
                </span>
                <div class="info-box-content">
                    <span class="text-bold text-uppercase">Total Sales Amount</span><br>
                    <span class="info-box-number">৳  {{money_format($totalSaleAmount)}}</span>
                </div>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-red">
                <i class="bi bi-dash-square icon"></i>
                </span>
                <div class="info-box-content">
                    <span class="text-bold text-uppercase">Total Expense Amount</span>
                    <span class="info-box-number">৳  0.00</span>
                </div>
            </div>
       </div>
    </section>
    <section class="row">
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-aqua">
                <i class="bi bi-bag icon"></i>
                </span>
                <div class="info-box-content">
                    @php
                        $todayPurAmount = $todayPurchaseAmount + $todayRegularPurchaseAmount + $todayBeparianPurchaseAmount;
                    @endphp
                    <span class="text-bold text-uppercase">Todays Total Purchase</span><br>
                    <span class="info-box-number">৳  {{money_format($todayPurAmount)}}</span>
                </div>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow">
                <i class="bi bi-currency-dollar icon"></i>
                </span>
                <div class="info-box-content">
                    <span class="text-bold text-uppercase">Today Payment Received(Sales)</span>
                    <span class="info-box-number">৳  0.00</span>
                </div>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-green">
                <i class="bi bi-cart icon"></i>
                </span>
                <div class="info-box-content">
                    <span class="text-bold text-uppercase">Todays Total Sales</span><br>
                    <span class="info-box-number">৳  {{money_format($todayTotalSaleAmount)}}</span>
                </div>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-red">
                <i class="bi bi-dash-square icon"></i>
                </span>
                <div class="info-box-content">
                    <span class="text-bold text-uppercase">Todays Total Expense</span>
                    <span class="info-box-number">৳  0.00</span>
                </div>
            </div>
       </div>
    </section>
    <section class="row">
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="small-box bg-dream-pink">
               <div class="inner text-uppercase">
                    <h3>{{$customer}}</h3>
                    <p>Customers</p>
               </div> 
               <div class="icon">
                <i class="bi bi-people-fill"></i>
               </div>
               <a href="{{route(currentUser().'.customer.index')}}" class="small-box-footer text-uppercase">View
                <i class="bi bi-arrow-right-circle"></i>
               </a>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="small-box bg-dream-purple">
               <div class="inner text-uppercase">
                    <h3>{{$supplier}}</h3>
                    <p>Suppliers</p>
               </div> 
               <div class="icon">
                <i class="bi bi-people-fill"></i>
               </div>
               <a href="{{route(currentUser().'.supplier.index')}}" class="small-box-footer text-uppercase">View
                <i class="bi bi-arrow-right-circle"></i>
               </a>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="small-box bg-dream-maroon">
               <div class="inner text-uppercase">
                @php
                    $purCount = $totalPurchase + $totalBeparianPurchase + $totalRegularPurchase;
                @endphp
                    <h3>{{money_format($purCount)}}</h3>
                    <p>Purchase Invoice</p>
               </div> 
               <div class="icon">
                <i class="bi bi-receipt"></i>
               </div>
               <a href="{{route(currentUser().'.purchase.index')}}" class="small-box-footer text-uppercase">View
                <i class="bi bi-arrow-right-circle"></i>
               </a>
            </div>
       </div>
       <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="small-box bg-dream-green">
               <div class="inner text-uppercase">
                    <h3>{{$totalSale}}</h3>
                    <p>Sales Invoice</p>
               </div> 
               <div class="icon">
                <i class="bi bi-receipt"></i>
               </div>
               <a href="{{route(currentUser().'.sales.index')}}" class="small-box-footer text-uppercase">View
                <i class="bi bi-arrow-right-circle"></i>
               </a>
            </div>
       </div>
    </section>
</div>
@endsection

@push('scripts')

<!-- Need: Apexcharts -->
<script src="{{ asset('/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('/assets/js/pages/dashboard.js') }}"></script>
@endpush