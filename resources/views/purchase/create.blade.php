@extends('layout.app')

@section('pageTitle',trans('Create Purchase'))
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
                        <form class="form" method="post" action="{{route(currentUser().'.purchase.store')}}">
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
                                                            {{-- <option value="">Select Branches</option>     --}}
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
                                                    <select required class="form-control form-select" name="supplierName" id="supplierName">
                                                        <option value="">Select Supplier</option>
                                                        @forelse($suppliers as $d)
                                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('supplierName')==$d->id?"selected":""}}> {{ $d->supplier_name}}</option>
                                                        @empty
                                                            <option value="">No Supplier found</option>
                                                        @endforelse
                                                    </select>
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
                                                </td>
                                                <th style="width: 10%;">
                                                    <label for="date"><h6>Date<span class="text-danger">*</span></h6></label>
                                                </th>
                                                <td style="width: 40%;">
                                                    <input type="text" id="datepicker" class="form-control" value="{{ old('purchase_date')}}" name="purchase_date" placeholder="dd/mm/yyyy" required>
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
                                                <th class="py-2 px-1" >Trade Marek</th>
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
                                    </table>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 mt-3">
                                    <div><h5>TOTAL EXPENSES:</h5></div>
                                    <table class="tbl_expense" style="width:100%;">
                                        <tbody>
                                            @forelse ($childTow as $ex)
                                                @if($ex->head_code != 5322)
                                                    <tr class="tbl_expense">
                                                        <th class="tbl_expense" style="padding-left: 8px;">{{$ex->head_name}} <input type="hidden" name="child_two_id[]" value="{{$ex->id}}"></th>
                                                        <td class="tbl_expense" ><input type="number" onkeyup="total_expense(this)" class="form-control expense_value text-end" name="cost_amount[]" ></td>
                                                    </tr>
                                                @endif
                                                @if($ex->head_code == 5322)
                                                    <tr class="tbl_expense">
                                                        <th class="tbl_expense" style="padding-left: 8px;">{{$ex->head_name}} <input type="hidden" name="child_two_id[]" value="{{$ex->id}}"></th>
                                                        <td class="tbl_expense" ><input type="number" onkeyup="total_expense(this)" class="form-control expense_value text-end ltr_interest" name="cost_amount[]" readonly></td>
                                                    </tr>
                                                @endif
                                            @empty
                                                
                                            @endforelse
                                                <tr class="tbl_expense">
                                                    <th class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL EXPENSES</h5></th>
                                                    <td class="tbl_expense text-end" >
                                                        <h5 class="tgrandtotal" >0.00</h5>
                                                        <input type="hidden" name="tgrandtotal" class="tgrandtotal_p">
                                                        <input type="hidden"  class="sub_total">
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="row mb-1">
                                <div class="col-8 mt-2 pe-2 text-end">
                                    <label for="" class="form-group"><h5>PER KG EXPENSE/COSTING:</h5></label> 
                                </div>
                                <div class="col-4 mt-2 text-start">
                                    <label for="" class="form-group"><h5 class="perKgCost">0.00</h5></label>
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
  total_expense();
  total_calculate();
}

//CALCUALATED SALES PRICE
function get_cal(e){
  var quantity_bag = (isNaN(parseFloat($(e).closest('tr').find('.qty_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_bag').val().trim()); 
  var quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_kg').val().trim()); 
  var less_quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()); 
  var rate_in_kg = (isNaN(parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim());
  
 
  var actual_quantity = ((quantity_kg - less_quantity_kg));
  var amount = ((quantity_kg * rate_in_kg));


  $(e).closest('tr').find('.actual_qty').val(actual_quantity);
  $(e).closest('tr').find('.amount').val(amount);


//   console.log('expense:', purExpense);
  console.log('actual_quantity:', actual_quantity);
  console.log('amount:', amount);

  total_expense();
  total_calculate();
}

function total_expense(e) {
    var grandExpense = 0;
    $('.expense_value').each(function() {
        grandExpense += parseFloat($(this).val()) || 0;
    });

    $(".sub_total").val(grandExpense.toFixed(2));

    total_calculate();
}

function total_calculate() {
    var subTotal=(isNaN(parseFloat($('.sub_total').val().trim()))) ? 0 :parseFloat($('.sub_total').val().trim());
    

    // Calculate the sum of total_amount values
    
    var purChaseTotal = 0;
    $('.amount').each(function() {
        purChaseTotal += parseFloat($(this).val());
    });

    var actualTotal = 0;
    $('.actual_qty').each(function() {
        actualTotal += parseFloat($(this).val());
    });

    var grandTotal=((subTotal+purChaseTotal));
    var per_kg_costing = (grandTotal/actualTotal);

    // Display the sum in the specified element
    $('.perKgCost').text(per_kg_costing.toFixed(2));
    $('.tgrandtotal').text(grandTotal.toFixed(2));
    $('.tgrandtotal_p').val(grandTotal.toFixed(2));
    
}

</script>
<script>
function Availability(inputField) {
    var lc = inputField.value;
    $.ajax({
        url: '{{route(currentUser().'.checkLcNo')}}',
        type: 'GET',
        data: { lc_no: lc },
        dataType: 'json',
        success: function(response) {
            if (response.data) {
            var ltrInterest = response.data.dr;
            $('.ltr_interest').val(ltrInterest);
            } else {
                $('.ltr_interest').val('');
            }
            total_expense();
        },
        
        error: function(xhr, status, error) {
            console.log(error); // Handle the error if needed
        }
    });
}
</script>
<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
