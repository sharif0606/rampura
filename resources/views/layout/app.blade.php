<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khatungonj Treading Software | @yield('siteTitle', 'Muktodhara Technology Ltd')</title>
    
<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
<link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
{{-- choice css --}}
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
<!-- Include jQuery UI CSS file -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
<style>
    .choices__list--dropdown .choices__item--selectable {
        padding-right: 0;
    }
</style>
@stack('styles')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
  crossorigin="anonymous"></script>
</head>

<body>
    <div id="app">
    <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
				<div class="sidebar-header position-relative">
					<div class="d-flex justify-content-between align-items-center">
						<div class="logo">
							<a href="{{route(currentUser().'.dashboard')}}"><img src="{{ asset('assets/images/logo/mtlkatungonj.png')}}" alt="Logo" srcset=""></a>
						</div>
						{{-- <div class="theme-toggle d-flex gap-1  align-items-center mt-2">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
							<div class="form-check form-switch fs-6">
								<input class="form-check-input  me-0" type="checkbox" id="toggle-dark" >
								<label class="form-check-label" ></label>
							</div>
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path></svg>
						</div> --}}
						<div class="sidebar-toggler">
							<a href="#" class="sidebar-hide d-block"><i class="bi bi-x bi-middle"></i></a>
						</div>
					</div>
				</div>
				<div class="sidebar-menu pb-5 mb-4">
                    @include('layout.nav.'.currentUser())
				</div>
			</div>
        </div>
        <div id="main">
            <header class="mb-3">
                <div class="dropdown float-end">
                    <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="text">
                            <h6 class="user-dropdown-name">{{encryptor('decrypt', request()->session()->get('userName'))}} <span class="user-dropdown-status text-sm text-muted">{{currentUser()}}</span></h6>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                        <li><a class="dropdown-item" href="#">{{__('My Account') }}</a></li>
                        <li><a class="dropdown-item" href="{{route(currentUser().'.profile')}}">{{__('Profile')}}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{route('logOut')}}">{{__('Logout') }}</a></li>
                    </ul>
                </div>
                <a href="#" class="burger-btn d-block">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
			<div class="content-wrapper container">
				<div class="page-heading m-0">
                    <div class="row">
					    <div class="page-title">
							<div class="col-lg-12 col-md-12 order-md-1 order-last p-0">
								<div class="fs-5 fw-bold">@yield('pageTitle')</div>
							</div>
							<div class="col-lg-12 col-md-12 order-md-2 order-first">
								<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
									<ol class="breadcrumb mb-0">
                                        @hasSection('pageSubTitle')
										    <li class="breadcrumb-item"><a href="{{route(currentUser().'.dashboard')}}">{{__('dashboard') }}</a></li>
										    <li class="breadcrumb-item active" aria-current="page">@yield('pageSubTitle')</li>
                                        @else
                                            <li class="breadcrumb-item active">{{__('dashboard') }}</li>
                                        @endif
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>
				<div class="page-content">
					@yield('content')
				</div>
			</div>
            <footer>
                <div class="container">
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2023 &copy; POS</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                href="https://muktodharaltd.com/" target="_blank">Muktodhara Technology Limited</a></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
<script src="{{ asset('/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('/assets/js/app.js') }}"></script>
<!-- Include jQuery UI library -->
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script src="{{ asset('/assets/extensions/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{ asset('/assets/js/pages/form-element-select.js')}}"></script>
<script>
    $(function() {
        $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" }).val()
    });
</script>
{{-- //nav active code --}}
<script>
    function searchMenu() {
        var searchValue = document.getElementById("menuSearch").value.toLowerCase();
        var submenuItems = document.querySelectorAll(".submenu-item");
        var suggestionsContainer = document.getElementById("searchSuggestions");
        suggestionsContainer.innerHTML = "";

        submenuItems.forEach(function (item) {
            var menuItemText = item.textContent.toLowerCase();

            // Check if the anchor's href is not equal to #
            var menuItemLink = item.querySelector('a');
            if (menuItemLink && menuItemLink.getAttribute('href') !== '#') {
                if (menuItemText.includes(searchValue)) {
                    var suggestion = document.createElement("div");
                    suggestion.textContent = item.textContent;
                    suggestion.className = "suggestion-item";
                    suggestion.onclick = function () {
                        if (menuItemLink) {
                            menuItemLink.click();
                        }
                    };
                    suggestionsContainer.appendChild(suggestion);
                }
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        // Get the current page URL
        var currentPageUrl = window.location.href;

        // Loop through each anchor in submenu items
        $('.submenu-item a').each(function() {
            var anchorUrl = $(this).attr('href');

            // Check if the current page URL matches the anchor's URL
            if (currentPageUrl === anchorUrl) {
                // Add "active" class and style to the closest ul with class "submenu"
                $(this).closest('.submenu-item').addClass('active');
                $(this).closest('ul.submenu').addClass('active').css('display', 'block');
                
                // Add "active" class and style to the parent ul with class "submenu"
                $(this).closest('ul.submenu').parents('ul.submenu').addClass('active').css('display', 'block');
            }
        });
    });
</script>
{{-- //nav active code --}}
<script>
    function printDiv(divName) {
        var prtContent = document.getElementById(divName);
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write('<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" type="text/css"/>');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.onload =function(){
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    }
</script>
@stack('scripts')

</body>

</html>
