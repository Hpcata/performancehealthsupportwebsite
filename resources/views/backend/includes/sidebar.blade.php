<!-- sidebar -->
<div class="sidebar px-4 py-4 py-md-4 me-0">
	<div class="d-flex flex-column h-100">
		<a href="{!! backendRoutePut('dashboard') !!}" class="mb-0 brand-icon">
			<span class="logo-icon">
				<i class="bi bi-bag-check-fill fs-4"></i>
			</span>
			<span class="logo-text">Performance Health</span>
		</a>
		<!-- Menu: main ul -->
		<ul class="menu-list flex-grow-1 mt-3">

			<!-- <li><a class="m-link {!! routeIsActive(backendRoute('dashboard')) !!}" href="{!! backendRoutePut('dashboard') !!}"><i class="icofont-home fs-5"></i> <span>Dashboard</span></a></li> -->

			<li>
			<?php
				$isChildActive = (routeIsActive(backendRoute('blogs.index'))
					|| routeIsActive(backendRoute('blogs.create'))
					|| routeIsActive(backendRoute('blogs.edit'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" href="{{ route('backend.blogs.index') }}"><i class="icofont-focus fs-5"></i> <span>Blog Page</span></a>
			</li>
			<li>
				<a class="m-link" href="{{ route('pages.index') }}">
					<i class="icofont-focus fs-5"></i>
					<span>Pages</span>
				</a>
			</li>
			<li>
				<a class="m-link" href="{{ route('admin.plans.index') }}">
					<i class="icofont-focus fs-5"></i>
					<span>Plans</span>
				</a>
			</li>
			<li>
				
				<a class="m-link" data-bs-toggle="collapse" data-bs-target="#categories" href="#">
					<i class="icofont-chart-flow fs-5"></i> <span>Plan Categories</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				
				<ul class="sub-menu collapse " id="categories">
					<li><a class="ms-link " href="{{ route('admin.categories.index') }}">Categories List</a></li>
					<li><a class="ms-link " href="{{ route('admin.subcategories.index') }}">Sub Categories List</a></li>
				</ul>
			</li>
			<li>
				<a class="m-link" href="{{ route('admin.items.index') }}">
					<i class="icofont-focus fs-5"></i>
					<span>Plan Items</span>
				</a>
			</li>
			<li><?php
				$isChildActive = (routeIsActive(backendRoute('testimonials.index'))
					|| routeIsActive(backendRoute('blogs.edit'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" href="{!! route('testimonials.index') !!}"><i class="icofont-users-alt-2 fs-5"></i> <span>Testimonials</span></a>
			</li>
			<li><a class="m-link {!! routeIsActive(backendRoute('organizations')) !!}" href="{!! route('organizations') !!}"><i class="icofont-ui-rating fs-5"></i> <span>Associations</span></a>
			</li>
			<li>
				<a class="m-link" href="{{ route('site-settings', ['slug' => 'general']) }}">
					<i class="icofont-focus fs-5"></i>
					<span>Site Settings</span>
				</a>
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="menu-sale">
					<li>
						<a class="ms-link" href="{{ route('site-settings', ['slug' => 'general']) }}">
							General Settings
						</a>
					</li>
				</ul>
			</li>

			{{-- <li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('customers'))
					|| routeIsActive(backendRoute('customer-detail'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#customers-info" href="#">
					<i class="icofont-funky-man fs-5"></i> <span>Customers</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="customers-info">
					<li><a class="ms-link {!! routeIsActive(backendRoute('customers')) !!}" href="{!! backendRoutePut('customers') !!}">Customers List</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('customer-detail')) !!}" href="{!! backendRoutePut('customer-detail') !!}">Customers Details</a></li>
				</ul>
			</li>

			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('coupon-list'))
					|| routeIsActive(backendRoute('coupon-add'))
					|| routeIsActive(backendRoute('coupon-edit'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#menu-sale" href="#">
					<i class="icofont-sale-discount fs-5"></i> <span>Sales Promotion</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="menu-sale">
					<li><a class="ms-link {!! routeIsActive(backendRoute('coupon-list')) !!}" href="{!! backendRoutePut('coupon-list') !!}">Coupons List</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('coupon-add')) !!}" href="{!! backendRoutePut('coupon-add') !!}">Coupons Add</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('coupon-edit')) !!}" href="{!! backendRoutePut('coupon-edit') !!}">Coupons Edit</a></li>
				</ul>
			</li>

			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('inventory-info'))
					|| routeIsActive(backendRoute('purchase'))
					|| routeIsActive(backendRoute('supplier'))
					|| routeIsActive(backendRoute('returns'))
					|| routeIsActive(backendRoute('department'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#menu-inventory" href="#">
					<i class="icofont-chart-histogram fs-5"></i> <span>Inventory</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="menu-inventory">
					<li><a class="ms-link {!! routeIsActive(backendRoute('inventory-info')) !!}" href="{!! backendRoutePut('inventory-info') !!}">Stock List</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('purchase')) !!}" href="{!! backendRoutePut('purchase') !!}">Purchase</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('supplier')) !!}" href="{!! backendRoutePut('supplier') !!}">Supplier</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('returns')) !!}" href="{!! backendRoutePut('returns') !!}">Returns</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('department')) !!}" href="{!! backendRoutePut('department') !!}">Department</a></li>
				</ul>
			</li>

			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('invoices'))
					|| routeIsActive(backendRoute('expenses'))
					|| routeIsActive(backendRoute('salaryslip'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#menu-Componentsone" href="#"><i class="icofont-ui-calculator"></i> <span>Accounts</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="menu-Componentsone">
					<li><a class="ms-link {!! routeIsActive(backendRoute('invoices')) !!}" href="{!! backendRoutePut('invoices') !!}">Invoices </a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('expenses')) !!}" href="{!! backendRoutePut('expenses') !!}">Expenses </a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('salaryslip')) !!}" href="{!! backendRoutePut('salaryslip') !!}">Salary Slip </a></li>
				</ul>
			</li>

			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('calendar'))
					|| routeIsActive(backendRoute('chat'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#app" href="#">
					<i class="icofont-code-alt fs-5"></i> <span>App</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="app">
					<li><a class="ms-link {!! routeIsActive(backendRoute('calendar')) !!}" href="{!! backendRoutePut('calendar') !!}">Calandar</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('chat')) !!}" href="{!! backendRoutePut('chat') !!}"> Chat App</a></li>
				</ul>
			</li> --}}

			
			{{--
			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('ui-alerts'))
					|| routeIsActive(backendRoute('ui-badge'))
					|| routeIsActive(backendRoute('ui-breadcrumb'))
					|| routeIsActive(backendRoute('ui-buttons'))
					|| routeIsActive(backendRoute('ui-card'))
					|| routeIsActive(backendRoute('ui-carousel'))
					|| routeIsActive(backendRoute('ui-collapse'))
					|| routeIsActive(backendRoute('ui-dropdowns'))
					|| routeIsActive(backendRoute('ui-listgroup'))
					|| routeIsActive(backendRoute('ui-modal'))
					|| routeIsActive(backendRoute('ui-navs'))
					|| routeIsActive(backendRoute('ui-navbar'))
					|| routeIsActive(backendRoute('ui-pagination'))
					|| routeIsActive(backendRoute('ui-popovers'))
					|| routeIsActive(backendRoute('ui-progress'))
					|| routeIsActive(backendRoute('ui-scrollspy'))
					|| routeIsActive(backendRoute('ui-spinners'))
					|| routeIsActive(backendRoute('ui-toasts'))
					|| routeIsActive(backendRoute('ui-tooltips'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#menu-Components" href="#"><i class="icofont-paint"></i> <span>UI Components</span> <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="menu-Components">
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-alerts')) !!}" href="{!! backendRoutePut('ui-alerts') !!}"><span>Alerts</span> </a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-badge')) !!}" href="{!! backendRoutePut('ui-badge') !!}"><span>Badge</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-breadcrumb')) !!}" href="{!! backendRoutePut('ui-breadcrumb') !!}"><span>Breadcrumb</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-buttons')) !!}" href="{!! backendRoutePut('ui-buttons') !!}"><span>Buttons</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-card')) !!}" href="{!! backendRoutePut('ui-card') !!}"><span>Card</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-carousel')) !!}" href="{!! backendRoutePut('ui-carousel') !!}"><span>Carousel</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-collapse')) !!}" href="{!! backendRoutePut('ui-collapse') !!}"><span>Collapse</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-dropdowns')) !!}" href="{!! backendRoutePut('ui-dropdowns') !!}"><span>Dropdowns</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-listgroup')) !!}" href="{!! backendRoutePut('ui-listgroup') !!}"><span>List group</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-modal')) !!}" href="{!! backendRoutePut('ui-modal') !!}"><span>Modal</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-navs')) !!}" href="{!! backendRoutePut('ui-navs') !!}"><span>Navs</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-navbar')) !!}" href="{!! backendRoutePut('ui-navbar') !!}"><span>Navbar</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-pagination')) !!}" href="{!! backendRoutePut('ui-pagination') !!}"><span>Pagination</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-popovers')) !!}" href="{!! backendRoutePut('ui-popovers') !!}"><span>Popovers</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-progress')) !!}" href="{!! backendRoutePut('ui-progress') !!}"><span>Progress</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-scrollspy')) !!}" href="{!! backendRoutePut('ui-scrollspy') !!}"><span>Scrollspy</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-spinners')) !!}" href="{!! backendRoutePut('ui-spinners') !!}"><span>Spinners</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-toasts')) !!}" href="{!! backendRoutePut('ui-toasts') !!}"><span>Toasts</span></a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('ui-tooltips')) !!}" href="{!! backendRoutePut('ui-tooltips') !!}"><span>Tooltips</span></a></li>
				</ul>
			</li>


			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('auth-signin'))
					|| routeIsActive(backendRoute('auth-signup'))
					|| routeIsActive(backendRoute('auth-password-reset'))
					|| routeIsActive(backendRoute('auth-two-step'))
					|| routeIsActive(backendRoute('auth-404'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#Authentication" href="#">
					<i class="icofont-ui-lock fs-5"></i> <span>Authentication</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="Authentication">
					<li><a class="ms-link {!! routeIsActive(backendRoute('auth-signin')) !!}" href="{!! backendRoutePut('auth-signin') !!}">Sign in</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('auth-signup')) !!}" href="{!! backendRoutePut('auth-signup') !!}">Sign up</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('auth-password-reset')) !!}" href="{!! backendRoutePut('auth-password-reset') !!}">Password reset</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('auth-two-step')) !!}" href="{!! backendRoutePut('auth-two-step') !!}">2-Step Authentication</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('auth-404')) !!}" href="{!! backendRoutePut('auth-404') !!}">404</a></li>
				</ul>
			</li>

			<li class="collapsed">
				<?php
				$isChildActive = (routeIsActive(backendRoute('admin-profile'))
					|| routeIsActive(backendRoute('purchase-plan'))
					|| routeIsActive(backendRoute('charts'))
					|| routeIsActive(backendRoute('table'))
					|| routeIsActive(backendRoute('forms'))
					|| routeIsActive(backendRoute('icon'))
					|| routeIsActive(backendRoute('contact'))
					|| routeIsActive(backendRoute('todo-list'))
				) ? 1 : 0;
				?>
				<a class="m-link {!! $isChildActive ? 'active' : '' !!}" data-bs-toggle="collapse" data-bs-target="#page" href="#">
					<i class="icofont-page fs-5"></i> <span>Other Pages</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				<ul class="sub-menu collapse {!! $isChildActive ? 'show' : '' !!}" id="page">
					<li><a class="ms-link {!! routeIsActive(backendRoute('admin-profile')) !!}" href="{!! backendRoutePut('admin-profile') !!}">Profile Page</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('purchase-plan')) !!}" href="{!! backendRoutePut('purchase-plan') !!}">Price Plan Example</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('charts')) !!}" href="{!! backendRoutePut('charts') !!}">Charts Example</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('table')) !!}" href="{!! backendRoutePut('table') !!}">Table Example</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('forms')) !!}" href="{!! backendRoutePut('forms') !!}">Forms Example</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('icon')) !!}" href="{!! backendRoutePut('icon') !!}">Icons</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('contact')) !!}" href="{!! backendRoutePut('contact') !!}">Contact Us</a></li>
					<li><a class="ms-link {!! routeIsActive(backendRoute('todo-list')) !!}" href="{!! backendRoutePut('todo-list') !!}">Todo List</a></li>
				</ul>
			</li>

			<li><a class="m-link {!! routeIsActive(backendRoute('documentation')) !!}" href="{!! backendRoutePut('documentation') !!}"><i class="icofont-law-document fs-5"></i> <span>Documentation</span></a></li>
			<li><a class="m-link {!! routeIsActive(backendRoute('changelog')) !!}" href="{!! backendRoutePut('changelog') !!}"><i class="icofont-edit fs-5"></i> <span>Changelog</span> <span class="ms-auto small-14 fw-bold">v1.0.0</span></a></li>
			--}}
		</ul>

		<!-- Menu: menu collepce btn -->
		<button type="button" class="btn btn-link sidebar-mini-btn text-light">
			<span class="ms-2"><i class="icofont-bubble-right"></i></span>
		</button>
	</div>
</div>