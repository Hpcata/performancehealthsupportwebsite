<!-- sidebar -->
<div class="me-0 px-4 py-4 py-md-4 sidebar">
	<div class="d-flex flex-column h-100">
		<a href="{!! backendRoutePut('dashboard') !!}" class="mb-0 brand-icon">
			<span class="logo-icon">
				<i class="bi bi-bag-check-fill fs-4"></i>
			</span>
			<span class="logo-text">Athleat</span>
		</a>
		<!-- Menu: main ul -->
		<ul class="flex-grow-1 mt-3 menu-list" style="overflow:auto;">

			<!-- <li><a class="m-link {!! routeIsActive(backendRoute('dashboard')) !!}" href="{!! backendRoutePut('dashboard') !!}"><i class="icofont-home fs-5"></i> <span>Dashboard</span></a></li> -->
			 <li>
				<a class="m-link {{request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
					<i class="icofont-ui-user fs-5"></i>
					<span class="sidebar-mini-text">Users</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.quiz.*') ? 'active' : '' }}" href="{{ route('admin.quiz.index') }}">
					<i class="icofont-page fs-5"></i>
					<span class="sidebar-mini-text">Quiz</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('pages.*') ? 'active' : '' }}" href="{{ route('pages.index') }}">
					<i class="icofont-page fs-5"></i>
					<span class="sidebar-mini-text">Pages</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">
					<i class="icofont-dollar fs-5"></i>
					<span class="sidebar-mini-text">Purchase Plans</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
					<i class="icofont-sale-discount fs-5"></i>
					<span class="sidebar-mini-text">Coupons</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
					<i class="icofont-chart-flow fs-5"></i>
					<span class="sidebar-mini-text">Category</span>
				</a>
			</li>
			
			<li>
				<a class="m-link {{request()->routeIs('admin.subcategories.*') ? 'active' : '' }}" href="{{ route('admin.subcategories.index') }}">
					<i class="icofont-chart-flow fs-5"></i>
					<span class="sidebar-mini-text">Sub Categories</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">
					<i class="icofont-fruits fs-5"></i>
					<span class="sidebar-mini-text">Foods</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.meals.*') ? 'active' : '' }}" href="{{ route('admin.meals.index') }}">
					<i class="icofont-culinary fs-5"></i>
					<span class="sidebar-mini-text">Meals</span>
				</a>
			</li>

			<li>
				<a class="m-link {{request()->routeIs('admin.purchase-plans.*') ? 'active' : '' }}" href="{{ route('admin.purchase-plans.index') }}">
					<i class="icofont-law-document fs-5"></i>
					<span class="sidebar-mini-text">Athlete Plans</span>
				</a>
			</li>

			<li>
				<a class="m-link {{request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
					<i class="icofont-culinary fs-5"></i>
					<span class="sidebar-mini-text">Tags</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.flags.*') ? 'active' : '' }}" href="{{ route('admin.flags.index') }}">
					<i class="icofont-fruits fs-5"></i>
					<span class="sidebar-mini-text">Preferences</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.sports-categories.*') ? 'active' : '' }}" href="{{ route('admin.sports-categories.index') }}">
					<i class="icofont-abc fs-5"></i>
					<span class="sidebar-mini-text">Sport Categories</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.sport-games.*') ? 'active' : '' }}" href="{{ route('admin.sport-games.index') }}">
					<i class="icofont-football fs-5"></i>
					<span class="sidebar-mini-text">Sport Games</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('backend.blogs.*') ? 'active' : '' }}" href="{{ route('backend.blogs.index') }}">
					<i class="icofont-copy fs-5"></i>
					<span class="sidebar-mini-text">Blog Page</span>
				</a>
			</li>
			
			<li>
				<a class="m-link {{request()->routeIs('testimonials.*') ? 'active' : '' }}" href="{!! route('testimonials.index') !!}">
					<i class="icofont-users-alt-2 fs-5"></i>
					<span class="sidebar-mini-text">Testimonials</span>
				</a>
			</li>
			<li>
				<a class="m-link {{ request()->routeIs('site-settings', ['slug' => 'general']) ? 'active' : '' }}" href="{{ route('site-settings', ['slug' => 'general']) }}">
					<i class="icofont-ui-settings fs-5"></i>
					<span class="sidebar-mini-text">Site Settings</span>
				</a>
			</li>

			{{-- <li>
				
				<a class="m-link" data-bs-toggle="collapse" data-bs-target="#categories" href="#">
					<i class="icofont-chart-flow fs-5"></i> <span>Plan Categories</span> <span class="ms-auto icofont-rounded-down text-end arrow fs-5"></span></a>
				<!-- Menu: Sub menu ul -->
				
				<ul class="collapse sub-menu" id="categories">
					<li><a class="ms-link" href="{{ route('admin.categories.index') }}">Categories List</a></li>
					<li><a class="ms-link" href="{{ route('admin.subcategories.index') }}">Sub Categories List</a></li>
				</ul>
			</li> --}}
		</ul>

		<!-- Menu: menu collepce btn -->
		<button type="button" class="text-light btn btn-link sidebar-mini-btn">
			<span class="ms-2"><i class="icofont-bubble-right"></i></span>
		</button>
	</div>
</div>
