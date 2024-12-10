@extends(frontView('layouts.app'))

@section('title', 'Blog')

@section('content')
    <div class="page-title-row section-row" style="background-image: url('{{ frontAssets('images/blog-title-img.jpg') }}');">
        <div class="container text-center">
            <div class="page-title-box">
                <h1 class="m-0 text-white">Blog</h1>
            </div>
        </div>
        <div class="nav-breadcrumb">
            <nav aria-label="breadcrumb">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog</li>
                    </ol>
                </div>
            </nav>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="blog-list">
                <div class="row g-4">
                    @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-list-box">
                            <div class="blog-post-img">
                                <figure>
                                    <a href="#">
                                        <img src="{{ asset($blog->image) }}" alt="">
                                    </a>
                                </figure>
                            </div>
                            <div class="blog-list-info">
                                <h5><a href="#">{{ $blog->title }}</a></h5>
                                <ul class="about-blog-info">
                                    <li>
                                        <figure>
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 18.3334C14.6024 18.3334 18.3333 14.6024 18.3333 10C18.3333 5.39765 14.6024 1.66669 10 1.66669C5.39763 1.66669 1.66667 5.39765 1.66667 10C1.66667 14.6024 5.39763 18.3334 10 18.3334Z" stroke="#4D4D4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10 5V10L12.5 12.5" stroke="#4D4D4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>                                                
                                        </figure>
                                        <span>{{ $blog->created_at->format('F j, Y') }}
                                        </span>
                                    </li>
                                    <!-- <li>
                                        <figure>
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.74999 19.1666C1.49999 19.1666 1.24999 19.0833 1.08333 18.9166C0.833328 18.6666 0.749995 18.3333 0.833328 18L2.49999 13.0833C1.99999 12 1.74999 10.75 1.74999 9.49998C1.74999 6.16665 3.58333 3.16665 6.58333 1.74998C7.74999 1.16665 9.08333 0.833313 10.4167 0.833313H10.8333C15.3333 1.08331 18.8333 4.58331 19.0833 8.99998V9.49998C19.0833 10.8333 18.75 12.1666 18.1667 13.4166C16.6667 16.4166 13.6667 18.25 10.4167 18.25C9.16666 18.25 7.99999 18 6.83333 17.5L1.99999 19.0833C1.91666 19.1666 1.83333 19.1666 1.74999 19.1666ZM10.4167 2.66665C9.33333 2.66665 8.33333 2.91665 7.33333 3.41665C4.99999 4.58331 3.58333 6.91665 3.58333 9.58331C3.58333 10.6666 3.83333 11.6666 4.33333 12.6666C4.41666 12.9166 4.49999 13.1666 4.41666 13.3333L3.24999 16.8333L6.66666 15.6666C6.91666 15.5833 7.16666 15.5833 7.33333 15.75C8.24999 16.25 9.33333 16.5 10.4167 16.5C13 16.5 15.4167 15.0833 16.5833 12.6666C17.0833 11.75 17.3333 10.6666 17.3333 9.58331V9.16665C17.1667 5.74998 14.3333 2.91665 10.8333 2.74998L10.4167 2.66665Z" fill="#4D4D4D"/>
                                            </svg>
                                        </figure>
                                        <span>01</span>
                                    </li> -->
                                    <li>
                                        <a href="#">
                                            <figure>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14 12H6C3.25 12 1 14.25 1 17V19C1 19.5833 1.41667 20 2 20C2.58333 20 3 19.5833 3 19V17C3 15.3333 4.33333 14 6 14H14C15.6667 14 17 15.3333 17 17V19C17 19.5833 17.4167 20 18 20C18.5833 20 19 19.5833 19 19V17C19 14.25 16.75 12 14 12Z" fill="#4D4D4D"/>
                                                    <path d="M10 10C12.75 10 15 7.75 15 5C15 2.25 12.75 0 10 0C7.25 0 5 2.25 5 5C5 7.75 7.25 10 10 10ZM10 2C11.6667 2 13 3.33333 13 5C13 6.66667 11.6667 8 10 8C8.33333 8 7 6.66667 7 5C7 3.33333 8.33333 2 10 2Z" fill="#4D4D4D"/>
                                                </svg>
                                            </figure>
                                            <span>{{ $blog->user->is_superadmin === 1 ? 'Super Admin' : 'Admin'  }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="category-box">
                                    <!-- <a href="#">Uncategorized</a> -->
                                </div>
                                <p>{!! $blog->description !!}</p>
                                <a href="{{ route('front.blog.detail', ['id' => $blog->id]) }}" class="btn btn-primary mt-2 w-100">Read More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endpush