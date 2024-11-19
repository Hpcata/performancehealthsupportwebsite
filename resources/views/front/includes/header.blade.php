<header id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="d-flex align-items-center w-100">
                <a class="navbar-brand" href="#">
                <img src="{{ frontAssets('images/logo.svg') }}" alt="">
                </a>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="ms-lg-auto header-navbar navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" title="{{$slug}}" href="{{ route('front.index', $slug) }}">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('front.blog', $slug) }}">Blog</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                </div>
            </div>
            </nav>
    </div>
</header>
<!-- <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
        <span class="icofont-close js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div> -->