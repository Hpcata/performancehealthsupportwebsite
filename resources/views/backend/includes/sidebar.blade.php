<!-- sidebar -->
<div class="sidebar px-4 py-4 py-md-4 me-0">
	<div class="d-flex flex-column h-100">
		<a href="{!! route('front.index') !!}" class="mb-0 brand-icon" target="_blank">
			<span class="logo-icon">
				<i class="bi bi-bag-check-fill fs-4"></i>
			</span>
			<span class="logo-text">Performance Health</span>
		</a>
		<!-- Menu: main ul -->
		<ul class="menu-list flex-grow-1 mt-3">

			<!-- <li><a class="m-link {!! routeIsActive(backendRoute('dashboard')) !!}" href="{!! backendRoutePut('dashboard') !!}"><i class="icofont-home fs-5"></i> <span>Dashboard</span></a></li> -->
			 <li>
				<a class="m-link {{request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
					<i class="icofont-ui-user fs-5"></i>
					<span>Users</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.quiz.*') ? 'active' : '' }}" href="{{ route('admin.quiz.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Quiz">
					<i class="icofont-page fs-5"></i>
					<span>Quiz</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('pages.*') ? 'active' : '' }}" href="{{ route('pages.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Pages">
					<i class="icofont-page fs-5"></i>
					<span>Pages</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Purchase Plans">
					<i class="icofont-gym-alt-3 fs-5"></i>
					<span>Purchase Plans</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Coupons">
					<i class="icofont-sale-discount fs-5"></i>
					<span>Coupons</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.meal-times.*') ? 'active' : '' }}" href="{{ route('admin.meal-times.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Category">
					<i class="icofont-ui-clock fs-5"></i>
					<span>Categories</span>
				</a>
			</li>
			
			<li>
				<a class="m-link {{request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Sub Categories">
					<i class="icofont-chart-flow  fs-5"></i>
					<span>Sub Categories</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Foods">
					<i class="icofont-fruits fs-5"></i>
					<span>Foods</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.meals.*') ? 'active' : '' }}" href="{{ route('admin.meals.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Meals">
					<i class="icofont-culinary fs-5"></i>
					<span>Meals</span>
				</a>
			</li>

			<li>
				<a class="m-link {{request()->routeIs('admin.purchase-plans.*') ? 'active' : '' }}" href="{{ route('admin.purchase-plans.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Athlete Plans">
					<i class="icofont-law-document fs-5"></i>
					<span>Athlete Plans</span>
				</a>
			</li>

			<li>
				<a class="m-link {{request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Tags">
					<i class="icofont-culinary fs-5"></i>
					<span>Tags</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('admin.flags.*') ? 'active' : '' }}" href="{{ route('admin.flags.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Preferences">
					<i class="icofont-culinary fs-5"></i>
					<span>Preferences</span>
				</a>
			</li>

			<li>
				<a class="m-link {{request()->routeIs('backend.blogs.*') ? 'active' : '' }}" href="{{ route('backend.blogs.index') }}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Blog Page">
					<i class="icofont-copy fs-5"></i> <span>Blog Page</span>
				</a>
			</li>

			<li>
				<a class="m-link {{request()->routeIs('testimonials.*') ? 'active' : '' }}" href="{!! route('testimonials.index') !!}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Testimonials">
					<i class="icofont-users-alt-2 fs-5"></i> <span>Testimonials</span>
				</a>
			</li>
			<li>
				<a class="m-link {{request()->routeIs('organizations.*') ? 'active' : '' }}" href="{!! route('organizations') !!}" data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Associations">
					<i class="icofont-ui-rating fs-5"></i> <span>Associations</span>
				</a>
			</li>
			<li>
				<a class="m-link {{ request()->routeIs('site-settings', ['slug' => 'general']) ? 'active' : '' }}" href="{{ route('site-settings', ['slug' => 'general']) }}" 			data-bs-toggle="tooltip" 
        			data-bs-placement="right" 
        			title="Site Settings">
					<i class="icofont-ui-settings fs-5"></i> <span>Site Settings</span>
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
		</ul>

		<!-- Menu: menu collepce btn -->
		<button type="button" class="btn btn-link sidebar-mini-btn text-light">
			<span class="ms-2"><i class="icofont-bubble-right"></i></span>
		</button>
	</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>