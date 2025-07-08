<!-- sidebar -->
<div class="sidebar px-4 py-4 py-md-4 me-0" style="width: 220px;">
	<div class="d-flex flex-column h-100">
		<a href="{!! route('admin.dashboard') !!}" class="mb-0 brand-icon">
			<span class="logo-icon">
				<img src="{!! backendAssets('dist/assets/images/main-logo.png') !!}" class="img-fluid" alt="logo">
			</span>
			<span class="logo-text">Athleat Fuel</span>
		</a>

		<ul class="menu-list flex-grow-1 mt-3">

			<li>
				<a class="m-link {{request()->routeIs('admin.purchase-plans.*') ? 'active' : '' }}"
					href="{{ route('admin.purchase-plans.index') }}">
					<i class="icofont-law-document fs-5"></i>
					<span>Athlete Plans</span>
				</a>
			</li>

			<!-- 🔸 User Management -->
			<?php
				$userActive = (
					request()->routeIs('admin.users.*') ||
					request()->routeIs('admin.plans.*') ||
					request()->routeIs('admin.coupons.*')
				) ? 1 : 0;
			?>
			<li class="collapsed">
				<a class="m-link {{ $userActive ? 'active' : '' }}" data-bs-toggle="collapse"
					data-bs-target="#menu-user" href="#">
					<i class="icofont-ui-user fs-5"></i><span>Customer</span>
					<span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span>
				</a>
				<ul class="sub-menu collapse {{ $userActive ? 'show' : '' }}" id="menu-user">
					<li><a class="ms-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
							href="{{ route('admin.users.index') }}">Users</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}"
							href="{{ route('admin.plans.index') }}">Purchase Plans</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
							href="{{ route('admin.coupons.index') }}">Coupons</a></li>
				</ul>
			</li>

			<!-- 🔸 Content Management -->
			<?php
				$contentActive = (
					request()->routeIs('admin.quiz.*') ||
					request()->routeIs('admin.pages.*') ||
					request()->routeIs('admin.blogs.*') ||
					request()->routeIs('admin.testimonials.*')
				) ? 1 : 0;
			?>
			<li class="collapsed">
				<a class="m-link {{ $contentActive ? 'active' : '' }}" data-bs-toggle="collapse"
					data-bs-target="#menu-content" href="#">
					<i class="icofont-copy fs-5"></i><span>Content</span>
					<span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span>
				</a>
				<ul class="sub-menu collapse {{ $contentActive ? 'show' : '' }}" id="menu-content">
					<li><a class="ms-link {{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}"
							href="{{ route('admin.quiz.index') }}">Quiz</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
							href="{{ route('admin.pages.index') }}">Pages</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
							href="{{ route('admin.blogs.index') }}">Blog Page</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"
							href="{{ route('admin.testimonials.index') }}">Testimonials</a></li>
				</ul>
			</li>

			<!-- 🔸 Nutrition -->
			<?php
				$nutritionActive = (
					request()->routeIs('admin.categories.*') ||
					request()->routeIs('admin.subcategories.*') ||
					request()->routeIs('admin.items.*') ||
					request()->routeIs('admin.meals.*') ||
					request()->routeIs('admin.tags.*') ||
					request()->routeIs('admin.flags.*')
				) ? 1 : 0;
			?>
			<li class="collapsed">
				<a class="m-link {{ $nutritionActive ? 'active' : '' }}" data-bs-toggle="collapse"
					data-bs-target="#menu-nutrition" href="#">
					<i class="icofont-culinary fs-5"></i><span>Nutrition</span>
					<span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span>
				</a>
				<ul class="sub-menu collapse {{ $nutritionActive ? 'show' : '' }}" id="menu-nutrition">
					<li><a class="ms-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
							href="{{ route('admin.categories.index') }}">Category</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}"
							href="{{ route('admin.subcategories.index') }}">Sub Categories</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}"
							href="{{ route('admin.items.index') }}">Foods</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.meals.*') ? 'active' : '' }}"
							href="{{ route('admin.meals.index') }}">Meals</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}"
							href="{{ route('admin.tags.index') }}">Tags</a></li>
					<li><a class="ms-link {{ request()->routeIs('admin.flags.*') ? 'active' : '' }}"
							href="{{ route('admin.flags.index') }}">Preferences</a></li>
				</ul>
			</li>

			<!-- 🔸 Others -->
			<?php $othersActive = request()->routeIs('admin.organizations.*') || request()->routeIs('settings'); ?>
			<li class="collapsed">
				<a class="m-link {{ $othersActive ? 'active' : '' }}" data-bs-toggle="collapse"
					data-bs-target="#menu-others" href="#">
					<i class="icofont-ui-settings fs-5"></i><span>Others</span>
					<span class="arrow icofont-rounded-down ms-auto text-end fs-5"></span>
				</a>
				<ul class="sub-menu collapse {{ $othersActive ? 'show' : '' }}" id="menu-others">
					<li><a class="ms-link {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}"
							href="{{ route('admin.organizations.index') }}">Associations</a></li>
					<li><a class="ms-link {{ request()->routeIs('settings') ? 'active' : '' }}"
							href="{{ route('admin.settings.index', ['slug' => 'general']) }}">Site Settings</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>