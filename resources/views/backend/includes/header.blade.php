<!-- Body: Header -->
<div class="header">
	<nav class="navbar py-4">
		<div class="container-xxl d-flex justify-content-between align-items-center">
			<!-- header rightbar icon -->
			<div class="h-right d-flex align-items-center ms-auto">
				<div class="dropdown user-profile ms-3 d-flex align-items-center zindex-popover">
					<div class="u-info me-2">
						<p class="mb-0 text-end line-height-sm "><span
								class="font-weight-bold">{{ Auth::user()->name ?? '' }}</span></p>
						<small>{{ Auth::user()->designation ?? '' }}</small>
					</div>
					<a class="nav-link dropdown-toggle pulse p-0" href="#" role="button" data-bs-toggle="dropdown"
						data-bs-display="static">
						<img class="avatar lg rounded-circle img-thumbnail"
							src="{{ isset(Auth::user()->profile_image) ? uploadAssets(Auth::user()->profile_image) : 'https://booking.biohealthpassport.com.au/public/admin/dist/assets/images/profile_av.svg' }}"
							alt="profile">
					</a>
					<div class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-end p-0 m-0">
						<div class="card border-0 w280">
							<div class="card-body pb-0">
								<div class="d-flex py-1">
									<img class="avatar rounded-circle"
										src="{{ isset(Auth::user()->profile_image) ? uploadAssets(Auth::user()->profile_image) : 'https://booking.biohealthpassport.com.au/public/admin/dist/assets/images/profile_av.svg' }}"
										alt="profile">
									<div class="flex-fill ms-3">
										<p class="mb-0"><span
												class="font-weight-bold">{{ Auth::user()->name ?? '' }}</span></p>
										<small class="d-block text-truncate" style="max-width: 170px;"
											data-bs-toggle="tooltip"
											title="{{ Auth::user()->email ?? '' }}">{{ Auth::user()->email ?? '' }}</small>
									</div>
								</div>

								<div>
									<hr class="dropdown-divider border-dark">
								</div>
							</div>
							<div class="list-group m-2 ">
								<a href="{!! route('admin.profile.index', ['id' => Auth::user()->id ?? 3]) !!}"
									class="list-group-item list-group-item-action border-0 "><i
										class="icofont-ui-file fs-5 me-3"></i>Profile Page</a>
								<a href="{!! route('admin.auth.change.index') !!}"
									class="list-group-item list-group-item-action border-0 "><i
										class="icofont-ui-user fs-5 me-3"></i>Change Password</a>
								<a href="{!! route('admin.auth.logout') !!}"
									class="list-group-item list-group-item-action border-0 "><i
										class="icofont-logout fs-5 me-3"></i>Signout</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- menu toggler -->
			<button class="navbar-toggler p-0 border-0 menu-toggle order-3" type="button" data-bs-toggle="collapse"
				data-bs-target="#mainHeader">
				<span class="fa fa-bars"></span>
			</button>
		</div>
	</nav>
</div>