@extends('layout.app')

@section('pageTitle',trans('Create Beparian Purchase'))
@section('pageSubTitle',trans('Create'))
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
body{
    font-size: 11px !important;
}
.form-control{
    font-size: 11px !important;
}
</style>
    <section id="multiple-column-form">
        <div class="match-height">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.bpurchase.store')}}">
                            @csrf
                            <div class="row">
                                @if( currentUser()=='owner')
                                    <div class="col-md-2 mt-2">
                                        <label for="branch_id" class="float-end" ><h6>Branches Name<span class="text-danger">*</span></h6></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select required onchange="change_data(this.value)" class="form-control form-select" name="branch_id" id="branch_id">
                                            {{-- <option value="">Select Branches</option>     --}}
                                        @forelse($branches as $b)
                                                <option value="{{ $b->id }}" {{old('branch_id')==$b->id?'selected':''}}>{{ $b->name }}</option>
                                            @empty
                                                <option value="">No branch found</option>
                                            @endforelse          
                                        </select>      
                                    </div>
                                    @if($errors->has('branch_id'))
                                        <span class="text-danger"> {{ $errors->first('branch_id') }}</span>
                                    @endif
                                    
                                @else
                                    <input type="hidden" value="{{ branch()['branch_id']}}" name="branch_id">
                                @endif
                                
                                    
                                <div class="col-md-2 mt-2">
                                    <label for="supplierName" class="float-end"><h6>Supplier<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    
                                    <select required class="form-control form-select" name="supplierName" id="supplierName">
                                        <option value="">Select Supplier</option>
                                        @forelse($suppliers as $d)
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('supplierName')==$d->id?"selected":""}}> {{ $d->supplier_name}}</option>
                                        @empty
                                            <option value="">No Supplier found</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                @if($errors->has('supplierName'))
                                <span class="text-danger"> {{ $errors->first('supplierName') }}</span>
                                @endif


                                <div class="col-md-2 mt-2">
                                    <label for="warehouse_id" class="float-end"><h6>Warehouse<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id">
                                        <option value="">Select Warehouse</option>
                                        @forelse($Warehouses as $d)
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('warehouse_id')==$d->id?"selected":""}}> {{ $d->name}}</option>
                                        @empty
                                            <option value="">No Warehouse found</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                @if($errors->has('warehouse_id'))
                                    <span class="text-danger"> {{ $errors->first('warehouse_id') }}</span>
                                @endif 
                                

                                <div class="col-md-2 mt-2">
                                    <label for="date" class="float-end"><h6>Date<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="datepicker" class="form-control" value="{{ old('purchase_date')}}" name="purchase_date" placeholder="dd/mm/yyyy" required>
                                </div>


                                <div class="col-md-2 mt-2">
                                    <label for="reference_no" class="float-end"><h6>Reference Number</h6></label>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input type="text" class="form-control" value="{{ old('reference_no')}}" name="reference_no">
                                </div>
                            </div>
                            <div class="row m-3">
                                <div class="col-8 offset-2">
                                    <input type="text" name="" id="item_search" class="form-control  ui-autocomplete-input" placeholder="Search Product">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 tbl-scroll">
                                    <table class="table mb-5">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th class="py-2 px-1" data-title="Description of Goods">Des.of.goods</th>
                                                <th class="py-2 px-1" data-title="Lot no/ Lc no">Lot/Lc No</th>
                                                <th class="py-2 px-1" data-title="Trade Marek/ Brand">Brand</th>
                                                <th class="py-2 px-1" data-title="Quantity Bag">Qty Bag</th>
                                                <th class="py-2 px-1" data-title="Quantity kg">Qty Kg</th>
                                                <th class="py-2 px-1" data-title="Less Quantity kg">L.Qty Kg</th>
                                                <th class="py-2 px-1" data-title="Actual Quantity">A.Quantity</th>
                                                <th class="py-2 px-1" data-title="Discount in Kg" >Dis.kg</th>
                                                <th class="py-2 px-1" data-title="Rate in kg">Rate Kg</th>
                                                <th class="py-2 px-1" >Amount</th>
                                                <th class="py-2 px-1" data-title="Purchase Commission">P.Com</th>
                                                <th class="py-2 px-1" data-title="Transport Cost">Tr.Cost</th>
                                                <th class="py-2 px-1" data-title="Unloading Cost">Un.Cost</th>
                                                <th class="py-2 px-1" data-title="Sales income per bag(2tk)">S.income.per.bag</th>
                                                <th class="py-2 px-1" data-title="Total Amount">Total Amount</th>
                                                <th class="py-2 px-1" data-title="Price Per Kg">P.KG</th>
                                                <th class="py-2 px-1">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details_data">
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="row mb-5">
                                <div class="col-8 mt-2 pe-2 text-end">
                                    <label for="" class="form-group"><h6>Grand Total</h6></label> 
                                </div>
                                <div class="col-4 mt-2 pe-5 text-end">
                                    <label for="" class="form-group"><h6 class="tgrandtotal">0.00</h6></label>
                                    <input type="hidden" name="tgrandtotal" class="tgrandtotal_p">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                </div>
                            </div>
                        </form>
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
            
            $.ajax({
            autoFocus:true,
                url: "{{route(currentUser().'.pur.product_search')}}",
                method: 'GET',
                dataType: 'json',
                data: {
                    name: data.term
                },
                success: function(res){
                console.log(res);
                    var result;
                    result = [{label: 'No Records Found ',value: ''}];
                    if (res.length) {
                        result = $.map(res, function(el){
                            return {
                                label: el.value +'--'+ el.label,
                                value: '',
                                id: el.id,
                                item_name: el.value
                            };
                        });
                    }

                    cb(result);
                },error: function(e){
                    console.log("error "+e);
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
                if(isNaN(ui.content[0].id)){
                    return;
                }
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
    $.ajax({
            autoFocus:true,
                url: "{{route(currentUser().'.pur.product_search_data')}}",
                method: 'GET',
                dataType: 'json',
                data: {
                    item_id: item_id
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
}

//CALCUALATED SALES PRICE
function get_cal(e){
  var quantity_bag = (isNaN(parseFloat($(e).closest('tr').find('.qty_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_bag').val().trim()); 
  var quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_kg').val().trim()); 
  var less_quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()); 
  var rate_in_kg = (isNaN(parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()); 
  var purchase_commission = (isNaN(parseFloat($(e).closest('tr').find('.purchase_commission').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.purchase_commission').val().trim()); 
  var transport_cost = (isNaN(parseFloat($(e).closest('tr').find('.transport_cost').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.transport_cost').val().trim()); 
  var unloading_cost = (isNaN(parseFloat($(e).closest('tr').find('.unloading_cost').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.unloading_cost').val().trim()); 

  var sales_income_per_bag = ((quantity_bag * 2));
  var actual_quantity = ((quantity_kg - less_quantity_kg));
  var cost = ((purchase_commission + transport_cost + unloading_cost + sales_income_per_bag));
  var amount = ((actual_quantity * rate_in_kg));
  var total_amount = ((amount - cost));
  var pricePerKg = ((total_amount/actual_quantity));

  $(e).closest('tr').find('.sales_income_per_bag').val(sales_income_per_bag);
  $(e).closest('tr').find('.actual_qty').val(actual_quantity);
  $(e).closest('tr').find('.amount').val(amount);
  $(e).closest('tr').find('.total_amount').val(total_amount);
  $(e).closest('tr').find('.price_per_kg').text(pricePerKg.toFixed(2));

  console.log('sales_income_per_bag:', sales_income_per_bag);
  console.log('actual_quantity:', actual_quantity);
  console.log('amount:', amount);
  console.log('total_amount:', total_amount);

  total_calculate();
}

function total_calculate() {
    // ... existing code ...

    // Calculate the sum of total_amount values
    var grandtotal = 0;
    $('.total_amount').each(function() {
        grandtotal += parseFloat($(this).val());
    });

    // Display the sum in the specified element
    $('.tgrandtotal').text(grandtotal.toFixed(2));
    $('.tgrandtotal_p').val(grandtotal.toFixed(2));
    
}



</script>
<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
