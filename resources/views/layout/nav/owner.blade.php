<ul class="menu">
    {{-- <div class="sidebar-search">
        <input type="text" class="form-control" id="menuSearch" placeholder="Search menu..." oninput="searchMenu()" style="border: 1px solid #75b7f3;">
        <div id="searchSuggestions" class="search-suggestions"></div>
    </div> --}}
    <li class="sidebar-item">
        <a href="{{route(currentUser().'.dashboard')}}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>{{__('dashboard') }}</span>
        </a>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-cart-fill"></i><span>{{__('Sales')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.index')}}">{{__('Sales List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.create')}}">{{__('New Sales')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.cash')}}">{{__('Cash Sales')}}</a></li>
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_pending_exp')}}">{{__('Pending Expense')}}</a></li> controller and view can be deleted --}} 
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_pending_pay')}}">{{__('Pending Payment')}}</a></li> --}}
		</ul>   
    </li>

    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-cart-plus-fill"></i><span>{{__('Purchases')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.purchase.index')}}">{{__('Purchase')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.bpurchase.index')}}">{{__('Beparian Purchase')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.rpurchase.index')}}">{{__('Regular Purchase')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.pur_pending_exp')}}">{{__('Pending Expense')}}</a></li>
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.pur_pending_pay')}}">{{__('Pending Payment')}}</a></li> --}}
		</ul>   
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-receipt"></i><span>{{__('Voucher')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.credit.index')}}">{{__('Receive/Cr Voucher')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.debit.index')}}">{{__('Payment/Dr Voucher')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.journal.index')}}">{{__('Journal Voucher')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.purchase_voucher.index')}}">{{__('Purchase Voucher')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_voucher.index')}}">{{__('Sales Voucher')}}</a></li>
        </ul>
    </li>
    
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-people-fill"></i></i><span>{{__('Customer')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.customer.index')}}">{{__('Customer List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.customer.create')}}">{{__('New Customer')}}</a></li>
		</ul>   
    </li>

    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-people-fill"></i></i><span>{{__('Supplier')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.supplier.index')}}">{{__('Supplier List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.supplier.create')}}">{{__('New Supplier')}}</a></li>
		</ul>   
    </li>
    
    
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-box-fill"></i><span>{{__('Products')}}</span>
        </a>
        <ul class="submenu">
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'> {{__('Category')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.category.index')}}">{{__('Category List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.category.create')}}">{{__('New Category')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Sub Category')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.subcategory.index')}}">{{__('Sub Category List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.subcategory.create')}}">{{__('New Sub Category')}}</a></li>
                </ul>
            </li>
            {{-- <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Child Category')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.childcategory.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.childcategory.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li> --}}
            {{-- <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Brand')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.brand.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.brand.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li> --}}
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Products')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.product.index')}}">{{__('Product List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.product.create')}}">{{__('New Product')}}</a></li>
                </ul>
            </li>
            {{-- <li><a href="{{route(currentUser().'.plabel')}}" >{{__('Product Label')}}</a></li> --}}
		</ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-card-checklist"></i><span>{{__('Report')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.purchase_report')}}" >{{__('Purchase Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.beparian_report')}}" >{{__('Beparian Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.regular_report')}}" >{{__('Regular Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.all_pur_report')}}" >{{__('Purchase All')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.salreport')}}" >{{__('Sales Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.salreport_account')}}" >{{__('Sales Report With AC')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sreport')}}" >{{__('Stock Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.srota')}}" >{{__('Srota')}}</a></li>
            <li class="text-bold py-2 border-bottom ">Accounts Report</li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.incomeStatement')}}">{{__('Income Statement')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.statement_report')}}">{{__('Statement')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.headreport')}}" >{{__('Account Head Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.lc_report')}}" >{{__('LC Expense Report')}}</a></li>
        </ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-calculator"></i><span>{{__('Accounts')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.master.index')}}" >{{__('Master Head')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sub_head.index')}}" >{{__('Sub Head')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.child_one.index')}}" >{{__('Child One')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.child_two.index')}}" >{{__('Child Two')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.navigate.index')}}">{{__('Navigate View')}}</a></li>
		</ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-gear-fill"></i>
            <span>{{__('Settings')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.company.index')}}">{{__('Company Details')}}</a></li>
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'> {{__('User')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.users.index')}}">{{__('User List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.users.create')}}">{{__('New User')}}</a></li>
                </ul>
            </li>

            <!-- <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Branch')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.branch.index')}}">{{__('Branch List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.branch.create')}}">{{__('New Branch')}}</a></li>
                </ul>
            </li> -->
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Warehouse')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.warehouse.index')}}">{{__('Warehouse List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.warehouse.create')}}">{{__('New Warehouse')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Country')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.country.index')}}">{{__('Country List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.country.create')}}">{{__('New Country')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Division')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.division.index')}}">{{__('DivisionList')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.division.create')}}">{{__('New Division')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('District')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.district.index')}}">{{__('District List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.district.create')}}">{{__('New District')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Area')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.upazila.index')}}">{{__('Area List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.upazila.create')}}">{{__('New Area')}}</a></li>
                </ul>
            </li>
		</ul>
    </li>
</ul>