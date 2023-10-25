<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Models\Products\Product;
use App\Models\Products\Category;
use App\Models\Products\Subcategory;
use App\Models\Products\Childcategory;
use App\Models\Products\Brand;
use App\Models\Products\Unit;
use Illuminate\Http\Request;
use App\Http\Requests\Product\AddRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Exception;
use DB;
use DNS1D;
use DNS2D;

class ProductController extends Controller
{
    use ResponseTrait,ImageHandleTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::where(company());
        if($request->name)
            $products=$products->where('product_name','like','%'.$request->name.'%');

        $products=$products->paginate(15);
        return view('product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where(company())->get();
        $subcategories = Subcategory::where(company())->get();
        $childcategories = Childcategory::where(company())->get();
        $brands = Brand::where(company())->get();
        $units = Unit::all();
        return view('product.create',compact('categories','subcategories','childcategories','brands','units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddRequest $request)
    {
        try{
            $p= new Product;
            $p->bar_code=company()['company_id'].time();
            $p->category_id=$request->category;
            $p->subcategory_id=$request->subcategory;
            $p->childcategory_id=$request->childcategory;
            $p->brand_id=$request->brand_id;
            $p->unit_id=$request->unit_id;
            $p->product_name=$request->productName;
            $p->description=$request->description;
            $p->price=$request->price;
            $p->purchase_price=$request->purchase_price;
            
            $p->company_id=company()['company_id'];
            $p->status=1;
            if($request->has('image'))
                $p->image=$this->resizeImage($request->image,'images/product/'.company()['company_id'],true,200,200,false);

            if($p->save())
                return redirect()->route(currentUser().'.product.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::where(company())->get();
        $subcategories = Subcategory::where(company())->get();
        $childcategories = Childcategory::where(company())->get();
        $brands = Brand::where(company())->get();
        $units = Unit::all();
        $product= Product::findOrFail(encryptor('decrypt',$id));
        return view('product.edit',compact('categories','subcategories','childcategories','brands','units','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $p= Product::findOrFail(encryptor('decrypt',$id));
            $p->category_id=$request->category;
            $p->subcategory_id=$request->subcategory;
            $p->childcategory_id=$request->childcategory;
            $p->brand_id=$request->brand_id;
            $p->unit_id=$request->unit_id;
            $p->product_name=$request->productName;
            $p->description=$request->description;
            $p->price=$request->price;
            $p->purchase_price=$request->purchase_price;
            if($request->has('image')){
                if($p->image){
                    if($this->deleteImage($p->image,'images/product/'.company()['company_id'])){
                        $p->image=$this->resizeImage($request->image,'images/product/'.company()['company_id'],true,200,200,false);
                    }
                }else{
                    $p->image=$this->resizeImage($request->image,'images/product/'.company()['company_id'],true,200,200,false);
                }
            }

            $p->company_id=company()['company_id'];
            $p->status=1;
            if($p->save())
                return redirect()->route(currentUser().'.product.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat= Product::findOrFail(encryptor('decrypt',$id));
        $cat->delete();
        return redirect()->back();
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function label(Request $request)
    {
        $company_id=company()['company_id'];
        $where="where products.company_id={$company_id}";
        if($request->item_name)
            $where.=" and products.product_name like '%{$request->item_name}%'";
        $stock= DB::select("SELECT products.*,stocks.*,sum(stocks.quantity) as qty, AVG(stocks.unit_price) as avunitprice FROM `stocks` join products on products.id=stocks.product_id $where GROUP BY stocks.product_id");
        return view('product.label',compact('stock'));
    }

    public function barcodepreview(Request $request){
        $company_id=company()['company_id'];
        if($request->checkall)
            $productlist=DB::select("SELECT p.*,stocks.*,sum(stocks.quantity) as stock FROM `products` as p JOIN stocks on stocks.product_id=p.id where p.company_id=$company_id order by p.product_name");
        else
            $productlist=DB::select("SELECT p.*,stocks.*,sum(stocks.quantity) as stock FROM `products` as p JOIN stocks on stocks.product_id=p.id WHERE p.id in (".implode(',',$request->datas).") order by p.product_name");
        
        $barcode="<div class='row'>";
        $barcode.='<div class="text-center"><button type="button" class="btn btn-primary" onclick="print_label('.'\'a4\','.'\'barcode\''.')"><i class="fas fa-print"></i> A4</button>';
        $barcode.='<button type="button" class="ms-2 btn btn-primary" onclick="print_label('.'\'single\','.'\'barcode\''.')"><i class="fas fa-print"></i> Single</button></div>';
		if($productlist){
            foreach($productlist as $ps){
                for($i=0; $i<$ps->stock;$i++){
                    $barcode.="<div class='col-6 text-center px-1 py-1'>";
                    $barcode.="<div class='fw-bold'>$ps->product_name</div>";
                    $barcode.="<div class='fw-bold fs-4'>$ps->price</div>";
                    $barcode.="<div class='inside_center w-100'>".DNS1D::getBarcodeHTML("$ps->bar_code", 'C128',1,25)."</div>";
                    $barcode.="<div class='text-center'>$ps->bar_code</div>";
                    $barcode.="</div>";
                }
            }
        }
		$barcode.="</div>";
        echo json_encode($barcode);
    }

    public function qrcodepreview(Request $request){
        $company_id=company()['company_id'];
        if($request->checkall)
            $productlist=DB::select("SELECT p.*,stocks.*,sum(stocks.quantity) as stock FROM `products` as p JOIN stocks on stocks.product_id=p.id where p.company_id=$company_id order by p.product_name group by p.id");
        else
            $productlist=DB::select("SELECT p.*,stocks.*,sum(stocks.quantity) as stock FROM `products` as p JOIN stocks on stocks.product_id=p.id WHERE p.id in (".implode(',',$request->datas).")  group by p.id order by p.product_name");
    
        $barcode="<div class='row'>";
        $barcode.='<div class="text-center"><button type="button" class="btn btn-primary" onclick="print_label('.'\'a4\','.'\'qrcode\''.')"><i class="fas fa-print"></i> A4</button>';
        $barcode.='<button type="button" class="ms-2 btn btn-primary" onclick="print_label('.'\'single\','.'\'qrcode\''.')"><i class="fas fa-print"></i> Single</button></div>';
        
		if($productlist){
            foreach($productlist as $ps){
                for($i=0; $i<$ps->stock;$i++){
                    $barcode.="<div class='col-6 text-center px-1 py-1'>";
                    $barcode.="<div class='fw-bold'>$ps->product_name</div>";
                    $barcode.="<div class='fw-bold fs-4'>$ps->price</div>";
                    $barcode.="<div class='inside_center w-100'>".DNS2D::getBarcodeHTML("$ps->bar_code", 'QRCODE',5,4)."</div>";
                    $barcode.="<div class='text-center'>$ps->bar_code</div>";
                    $barcode.="</div>";
                }
            }
        }
		$barcode.="</div>";
        echo json_encode($barcode);
    }

    public function labelPrint(Request $request){
        $company_id=company()['company_id'];
        if($request->checkall)
            $productlist=DB::select("SELECT p.*,stocks.*,sum(stocks.quantity) as stock FROM `products` as p JOIN stocks on stocks.product_id=p.id where p.company_id=$company_id order by p.product_name");
        else
            $productlist=DB::select("SELECT p.*,stocks.*,sum(stocks.quantity) as stock FROM `products` as p JOIN stocks on stocks.product_id=p.id WHERE p.id in (".implode(',',$request->datas).") order by p.product_name");
    
        if($request->ptype=="a4" && $request->ltype=="barcode"){
            $barcode="<div>";
            if($productlist){
                foreach($productlist as $ps){
                    for($i=0; $i<$ps->stock;$i++){
                        $barcode.="<div style='page-break-inside: avoid;width:150px; height:122px; float:left;text-align:center;padding:10px;'>";
                        $barcode.="<div style='font-weight:bold'>$ps->product_name</div>";
                        $barcode.="<div style='font-weight:bold;font-size:26px;'>$ps->price</div>";
                        $barcode.="<center style='text-align:center'>"."<img width='100%' src='data:image/png;base64," . DNS1D::getBarcodePNG("$ps->bar_code", 'C128') . "' alt='barcode'   /></center>";
                        $barcode.="<div style='text-align:center'>$ps->bar_code</div>";
                        $barcode.="</div>";
                    }
                }
            }
            $barcode.="</div>";
            
        }else if($request->ptype=="single" && $request->ltype=="barcode"){
            $barcode="<div>";
            if($productlist){
                foreach($productlist as $ps){
                    for($i=0; $i<$ps->stock;$i++){
                        $barcode.="<div style='page-break-inside: avoid;width:100%; height:122px; text-align:center;padding:10px;'>";
                        $barcode.="<div style='font-weight:bold'>$ps->product_name</div>";
                        $barcode.="<div style='font-weight:bold;font-size:26px;'>$ps->price</div>";
                        $barcode.="<center style='text-align:center'>"."<img style='max-width:90%; width:auto' src='data:image/png;base64," . DNS1D::getBarcodePNG("$ps->bar_code", 'C128') . "' alt='barcode'   /></center>";
                        $barcode.="<div style='text-align:center'>$ps->bar_code</div>";
                        $barcode.="</div>";
                    }
                }
            }
            $barcode.="</div>";
        }else if($request->ptype=="a4" && $request->ltype=="qrcode"){
            $i=0;
            $barcode="<div style='display: flex;flex-wrap: wrap;'>";
            if($productlist){
                foreach($productlist as $i=>$ps){
                    for($i=0; $i<$ps->stock;$i++){
                        $barcode.="<div style='page-break-inside: avoid;width:0.5in; height:1.5in; flex: 1 0 14%;text-align:center;padding:10px;'>";
                        $barcode.="<div style='font-weight:bold;font-size:16px;'>$ps->product_name</div>";
                        $barcode.="<div style='font-size:22px;'>$ps->price</div>";
                        $barcode.="<center style='text-align:center'>"."<img style='max-width:90%; width:auto' src='data:image/png;base64," . DNS2D::getBarcodePNG("$ps->bar_code", 'QRCODE',5,4) . "' alt='barcode'   /></center>";
                        $barcode.="<div style='text-align:center'>$ps->bar_code</div>";
                        $barcode.="</div>";
                    }
                }
            }
            $barcode.="</div>";
            
        }else if($request->ptype=="single" && $request->ltype=="qrcode"){
            $barcode="<div>";
            if($productlist){
                foreach($productlist as $ps){
                    for($i=0; $i<$ps->stock;$i++){
                        $barcode.="<div style='page-break-inside: avoid;margin-top:15px;width:100%; height:122px; text-align:center;padding:10px;'>";
                        $barcode.="<div style='font-weight:bold;font-size:16px;'>$ps->product_name</div>";
                        $barcode.="<div style='font-size:22px;'>$ps->price</div>";
                        $barcode.="<center style='text-align:center'>"."<img style='max-width:90%; width:auto' src='data:image/png;base64," . DNS2D::getBarcodePNG("$ps->bar_code", 'QRCODE',5,4) . "' alt='barcode'   /></center>";
                        $barcode.="<div style='text-align:center'>$ps->bar_code</div>";
                        $barcode.="</div>";
                    }
                }
            }
            $barcode.="</div>";
        }

        echo json_encode($barcode);
    }
}
