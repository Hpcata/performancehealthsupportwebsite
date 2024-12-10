<?php
$setting = \App\Models\SiteSettings::where('page_id', 'general')->where('meta_key', 'header_headermenu')->first();
$headerData = json_decode($setting['meta_value'], true);
?>
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
                @foreach ($headerData as $menu)
                    @php
                        $slug = '';
                        $link = $menu['link'] ?? '';
                        if(str_contains($link, '|')){
                            $explodelinks = explode('|', $link);
                            $link = $explodelinks[0] ?? '';
                            $slug = $explodelinks[1] ?? '';
                        }
                        $title = $menu['title'] ?? 'Untitled';
                    @endphp
                    @if($link != '' && $link != '#')
                        <li class="nav-item">
                            <a class="nav-link restriction-page" href="{{ route($link, $slug ? ['page_slug' => $slug] : []) }}">{{ $title }}</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link restriction-page" href="#{{ strtolower($title) }}">{{ $title }}</a>
                        </li>
                    @endif
                @endforeach
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