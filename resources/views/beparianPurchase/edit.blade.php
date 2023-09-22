@extends('layout.app')

@section('pageTitle',trans('Update Beparian Purchase'))
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
        <div class="match-height">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.bpurchase.update',encryptor('encrypt',$purchase->id))}}">
                            @csrf
                            @method('patch')
                            <div class="row">
                                @if( currentUser()=='owner')
                                    <div class="col-md-2 mt-2">
                                        <label for="branch_id" class="float-end" ><h6>Branches Name<span class="text-danger">*</span></h6></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select required onchange="change_data(this.value)" class="form-control form-select" name="branch_id" id="branch_id">
                                            {{-- <option value="">Select Branches</option>     --}}
                                        @forelse($branches as $b)
                                                <option value="{{ $b->id }}" {{old('branch_id',$purchase->branch_id)==$b->id?'selected':''}}>{{ $b->name }}</option>
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
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('supplierName',$purchase->supplier_id)==$d->id?"selected":""}}> {{ $d->supplier_name}}</option>
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
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('warehouse_id',$purchase->warehouse_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
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
                                    <input type="text" id="datepicker" class="form-control" value="{{ old('purchase_date',$purchase->purchase_date)}}" name="purchase_date" placeholder="dd/mm/yyyy" required>
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
                                            @php
                                                $actualQtyTotal = 0;
                                            @endphp
                                            <?php $firstBatchId = optional($purchase->stock)->first()->batch_id; ?>
                                            <input type="hidden" name="batch_id" value="{{$firstBatchId}}">
                                        <tbody id="details_data">
                                            @forelse ($purchaseDetails as $p)
                                            <tr class="text-center">
                                                <td class="py-2 px-1"><input type="hidden" name="product_id[]" value="{{$p->product_id}}">{{$p->product?->product_name}}</td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="lot_no[]" type="text" value="{{$p->lot_no}}" class="form-control lot_no"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="brand[]" type="text" value="{{$p->brand}}" class="form-control brand"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_bag[]" type="text" value="{{$p->quantity_bag}}" class="form-control qty_bag"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_kg[]" type="text" value="{{$p->quantity_kg}}" class="form-control qty_kg"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="less_qty_kg[]" type="text" value="{{$p->less_quantity_kg}}" class="form-control less_qty_kg"></td>
                                                <td class="py-2 px-1"><input name="actual_qty[]" readonly type="text" class="form-control actual_qty" value="{{$p->actual_quantity}}"></td>
                                                <td class="py-2 px-1"><input onkeyup="get_cal(this)" name="rate_in_kg[]" type="text" class="form-control rate_in_kg" value="{{$p->rate_kg}}"></td>
                                                <td class="py-2 px-1"><input name="amount[]" readonly type="text" class="form-control amount" value="{{$p->amount}}"></td>
                                                <td class="py-2 px-1 text-danger"><i style="font-size:1.7rem" onclick="removerow(this)" class="bi bi-dash-circle-fill"></i></td>
                                            </tr>
                                            @php
                                                $actualQtyTotal += $p->actual_quantity;
                                            @endphp
                                            @empty
                                            <tr class="text-center">
                                                <td colspan="10">No Data Found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 mt-3">
                                    <div><h5>TOTAL EXPENSES:</h5></div>
                                    <table class="tbl_expense" style="width:100%;">
                                        <tbody id="expense">
                                            <tr class="tbl_expense text-center">
                                                <th class="tbl_expense">Expense Head</th>
                                                <th class="tbl_expense">Lc Number</th>
                                                <th colspan="2" class="tbl_expense">Cost Amount</th>
                                            </tr>
                                            @forelse ($expense as $item)
                                                <tr class="tbl_expense">
                                                    <td class="tbl_expense">
                                                        <select name="child_two_id[]" class="form-select">
                                                            <option value="">select</option>
                                                            @forelse ($childTow as $ex)
                                                                <option value="{{$ex->id}}" {{old('child_two_id',$item->child_two_id)==$ex->id?'selected':''}}>{{$ex->head_name}}</option>
                                                            @empty
                                                                <option value="">No Data Found</option>
                                                            @endforelse
                                                        </select>
                                                    </td>
                                                    <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no[]" placeholder="Lc Number" value="{{$item->lot_no}}"><span class="error-message" style="color: red; display: none;"></span></td>
                                                    <td class="tbl_expense"><input type="text" onkeyup="total_expense(this)" class="form-control expense_value text-end" name="cost_amount[]" value="{{$item->cost_amount}}"></td>
                                                    <td class="tbl_expense text-center fs-4" style="width: 3%;">
                                                        <span class="text-primary" onClick='addRow();'><i class="bi bi-plus-square-fill"></i></span>
                                                        <span class="text-danger" onClick='RemoveRow(this);'><i class="bi bi-trash"></i></span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="tbl_expense">
                                                    <td class="tbl_expense">
                                                        <select name="child_two_id[]" class="form-select">
                                                            <option value="">select</option>
                                                            @forelse ($childTow as $ex)
                                                                <option value="{{$ex->id}}">{{$ex->head_name}}</option>
                                                            @empty
                                                                <option value="">No Data Found</option>
                                                            @endforelse
                                                        </select>
                                                    </td>
                                                    <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no[]" placeholder="Lc Number" value=""><span class="error-message" style="color: red; display: none;"></span></td>
                                                    <td class="tbl_expense"><input type="text" onkeyup="total_expense(this)" class="form-control expense_value text-end" name="cost_amount[]" value=""></td>
                                                    <td class="tbl_expense text-center fs-4" style="width: 3%;">
                                                        <span class="text-primary" onClick='addRow();'><i class="bi bi-plus-square-fill"></i></span>
                                                        <span class="text-danger" onClick='RemoveRow(this);'><i class="bi bi-trash"></i></span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL EXPENSES</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tgrandtotal" style=" padding-right: 10px;">{{$purchase->grand_total}}</h5>
                                                    <input type="hidden" name="tgrandtotal" class="tgrandtotal_p" value="{{$purchase->grand_total}}">
                                                    <input type="hidden"  class="sub_total">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>


                            <div class="row mb-1">
                                <div class="col-8 mt-2 pe-2 text-end">
                                    <label for="" class="form-group"><h5>PER KG EXPENSE/COSTING:</h5></label> 
                                </div>
                                <div class="col-4 mt-2 text-start">
                                    <label for="" class="form-group">
                                        @php
                                            $pricePerKg = $purchase->grand_total / $actualQtyTotal;
                                            $formattedPricePerKg = number_format($pricePerKg, 2);
                                        @endphp
                                        <h5 class="perKgCost">{{$formattedPricePerKg}}</h5>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div><h5>Payment:</h5></div>
                                    <table class="tbl_expense" style="width:100%;">
                                        <tbody id="payment">
                                            <tr class="tbl_expense text-center">
                                                <th class="tbl_expense">Payment Type</th>
                                                <th class="tbl_expense">Lc Number</th>
                                                <th colspan="2" class="tbl_expense"> Amount</th>
                                            </tr>
                                            @forelse ($supplierPaymentDetails as $spd)
                                                <tr class="tbl_expense">
                                                    <td class="tbl_expense">
                                                        <select  class="form-control form-select" name="payment_head[]">
                                                            @if($paymethod)
                                                                @foreach($paymethod as $d)
                                                                    <option value="{{$d['table_name']}}~{{$d['id']}}~{{$d['head_name']}}~{{$d['head_code']}}" {{old('payment_head',$spd->p_table_id)== $d['id']?'selected':''}}>{{$d['head_name']}}-{{$d['head_code']}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no_payment[]" value="{{$spd->lc_no}}" placeholder="Lc Number"><span class="error-message" style="color: red; display: none;"></span></td>
                                                    <td class="tbl_expense"><input type="text" onkeyup="payment(this)" class="form-control pay_value text-end" name="pay_amount[]" value="{{$spd->amount}}"></td>
                                                    <td class="tbl_expense text-primary text-center fs-4" style="width: 3%;">
                                                        <span class="text-primary" onClick='addPaymentRow();'><i class="bi bi-plus-square-fill"></i></span>
                                                        <span class="text-danger" onClick='RemoveRow(this);'><i class="bi bi-trash"></i></span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="tbl_expense">
                                                    <td class="tbl_expense">
                                                        <select  class="form-control form-select" name="payment_head[]">
                                                            @if($paymethod)
                                                                @foreach($paymethod as $d)
                                                                    <option value="{{$d['table_name']}}~{{$d['id']}}~{{$d['head_name']}}~{{$d['head_code']}}">{{$d['head_name']}}-{{$d['head_code']}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no_payment[]" value="" placeholder="Lc Number"><span class="error-message" style="color: red; display: none;"></span></td>
                                                    <td class="tbl_expense"><input type="text" onkeyup="payment(this)" class="form-control pay_value text-end" name="pay_amount[]" value=""></td>
                                                    <td class="tbl_expense text-primary text-center fs-4" style="width: 3%;">
                                                        <span class="text-primary" onClick='addPaymentRow();'><i class="bi bi-plus-square-fill"></i></span>
                                                        <span class="text-danger" onClick='RemoveRow(this);'><i class="bi bi-trash"></i></span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL AMOUNT</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tgrandtotal" >{{$supplerPayment->total_amount}}</h5>
                                                    <input type="hidden" name="total_pay_amount" class="tgrandtotal_p" value="{{$supplerPayment->total_amount}}">
                                                </td>
                                            </tr>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL PAYMENT</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tpayment" >{{$supplerPayment->total_payment}}</h5>
                                                    <input type="hidden" name="total_payment" class="tpayment_p" value="{{$supplerPayment->total_payment}}">
                                                </td>
                                            </tr>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL DUE</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tdue" >{{$supplerPayment->total_due}}</h5>
                                                    <input type="hidden" name="total_due" class="tdue_p" value="{{$supplerPayment->total_due}}" >
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info me-1 mb-1">Update</button>
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
    total_expense(); // call this to get subtotal
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
  total_expense();
  payment();
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
  payment();
  total_calculate();
}
//row reapeter
function addRow(){

var row=`<tr class="tbl_expense">
            <td class="tbl_expense">
                <select name="child_two_id[]" class="form-select">
                    <option value="">select</option>
                    @forelse ($childTow as $ex)
                        <option value="{{$ex->id}}">{{$ex->head_name}}</option>
                    @empty
                        <option value="">No Data Found</option>
                    @endforelse
                </select>
            </td>
            <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no[]" placeholder="Lc Number" required><span class="error-message" style="color: red; display: none;"></span></td>
            <td class="tbl_expense"><input type="number" onkeyup="total_expense(this)" class="form-control expense_value text-end" name="cost_amount[]" required></td>
            <td class="tbl_expense text-danger text-center" onClick='RemoveRow(this);' style="width: 3%;"><i style="font-size: 1.5rem;" class="bi bi-trash"></i></td>
        </tr>`;
    $('#expense').append(row);
}

function addPaymentRow(){

var row=`<tr class="tbl_expense">
            <td class="tbl_expense">
                <select  class="form-control form-select" name="payment_head[]">
                    @if($paymethod)
                        @foreach($paymethod as $d)
                            <option value="{{$d['table_name']}}~{{$d['id']}}~{{$d['head_name']}}~{{$d['head_code']}}">{{$d['head_name']}}-{{$d['head_code']}}</option>
                        @endforeach
                    @endif
                </select>
            </td>
            <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no_payment[]" placeholder="Lc Number" required><span class="error-message" style="color: red; display: none;"></span></td>
            <td class="tbl_expense"><input type="number" onkeyup="payment(this)" class="form-control pay_value text-end" name="pay_amount[]" required></td>
            <td class="tbl_expense text-danger text-center" onClick='RemoveRow(this);' style="width: 3%;"><i style="font-size: 1.5rem;" class="bi bi-trash"></i></td>
        </tr>`;
    $('#payment').append(row);
}

function RemoveRow(e) {
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('tr').remove();
        
        total_expense();
        payment();
        total_calculate();
    }
}
//row reapeter
function checking_lc_no(input) {
    var lcNumber = input.value.trim();
    var lotNumbers = [];

    $('.lot_no').each(function() {
        var lotNumber = $(this).val().trim();
        if (lotNumber !== '') {
            lotNumbers.push(lotNumber);
        }else{
            alert("Please insert LC number into the product.");
         }
    });

    // Check if the lcNumber matches any lotNumber
    var isMatched = lotNumbers.includes(lcNumber);
    // Get the error message element associated with this input
    var errorMessage = $(input).next('.error-message');

    if (!isMatched) {
        input.value = ''; // Clear the input value
        errorMessage.text('No matches found').css('color', 'red').show();
    } else {
        errorMessage.hide();
    }
}

function total_expense(e) {
    var grandExpense = 0;
    $('.expense_value').each(function() {
        grandExpense += parseFloat($(this).val()) || 0;
    });

    $(".sub_total").val(grandExpense.toFixed(2));

    payment();
    total_calculate();
}
function payment(e) {
    var t_payment = 0;
    $('.pay_value').each(function() {
        t_payment += parseFloat($(this).val()) || 0;
    });

    $(".tpayment").text(t_payment.toFixed(2));
    $(".tpayment_p").val(t_payment.toFixed(2));

    total_calculate();
}

function total_calculate() {
    var subTotal=(isNaN(parseFloat($('.sub_total').val().trim()))) ? 0 :parseFloat($('.sub_total').val().trim());
    var totalPayment=(isNaN(parseFloat($('.tpayment_p').val().trim()))) ? 0 :parseFloat($('.tpayment_p').val().trim());
    

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
    var totalDue = (grandTotal - totalPayment);
    var per_kg_costing = (grandTotal/actualTotal);

    // Display the sum in the specified element
    $('.perKgCost').text(per_kg_costing.toFixed(2));
    $('.tgrandtotal').text(grandTotal.toFixed(2));
    $('.tgrandtotal_p').val(grandTotal.toFixed(2));
    $('.tdue').text(totalDue.toFixed(2));
    $('.tdue_p').val(totalDue.toFixed(2));
    
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
