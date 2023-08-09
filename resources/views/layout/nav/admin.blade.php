

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
            <li class="py-1"><a href="{{route(currentUser().'.admincompany')}}">{{__('Company Details')}}</a></li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('User')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.admin.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.admin.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Currency')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.currency.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.currency.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Country')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.country.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.country.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Division')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.division.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.division.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('District')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.district.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.district.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Upazila')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.upazila.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.upazila.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Thana')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.thana.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.thana.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
            <li class="submenu-item sidebar-item has-sub">
                <a href="#" class='sidebar-link'> {{__('Unit')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.unit.index')}}">{{__('List')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.unit.create')}}">{{__('Add New')}}</a></li>
                </ul>
            </li>
        </ul>
    </li>
</ul>