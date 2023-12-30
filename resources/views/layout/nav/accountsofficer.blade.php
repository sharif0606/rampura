<ul class="menu">
    <li class="sidebar-item">
        <a href="{{route(currentUser().'.dashboard')}}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>{{__('dashboard') }}</span>
        </a>
    </li>
    
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-gear-fill"></i>
            <span>{{__('Settings')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.company.index')}}">{{__('Company Details')}}</a></li>
            {{-- <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'> {{__('User')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.users.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.users.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li> --}}

            <!-- <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Branch')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.branch.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.branch.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li> -->
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Warehouse')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.warehouse.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.warehouse.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Country')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.country.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.country.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Division')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.division.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.division.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('District')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.district.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.district.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Area')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.upazila.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.upazila.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
		</ul>
    </li>



    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-box-fill"></i><span>{{__('Products')}}</span>
        </a>
        <ul class="submenu">
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'> {{__('Category')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.category.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.category.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Sub Category')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.subcategory.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.subcategory.create')}}">{{__('Add New')}}</a></li>
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
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.product.index')}}">{{__('List')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.product.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            {{-- <li><a href="{{route(currentUser().'.plabel')}}" >{{__('Product Label')}}</a></li> --}}
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
        <a href="#" class='sidebar-link'><i class="bi bi-cart-fill"></i><span>{{__('Sales')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.index')}}">{{__('List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.create')}}">{{__('Add New')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.cash')}}">{{__('Cash Sales')}}</a></li>
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_pending_exp')}}">{{__('Pending Expense')}}</a></li> controller and view can be deleted --}} 
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_pending_pay')}}">{{__('Pending Payment')}}</a></li> --}}
		</ul>   
    </li>

    
    {{-- <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-sign-turn-right-fill"></i><span>{{__('Transfer')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.transfer.index')}}">{{__('Transfer list')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.transfer.create')}}">{{__('Transfer')}}</a></li>
		</ul>   
    </li> --}}
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-people-fill"></i></i><span>{{__('Supplier')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.supplier.index')}}">{{__('List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.supplier.create')}}">{{__('Add New')}}</a></li>
		</ul>   
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-people-fill"></i></i><span>{{__('Customer')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.customer.index')}}">{{__('List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.customer.create')}}">{{__('Add New')}}</a></li>
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
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.incomeStatement')}}">{{__('Income Statement')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.headreport')}}" >{{__('Account Head Report')}}</a></li>
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
        <a href="#" class='sidebar-link'><i class="bi bi-card-checklist"></i><span>{{__('Report')}}</span></a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.purchase_report')}}" >{{__('Purchase Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.beparian_report')}}" >{{__('Beparian Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.regular_report')}}" >{{__('Regular Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.all_pur_report')}}" >{{__('Purchase All')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.salreport')}}" >{{__('Sales Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sreport')}}" >{{__('Stock Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.srota')}}" >{{__('Srota')}}</a></li>
        </ul>
    </li>
</ul>