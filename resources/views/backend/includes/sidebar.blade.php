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
				<a class="m-link {{request()->routeIs('backend.blogs.*') ? 'active' : '' }}" href="{{ route('backend.blogs.index') }}"><i class="icofont-copy fs-5"></i> <span>Blog Page</span></a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('pages.*') ? 'active' : '' }}" href="{{ route('pages.index') }}">
					<i class="icofont-page fs-5"></i>
					<span>Pages</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.meal-times.*') ? 'active' : '' }}" href="{{ route('admin.meal-times.index') }}">
					<i class="icofont-ui-clock fs-5"></i>
					<span>Meal Times</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">
					<i class="icofont-gym-alt-3 fs-5"></i>
					<span>Plans</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
					<i class="icofont-chart-flow  fs-5"></i>
					<span>Categories</span>
				</a>
			</li>
			
			<li>
				<a class="m-link {{request()->routeIs('admin.meals.*') ? 'active' : '' }}" href="{{ route('admin.meals.index') }}">
					<i class="icofont-culinary fs-5"></i>
					<span>Meals</span>
				</a>
			</li>
			{{-- <li>
				
				<a class="m-link" data-bs-toggle="collapse" data-bs-target="#categories" href="#">
					<i class="icofont-chart-flow fs-5"></i> <span>Plan Categories</span> <span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				
				<ul class="sub-menu collapse " id="categories">
					<li><a class="ms-link " href="{{ route('admin.categories.index') }}">Categories List</a></li>
					<li><a class="ms-link " href="{{ route('admin.subcategories.index') }}">Sub Categories List</a></li>
				</ul>
			</li> --}}
			<li>
				<a class="m-link {{request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">
					<i class="icofont-fruits fs-5"></i>
					<span>Foods</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.purchase-plans.*') ? 'active' : '' }}" href="{{ route('admin.purchase-plans.index') }}">
					<i class="icofont-law-document fs-5"></i>
					<span>Purchase Plans</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('testimonials.*') ? 'active' : '' }}" href="{!! route('testimonials.index') !!}"><i class="icofont-users-alt-2 fs-5"></i> <span>Testimonials</span></a>
			</li>
			<li><a class="m-link {{request()->routeIs('organizations.*') ? 'active' : '' }}" href="{!! route('organizations') !!}"><i class="icofont-ui-rating fs-5"></i> <span>Associations</span></a>
			</li>
			

			<li><a class="m-link {{ request()->routeIs('site-settings', ['slug' => 'general']) ? 'active' : '' }}" href="{{ route('site-settings', ['slug' => 'general']) }}"><i class="icofont-ui-settings fs-5"></i> <span>Site Settings</span></a>
			</li>

		</ul>

		<!-- Menu: menu collepce btn -->
		<button type="button" class="btn btn-link sidebar-mini-btn text-light">
			<span class="ms-2"><i class="icofont-bubble-right"></i></span>
		</button>
	</div>
</div>