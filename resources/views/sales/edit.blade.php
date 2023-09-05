@extends('layout.app')

@section('pageTitle',trans('Update Sales'))
@section('pageSubTitle',trans('update'))
@push("styles")
<link rel="stylesheet" href="{{ asset('assets/css/main/full-screen.css') }}">
@endpush

@section('content')
<style>
@media screen and (max-width: 800px) {
    .tbl-scroll {
        overflow: scroll;
    }
}
.tbl_expense{
    border: 1px solid;
    border-collapse: collapse;
}
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.sales.update',encryptor('encrypt',$sales->id))}}">
                            @csrf
                            @method('patch')
                            <div class="row">
                                @if( currentUser()=='owner')
                                    <div class="col-md-2 mt-2">
                                        <label for="branch_id" class="float-end" ><h6>{{__('Branches Name')}}<span class="text-danger">*</span></h6></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select required onchange="change_data(this.value)" class="form-control form-select" name="branch_id" id="branch_id">
                                            @forelse($branches as $b)
                                                <option value="{{ $b->id }}" {{old('branch_id',$sales->branch_id)==$b->id?'selected':''}}>{{ $b->name }}</option>
                                            @empty
                                                <option value="">No branch found</option>
                                            @endforelse          
                                        </select>      
                                    </div>
                                    @if($errors->has('branch_id'))
                                        <span class="text-danger"> {{ $errors->first('branch_id') }}</span>
                                    @endif
                                    
                                @else
                                    <input type="hidden" value="{{ branch()['branch_id']}}" name="branch_id" id="branch_id">
                                @endif
                                
                                    
                                <div class="col-md-2 mt-2">
                                    <label for="customrName" class="float-end"><h6>{{__('Customer')}}<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    
                                    <select required class="form-control form-select" name="customerName" id="customerName">
                                        <option value="">Select Customer</option>
                                        @forelse($customers as $d)
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('customerName',$sales->customer_id)==$d->id?"selected":""}}> {{ $d->customer_name}}</option>
                                        @empty
                                            <option value="">No Data found</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                @if($errors->has('customerName'))
                                <span class="text-danger"> {{ $errors->first('customerName') }}</span>
                                @endif


                                <div class="col-md-2 mt-2">
                                    <label for="warehouse_id" class="float-end"><h6>{{__('Warehouse')}}<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id">
                                        
                                        @forelse($Warehouses as $d)
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('warehouse_id',$sales->warehouse_id )==$d->id?"selected":""}}> {{ $d->name}}</option>
                                        @empty
                                            <option value="">No Data found</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                @if($errors->has('warehouse_id'))
                                    <span class="text-danger"> {{ $errors->first('warehouse_id') }}</span>
                                @endif 
                                

                                <div class="col-md-2 mt-2">
                                    <label for="date" class="float-end"><h6>{{__('Date')}}<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="datepicker" class="form-control" value="{{ old('sales_date',$sales->sales_date)}}" name="sales_date" placeholder="dd/mm/yyyy" required>
                                </div>
                            </div>
                            <div class="row m-3">
                                <div class="col-8 offset-2">
                                    <input type="text" name="" id="item_search" class="form-control  ui-autocomplete-input" placeholder="Search Product">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 tbl-scroll">
                                    <table class="table mb-2">
                                        <thead>
                                            <tr class="bg-primary text-white text-center">
                                                <th class="py-2 px-1" >Description of Goods</th>
                                                <th class="py-2 px-1" >Lot/Lc no</th>
                                                <th class="py-2 px-1" >Trade Mark</th>
                                                <th class="py-2 px-1" >Stock Total Bag</th>
                                                <th class="py-2 px-1" >Stock Total Kg</th>
                                                <th class="py-2 px-1" >Quantity Bag</th>
                                                <th class="py-2 px-1" >Quantity Kg</th>
                                                <th class="py-2 px-1" >Less/Discount Kg</th>
                                                <th class="py-2 px-1" >Actual Quantity Kg</th>
                                                <th class="py-2 px-1" >Rate in Kg</th>
                                                <th class="py-2 px-1" >Amount</th>
                                                <th class="py-2 px-1">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details_data">

                                            @forelse ($salesDetails as $p)
                                            <tr class="productlist text-center">
                                                <td class="p-2">{{$p->productName}}
                                                    <input name="product_id[]" type="hidden" value="{{$p->product_id}}" class="product_id_list">
                                                    <input name="stockqty[]" type="hidden" value="{{$p->qty+$p->quantity_kg}}" class="stockqty">
                                                    <input name="batch_id[]" type="hidden" value="{{$p->batch_id}}" class="batch_id_list">
                                                </td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="lot_no[]" type="text" value="{{$p->lot_no}}" class="form-control lot_no"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="brand[]" type="text" value="{{$p->brand}}" class="form-control brand"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" type="text" value="{{$p->bag_qty+$p->quantity_bag}}" class="form-control stock_bag" disabled></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" type="text" value="{{$p->qty+$p->quantity_kg}}" class="form-control" disabled></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_bag[]" type="text" value="{{$p->quantity_bag}}" class="form-control qty_bag"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_kg[]" type="text" value="{{$p->quantity_kg}}" class="form-control qty_kg"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="less_qty_kg[]" type="text" class="form-control less_qty_kg"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="actual_qty[]" readonly type="text" class="form-control actual_qty" value="0"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="rate_in_kg[]" type="text" class="form-control rate_in_kg" value="{{$p->rate_kg}}"></td>
                                                <td class="py-2 px-1"><input name="amount[]" readonly type="text" class="form-control amount" value="{{$p->amount}}"></td>
                                                <td class="py-2 px-1 text-danger"><i style="font-size:1.7rem" onclick="removerow(this)" class="bi bi-dash-circle-fill"></i></td>
                                            </tr>
                                            @empty
                                            <tr class="text-center">
                                                <td colspan="12">No Data Found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info me-1 mb-1">{{__('Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- jQuery UI library -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>
    function change_data(e){
        $('.brnch').hide();
        $('.brnch'+e).show();
    }

</script>

<script>



$(function() {
    $("#item_search").bind("paste", function(e){
        $("#item_search").autocomplete('search');
    } );
    $("#item_search").autocomplete({
        source: function(data, cb){
          let branch_id=$('#branch_id').val();
          let warehouse_id=$('#warehouse_id').val();
          let batch_id="";
          $(".productlist").each(function(){
            batch_id+='"'+$(this).find(".batch_id_list").val()+'",';
          })
          //alert(batch_id);
            $.ajax({
            autoFocus:true,
                url: "{{route(currentUser().'.sales.product_sc')}}",
                method: 'GET',
                dataType: 'json',
                data: {
                    name: data.term,branch_id:branch_id,warehouse_id:warehouse_id,batch_id:batch_id
                },
                success: function(res){
                    console.log(res);
                    var result;
                    result = [{label: 'No Records Found ',value: ''}];
                    if (res.length) {
                        result = $.map(res, function(el){
                            return {
                                label: el.product_name+'-Lot_no/Lc_no-'+el.lot_no +' Brand-'+el.brand,
                                value: '',
                                id: el.id+"^"+el.lot_no+"^"+el.brand+"^"+el.batch_id,
                                item_name: el.product_name
                            };
                        });
                    }

                    cb(result);
                },error: function(e){
                    console.log(e);
                }
            });
        },

            response:function(e,ui){
            if(ui.content.length==1){
                $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                $(this).autocomplete("close");
            }
            //console.log(ui.content[0].id);
            },

            //loader start
            search: function (e, ui) {
            },
            select: function (e, ui) { 
                if(typeof ui.content!='undefined'){
                console.log("Autoselected first");
                // if(isNaN(ui.content[0].id)){
                //     return;
                // }
                var item_id=ui.content[0].id;
                }
                else{
                console.log("manual Selected");
                var item_id=ui.item.id;
                }

                return_row_with_data(item_id);
                $("#item_search").val('');
            },   
            //loader end
    });


});

function return_row_with_data(item_id){
    $("#item_search").addClass('ui-autocomplete-loader-center');
    let branch_id=$('#branch_id').val();
    let warehouse_id=$('#warehouse_id').val();
console.log(item_id)
    $.ajax({
        autoFocus:true,
        url: "{{route(currentUser().'.sales.product_sc_d')}}",
        method: 'GET',
        dataType: 'json',
        data: {
            item_id: item_id,branch_id:branch_id,warehouse_id:warehouse_id
        },
        success: function(res){
            $('#details_data').append(res);
            $("#item_search").val('');
            $("#item_search").removeClass('ui-autocomplete-loader-center');
        },error: function(e){
            console.log("error "+e);
        }
    });
	
}
//INCREMENT ITEM
function removerow(e){
  $(e).closest('tr').remove();
  total_calculate();
}
//END
//CALCUALATED SALES PRICE
function get_cal(e){
  var quantity_bag = (isNaN(parseFloat($(e).closest('tr').find('.qty_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_bag').val().trim()); 
  var stock_bag = (isNaN(parseFloat($(e).closest('tr').find('.stock_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.stock_bag').val().trim()); 
  var quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_kg').val().trim()); 
  var rate_in_kg = (isNaN(parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()); 
  var stock = (isNaN(parseFloat($(e).closest('tr').find('.stockqty').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.stockqty').val().trim()); 
  var sale_commission = (isNaN(parseFloat($(e).closest('tr').find('.sale_commission').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.sale_commission').val().trim()); 
  var transport_cost = (isNaN(parseFloat($(e).closest('tr').find('.transport_cost').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.transport_cost').val().trim()); 
  var labour_cost = (isNaN(parseFloat($(e).closest('tr').find('.labour_cost').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.labour_cost').val().trim()); 

  if(stock < quantity_kg){
    alert("You cannot sell more than "+stock);
    quantity_kg=stock;
    $(e).closest('tr').find('.qty_kg').val(stock)
  }

  if(stock_bag < quantity_bag){
    alert("You cannot sell more than "+stock_bag);
    quantity_bag=stock_bag;
    $(e).closest('tr').find('.qty_bag').val(quantity_bag)
  }

  var cost = ((sale_commission + transport_cost + labour_cost ));
  var amount = ((quantity_kg * rate_in_kg));
  var total_amount = ((amount + cost));

  
  
  $(e).closest('tr').find('.amount').val(amount);
  $(e).closest('tr').find('.total_amount').val(total_amount);
  total_calculate();
}
function total_calculate() {
    // Calculate the sum of total_amount values
    var grandtotal = 0;
    $('.total_amount').each(function() {
        grandtotal += parseFloat($(this).val());
    });

    var totalbag = 0;
    $('.qty_bag').each(function() {
        totalbag += parseFloat($(this).val());
    });

    var totalkg = 0;
    $('.qty_kg').each(function() {
        totalkg += parseFloat($(this).val());
    });

    var totalam = 0;
    $('.amount').each(function() {
        totalam += parseFloat($(this).val());
    });

    var totalsalcommission = 0;
    $('.sale_commission').each(function() {
        totalsalcommission += parseFloat($(this).val());
    });

    var totaltrncharge = 0;
    $('.transport_cost').each(function() {
        totaltrncharge += parseFloat($(this).val());
    });

    var totallabourcharge = 0;
    $('.labour_cost').each(function() {
        totallabourcharge += parseFloat($(this).val());
    });

    // Display the sum in the specified element
    $('.tgrandtotal_p').val(grandtotal.toFixed(2));
    $('.tgrandtotalP').text(grandtotal.toFixed(2));
    $('.total_bag').text(totalbag.toFixed(2));
    $('.total_qty_kg').text(totalkg.toFixed(2));
    $('.total_am').text(totalam.toFixed(2));
    $('.total_sale_commission').text(totalsalcommission.toFixed(2));
    $('.total_trn_charge').text(totaltrncharge.toFixed(2));
    $('.total_labour_charge').text(totallabourcharge.toFixed(2));
    
}

//END


</script>
<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
