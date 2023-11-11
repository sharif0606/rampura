@extends('layout.app')

@section('pageTitle',trans('Create Sales'))
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
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.sales.store')}}">
                            @csrf
                            <div class="row">
                                @if( currentUser()=='owner')
                                    <div class="col-md-2 mt-2">
                                        <label for="branch_id" class="float-end" ><h6>{{__('Branches Name')}}<span class="text-danger">*</span></h6></label>
                                    </div>
                                    <div class="col-md-4 form-group">
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
                                    </div>
                                    
                                @else
                                    <input type="hidden" value="{{ branch()['branch_id']}}" name="branch_id" id="branch_id">
                                @endif
                                
                                    
                                <div class="col-md-2 mt-2">
                                    <label for="customrName" class="float-end"><h6>{{__('Customer')}}<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <select class="choices form-select" name="customerName" id="customerName" onchange="$('#customer_r_name').val($(this).find('option:selected').text())">
                                        <option value="">Select Customer</option>
                                        @forelse($customers as $d)
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('customerName')==$d->id?"selected":""}}> {{ $d->customer_name}}-[{{ $d->upazila?->name}}]</option>
                                        @empty
                                            <option value="">No Data found</option>
                                        @endforelse
                                    </select>
                                    @if($errors->has('customerName'))
                                        <span class="text-danger"> {{ $errors->first('customerName') }}</span>
                                    @endif
                                    <input type="hidden" name="customer_r_name" id="customer_r_name">
                                </div>
                                


                                <div class="col-md-2 mt-2">
                                    <label for="warehouse_id" class="float-end"><h6>{{__('Warehouse')}}<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id">
                                        
                                        @forelse($Warehouses as $d)
                                            <option class="brnch brnch{{$d->branch_id}}" value="{{$d->id}}" {{ old('warehouse_id')==$d->id?"selected":""}}> {{ $d->name}}</option>
                                        @empty
                                            <option value="">No Data found</option>
                                        @endforelse
                                    </select>
                                    @if($errors->has('warehouse_id'))
                                        <span class="text-danger"> {{ $errors->first('warehouse_id') }}</span>
                                    @endif 
                                </div>
                                
                                

                                <div class="col-md-2 mt-2">
                                    <label for="date" class="float-end"><h6>{{__('Date')}}<span class="text-danger">*</span></h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="datepicker" class="form-control" value="{{ old('sales_date')}}" name="sales_date" placeholder="dd/mm/yyyy" required>
                                    @if($errors->has('sales_date'))
                                        <span class="text-danger"> {{ $errors->first('sales_date') }}</span>
                                    @endif
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
    
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-warning">
                                                <th colspan="5" class="py-2 px-1 text-center">Total</th>
                                                <th class="py-2 px-1 total_bag"></th>
                                                <th class="py-2 px-1 total_quantity"></th>
                                                <th class="py-2 px-1 total_less"></th>
                                                <th class="py-2 px-1 total_actual_quantity"></th>
                                                <th class="py-2 px-1"></th>
                                                <th class="py-2 px-1 total_sale_amount"></th>
                                                <th class="py-2 px-1"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 mt-3">
                                    <div><h5>ADD FOR SALES:</h5></div>
                                    <table class="tbl_expense" style="width:100%;">
                                        <tbody id="expense">
                                            <tr class="tbl_expense text-center">
                                                <th class="tbl_expense">Expense Head</th>
                                                <th class="tbl_expense">Lc Number</th>
                                                <th class="tbl_expense">Sign</th>
                                                <th colspan="2" class="tbl_expense">Amount</th>
                                            </tr>
                                            <tr class="tbl_expense">
                                                <td class="tbl_expense">
                                                    <select name="child_two_id[]" class="form-select">
                                                        <option value="">select</option>
                                                        @forelse ($childTow as $ex)
                                                            <option value="child_twos~{{$ex->id}}~{{$ex['head_name']}}~{{$ex['head_code']}}">{{$ex->head_name}}</option>
                                                        @empty
                                                            <option value="">No Data Found</option>
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no[]" placeholder="Lc Number"><span class="error-message" style="color: red; display: none;"></span></td>
                                                <td class="tbl_expense">
                                                    <select name="sign_for_calculate[]" class="form-select">
                                                        <option value="+" {{old('sign_for_calculate[]')== '+'?'selected':''}}>(+)</option>
                                                        <option value="-" {{old('sign_for_calculate[]')== '-'?'selected':''}}>(-)</option>
                                                    </select>
                                                </td>
                                                <td class="tbl_expense"><input type="number" onkeyup="total_expense(this)" class="form-control expense_value text-end" name="cost_amount[]"></td>
                                                <td class="tbl_expense text-primary text-center" onClick='addRow();' style="width: 3%;"><i style="font-size: 1.5rem;" class="bi bi-plus-square-fill"></i></td>
                                            </tr>
                                                
                                        </tbody>
                                        <tfoot>
                                            <tr class="tbl_expense">
                                                <th colspan="3" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL RECEIVABLE AMOUNT</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tgrandtotal" >0.00</h5>
                                                    <input type="hidden" name="tgrandtotal" class="tgrandtotal_p">
                                                    <input type="hidden"  class="sub_total">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div><h5>Payment:</h5></div>
                                    <table class="tbl_expense" style="width:100%;">
                                        <tbody id="payment">
                                            <tr class="tbl_expense text-center">
                                                <th class="tbl_expense">Payment Type</th>
                                                <th class="tbl_expense">Lc Number</th>
                                                <th colspan="2" class="tbl_expense"> Amount</th>
                                            </tr>
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
                                                <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no_payment[]" placeholder="Lc Number"><span class="error-message" style="color: red; display: none;"></span></td>
                                                <td class="tbl_expense"><input type="number" onkeyup="payment(this)" class="form-control pay_value text-end" name="pay_amount[]"></td>
                                                <td class="tbl_expense text-primary text-center" onClick='addPaymentRow();' style="width: 3%;"><i style="font-size: 1.5rem;" class="bi bi-plus-square-fill"></i></td>
                                            </tr>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL AMOUNT</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tgrandtotal" >0.00</h5>
                                                    <input type="hidden" name="total_pay_amount" class="tgrandtotal_p">
                                                </td>
                                            </tr>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL PAYMENT</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tpayment" >0.00</h5>
                                                    <input type="hidden" name="total_payment" class="tpayment_p" value="0">
                                                </td>
                                            </tr>
                                            <tr class="tbl_expense">
                                                <th colspan="2" class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL DUE</h5></th>
                                                <td class="tbl_expense text-end" >
                                                    <h5 class="tdue" >0.00</h5>
                                                    <input type="hidden" name="total_due" class="tdue_p">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="" class="form-label"><b>Note</b></label>
                                    <textarea class="form-control" name="note" rows="3">{{old('note')}}</textarea>
                                </div>
                            </div>
                            
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">{{__('Save')}}</button>
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

  total_expense();
  payment();
  total_calculate();
}
//END
// function check_product_qty(e){
//     var lot_no = $(e).closest('tr').find('.lot_no').val().trim(); 
// }
//CALCUALATED SALES PRICE
function get_cal(e){
    // return check_product_qty(e)
  var quantity_bag = (isNaN(parseFloat($(e).closest('tr').find('.qty_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_bag').val().trim()); 
  var stock_bag = (isNaN(parseFloat($(e).closest('tr').find('.stock_bag').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.stock_bag').val().trim()); 
  var quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.qty_kg').val().trim()); 
  var less_quantity_kg = (isNaN(parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.less_qty_kg').val().trim()); 
  var rate_in_kg = (isNaN(parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.rate_in_kg').val().trim()); 
  var stock = (isNaN(parseFloat($(e).closest('tr').find('.stockqty').val().trim()))) ? 0 :parseFloat($(e).closest('tr').find('.stockqty').val().trim());

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
  
  var actualQuantity= ((quantity_kg - less_quantity_kg));
  var amount = ((actualQuantity * rate_in_kg));

  
  
  $(e).closest('tr').find('.actual_qty').val(actualQuantity);
  $(e).closest('tr').find('.amount').val(amount);

  total_expense();
  payment();
  total_calculate();
}

//row reapeter
function addRow(){

    var row=`<tr class="tbl_expense">
            <td class="tbl_expense">
                <select required name="child_two_id[]" class="form-select">
                    <option value="">select</option>
                    @forelse ($childTow as $ex)
                        <option value="child_twos~{{$ex->id}}~{{$ex['head_name']}}~{{$ex['head_code']}}">{{$ex->head_name}}</option>
                    @empty
                        <option value="">No Data Found</option>
                    @endforelse
                </select>
            </td>
            <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no[]" placeholder="Lc Number" required><span class="error-message" style="color: red; display: none;"></span></td>
            <td class="tbl_expense">
                <select name="sign_for_calculate[]" class="form-select">
                    <option value="+" {{old('sign_for_calculate[]')== '+'?'selected':''}}>(+)</option>
                    <option value="-" {{old('sign_for_calculate[]')== '-'?'selected':''}}>(-)</option>
                </select>
            </td>
            <td class="tbl_expense"><input type="number" onkeyup="total_expense(this)" class="form-control expense_value text-end" name="cost_amount[]"></td>
            <td class="tbl_expense text-danger text-center" onClick='RemoveRow(this);' style="width: 3%;"><i style="font-size: 1.5rem;" class="bi bi-trash"></i></td>
        </tr>`;
    $('#expense').append(row);
}

function addPaymentRow(){

var row=`<tr class="tbl_expense">
            <td class="tbl_expense">
                <select required  class="form-control form-select" name="payment_head[]">
                    @if($paymethod)
                        @foreach($paymethod as $d)
                            <option value="{{$d['table_name']}}~{{$d['id']}}~{{$d['head_name']}}~{{$d['head_code']}}">{{$d['head_name']}}-{{$d['head_code']}}</option>
                        @endforeach
                    @endif
                </select>
            </td>
            <td class="tbl_expense"><input type="text" onblur="checking_lc_no(this)" class="form-control" name="lc_no_payment[]" placeholder="Lc Number" required><span class="error-message" style="color: red; display: none;"></span></td>
            <td class="tbl_expense"><input type="number" onkeyup="payment(this)" class="form-control pay_value text-end" name="pay_amount[]"></td>
            <td class="tbl_expense text-danger text-center " onClick='RemoveRow(this);' style="width: 3%;"><i style="font-size: 1.5rem;" class="bi bi-trash"></i></td>
        </tr>`;
    $('#payment').append(row);
}
function addBagRow(button) {
    var modal = $(button).closest('.modal');
    var lotNoValue = modal.find('input[name="bag_lot_no[]"]').val();
    var newRow = `<div class="row">
                    <div class="col-2">
                        <label for="lot_no" class="form-label">Lot Number</label>
                        <input type="text" class="form-control" value="${lotNoValue}" name="bag_lot_no[]" readonly>
                    </div>
                    <div class="col-2">
                        <label for="bagno" class="form-label">Bag No</label>
                        <input type="text" class="form-control" name="bag_no[]" placeholder="bag no">
                    </div>
                    <div class="col-3">
                        <label for="bagno" class="form-label">Quantity Kg</label>
                        <input type="text" class="form-control" name="quantity_detail[]" placeholder="quantity">
                    </div>
                    <div class="col-3">
                        <label for="bagno" class="form-label">Comment</label>
                        <input type="text" class="form-control" name="bag_comment[]" placeholder="comment">
                    </div>
                    <div class="col-2 text-start" style="margin-top: 1.9rem;">
                        <span class="text-primary pe-2"><i style="font-size: 1.3rem;" onclick="addBagRow(this)" class="bi bi-plus-square-fill"></i></span>
                        <span class="text-danger"><i style="font-size: 1.3rem;" onclick="removeBagRow(this)" class="bi bi-dash-square-fill"></i></span>
                    </div>
                </div>`;

    // Append the new row to the modal's bagRow container
    modal.find('#bagRow').append(newRow);
}


function removeBagRow(button) {
    // Find the row associated with the clicked remove button and remove it
    $(button).closest('.row').remove();
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
    
    var salesTotal = 0;
    $('.amount').each(function() {
        salesTotal += parseFloat($(this).val());
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

    var grandTotal=((subTotal+salesTotal));
    var totalDue = (grandTotal - totalPayment);

    // Display the sum in the specified element
    $('.total_bag').text(bagTotal.toFixed(2));
    $('.total_quantity').text(quantityTotal.toFixed(2));
    $('.total_less').text(lessTotal.toFixed(2));
    $('.total_actual_quantity').text(actualTotal.toFixed(2));
    $('.total_sale_amount').text(salesTotal.toFixed(2));
    $('.tgrandtotal').text(grandTotal.toFixed(2));
    $('.tgrandtotal_p').val(grandTotal.toFixed(2));
    $('.tdue').text(totalDue.toFixed(2));
    $('.tdue_p').val(totalDue.toFixed(2));
    
}

//END
</script>
<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
