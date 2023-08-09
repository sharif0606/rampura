@extends('layout.app')

@section('pageTitle',trans('Product Label'))
@section('pageSubTitle',trans('Label'))
<style>

.inside_center div{margin:0 auto;}
.labeldata{
    max-height: 400px;
    overflow-y: auto;
}
</style>
@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="get" action="">
                                <div class="row">
                                    <div class="col-6 ">
                                        <input type="text" value="{{isset($_GET['item_name'])?$_GET['item_name']:''}}" name="item_name" id="item_search" class="form-control  ui-autocomplete-input" placeholder="Search Product">
                                    </div>

                                    <div class="col-2 text-end me-0 pe-0">
                                        <button type="submit" class="btn btn-info mb-1 btn-block">{{__("Search")}}</button>
                                    </div>
                                    <div class="col-2 pe-0 justify-content-end">
                                        <button type="button" onclick="preview_lavel('qrcode')" class="btn btn-info me-1 mb-1 btn-block"> <i class="bi bi-qr-code"></i> {{__('QRCode')}}</button>
                                    </div>
                                    <div class="col-2 pe-0 justify-content-end">
                                        <button type="button" onclick="preview_lavel('barcode')" class="btn btn-info me-1 mb-1 btn-block"><i class="bi bi-upc"></i> {{__('Barcode')}}</button>
                                    </div>
                                </div>
                            </form>
                               
                                <div class="row">
                                    <div class="col-8">
                                        <table class="table mb-5">
                                            <thead>
                                                <tr class="bg-primary text-white text-center">
                                                    <th class="p-2">{{__('#SL')}}</th>
                                                    <th class="p-2">{{__('Product Name')}}</th>
                                                    <th class="p-2">{{__('Quantity')}}</th>
                                                    <th class="p-2">{{__('Bar Code')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              
                                                @forelse($stock as $s)
                                                <tr class="text-center">
                                                    <th scope="row"><input class="get_data" value="{{$s->product_id}}" type="checkbox"></th>
                                                    <td>{{$s->product_name}}</td>
                                                    <td>{{$s->quantity}}</td>
                                                    <td>{{$s->bar_code}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <th colspan="4" class="text-center">No data Found</th>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-4 labeldata">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function preview_lavel(type){
            var get_data=new Array();
            $('.get_data').each(function(){
                if($(this).is(":checked"))
                    get_data.push($(this).val());
            });

            var checkall=$('.checkall').is(":checked")?1:0;

            if(type=="qrcode")
                var url="{{route(currentUser().'.qrcodepreview')}}";
            else
                var url="{{route(currentUser().'.barcodepreview')}}";

            $.ajax({
                'url': url,
                'type': 'GET',
                'dataType' : 'json',
                'data': {datas:get_data,checkall:checkall},
                success: function(response){ // What to do if we succeed
                    console.log(response);
                    $('.labeldata').html(response);
                },
                error: function(response){
                    console.log(response);
                }
            });
        }

        function print_label(ptype,ltype){
            var get_data=new Array();
            $('.get_data').each(function(){
                if($(this).is(":checked"))
                    get_data.push($(this).val());
            });

            var checkall=$('.checkall').is(":checked")?1:0;
            $.ajax({
                'url': "{{route(currentUser().'.labelprint')}}",
                'type': 'GET',
                'dataType' : 'json',
                'data': {datas:get_data,checkall:checkall,ptype:ptype,ltype:ltype},
                success: function(response){ // What to do if we succeed
                    var divContents = response;
                    var a = window.open('', '', 'height=700, width=800');
                    a.document.write('<html>');
                    a.document.write('<body >');
                    a.document.write(divContents);
                    a.document.write('</body></html>');
                    a.document.close();
                    a.onload=function(){
                        a.print();
                    };
                },
                error: function(response){
                    console.log(response);
                }
            });
        }
    </script>

@endpush