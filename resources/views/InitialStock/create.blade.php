@extends('layout.app')

@section('pageTitle',trans('Create Initial Stock'))
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
.tbl_expense{
    border: 1px solid;
    border-collapse: collapse;
}
</style>
    <section id="multiple-column-form">
        <div class="match-height">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.initialStock.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                @if( currentUser()=='owner')
                                                    <th style="width: 10%; padding-bottom: .7rem;">   
                                                        <label for="branch_id" ><h6>Branches Name<span class="text-danger">*</span></h6></label>
                                                    </th>
                                                    <td style="widht: 40%; padding-bottom: .7rem;">
                                                        <select required onchange="change_data(this.value)" class="form-control form-select" name="branch_id" id="branch_id">
                                                            @forelse($branches as $b)
                                                                <option value="{{ $b->id }}" {{old('branch_id')==$b->id?'selected':''}}>{{ $b->name }}</option>
                                                            @empty
                                                                <option value="">No branch found</option>
                                                            @endforelse          
                                                        </select>  
                                                        @if($errors->has('branch_id'))
                                                        <span class="text-danger"> {{ $errors->first('branch_id') }}</span>
                                                        @endif    
                                                    </td>
                                                @else
                                                    <th colspan="2"><input type="hidden" value="{{ branch()['branch_id']}}" name="branch_id"></th> 
                                                @endif
                                                <th style="width: 10%; padding-bottom: .7rem;">
                                                    <label for="supplierName"><h6>Supplier<span class="text-danger">*</span></h6></label>
                                                </th>
                                                <td style="width: 40%; padding-bottom: .7rem;">
                                                    <select class="form-control choices form-select" name="supplierName" id="supplierName" onchange="$('#supplier_r_name').val($(this).find('option:selected').text())">
                                                        <option value="">Select Supplier</option>
                                                        @forelse($suppliers as $d)
                                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('supplierName')==$d->id?"selected":""}}> {{ $d->supplier_name}}-({{ $d->upazila?->name}})</option>
                                                        @empty
                                                            <option value="">No Supplier found</option>
                                                        @endforelse
                                                    </select>
                                                        @if($errors->has('supplierName'))
                                                            <span class="text-danger"> {{ $errors->first('supplierName') }}</span>
                                                        @endif 
                                                    <input type="hidden" name="supplier_r_name" id="supplier_r_name">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 10%;">
                                                    <label for="warehouse_id"><h6>Warehouse<span class="text-danger">*</span></h6></label>
                                                </th>
                                                <td style="width: 40%;">
                                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id">
                                                        <option value="">Select Warehouse</option>
                                                        @forelse($Warehouses as $d)
                                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('warehouse_id')==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                        @empty
                                                            <option value="">No Warehouse found</option>
                                                        @endforelse
                                                    </select>
                                                    @if($errors->has('warehouse_id'))
                                                        <span class="text-danger"> {{ $errors->first('warehouse_id') }}</span>
                                                    @endif 
                                                </td>
                                                <th style="width: 10%;">
                                                    <label for="date"><h6>Date<span class="text-danger">*</span></h6></label>
                                                </th>
                                                <td style="width: 40%;">
                                                    <input type="text" id="datepicker" class="form-control" value="{{ old('purchase_date')}}" name="purchase_date" placeholder="dd/mm/yyyy" required>
                                                    @if($errors->has('purchase_date'))
                                                        <span class="text-danger"> {{ $errors->first('purchase_date') }}</span>
                                                    @endif 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                            <tr class="bg-primary text-white text-center">
                                                <th class="py-2 px-1" >Description of Goods</th>
                                                <th class="py-2 px-1" >Lot/Lc No</th>
                                                <th class="py-2 px-1" >Trade Mark</th>
                                                <th class="py-2 px-1" >Quantity Bag</th>
                                                <th class="py-2 px-1" >Quantity kg</th>
                                                <th class="py-2 px-1" >Less/Discount Kg</th>
                                                <th class="py-2 px-1" >Actual Quantity</th>
                                                <th class="py-2 px-1" >Rate in Per Kg</th>
                                                <th class="py-2 px-1" >Total Amount</th>
                                                <th class="py-2 px-1">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details_data">
    
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-warning">
                                                <th colspan="3" class="py-2 px-1 text-center">Total</th>
                                                <th class="py-2 px-1 total_bag"></th>
                                                <th class="py-2 px-1 total_quantity"></th>
                                                <th class="py-2 px-1 total_less"></th>
                                                <th class="py-2 px-1 total_actual_quantity"></th>
                                                <th class="py-2 px-1"></th>
                                                <th class="py-2 px-1 total_pur_amount"></th>
                                                <th class="py-2 px-1"></th>
                                                <input type="hidden" class="grandTotal" name="tgrandtotal" value="">
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-8 mt-2 pe-2 text-end">
                                    <label for="" class="form-group"><h5>PER KG EXPENSE/COSTING:</h5></label> 
                                </div>
                                <div class="col-4 mt-2 text-start">
                                    <label for="" class="form-group"><h5 class="perKgCost">0.00</h5></label>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                // console.log(res);
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
  total_calculate();
}

//CALCUALATED SALES PRICE
function get_cal(e){
  var quantity_bag = (isNaN(parseFloat($(e).closest('tr').find('.qty_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_bag').val().trim()); 
  var quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_kg').val().trim()); 
  var less_quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()); 
  var rate_in_kg = (isNaN(parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim());

  var actual_quantity = ((quantity_kg - less_quantity_kg));
  var amount = ((actual_quantity * rate_in_kg));


  $(e).closest('tr').find('.actual_qty').val(actual_quantity);
  $(e).closest('tr').find('.amount').val(amount);


    //   console.log('expense:', purExpense);
  console.log('actual_quantity:', actual_quantity);
  console.log('amount:', amount);

  total_calculate();
}

function get_amount(e){
  var amount = (isNaN(parseFloat($(e).closest('tr').find('.amount').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.amount').val().trim()); 
  var actual_quantity = (isNaN(parseFloat($(e).closest('tr').find('.actual_qty').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.actual_qty').val().trim());
  
  var rate = ((amount / actual_quantity));


  $(e).closest('tr').find('.rate_in_kg').val(rate);
  total_calculate();
}

function total_calculate() {
    
    var purChaseTotal = 0;
    $('.amount').each(function() {
        purChaseTotal += parseFloat($(this).val());
    });

    var bagTotal = 0;
    $('.qty_bag').each(function() {
        bagTotal += parseFloat($(this).val());
    });

    var quantityTotal = 0;
    $('.qty_kg').each(function() {
        quantityTotal += parseFloat($(this).val());
    });

    var lessTotal = 0;
    $('.less_qty_kg').each(function() {
        lessTotal += parseFloat($(this).val());
    });

    var actualTotal = 0;
    $('.actual_qty').each(function() {
        actualTotal += parseFloat($(this).val());
    });

    var per_kg_costing = (purChaseTotal/actualTotal);

    // Display the sum in the specified element
    $('.total_bag').text(bagTotal.toFixed(2));
    $('.total_quantity').text(quantityTotal.toFixed(2));
    $('.total_less').text(lessTotal.toFixed(2));
    $('.total_actual_quantity').text(actualTotal.toFixed(2));
    $('.total_pur_amount').text(purChaseTotal.toFixed(2));
    $('.grandTotal').val(purChaseTotal.toFixed(2));
    $('.perKgCost').text(per_kg_costing.toFixed(2));
    
}

</script>
<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush