@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By Community Healthcare Clinics">
    <meta name="url" content="https://www.communityhealthcareclinics.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.communityhealthcareclinics.com" />
    <meta property="og:site_name" content="Community Healthcare Clinics | communityhealthcareclinics.com" />
    <meta name="twitter:site" content="@communityhealthcareclinics">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Community">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root {
            --red: #c80919;
            --blue: #2964bc;
            --maroon: #c80919;
            --navy-blue: #082755;
            --green: #35b518;
            --lh: 1.4rem;
            --lightgray: #f5f5f5;
            --lightblue: #2964BCA3;
            --text-dark: #333;
            --text-muted: #6c757d;
            --text-light: #f8f9fa;
            --bg-white: #ffffff;
            --border-radius-sm: 6px;
            --border-radius-md: 12px;
            --border-radius-lg: 20px;
            --box-shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.05);
            --box-shadow-md: 0 10px 30px rgba(41, 100, 188, 0.1);
            --transition: all 0.3s ease;
            --font-main: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-main);
        }

        body {
            background-color: var(--lightgray);
            color: var(--text-dark);
            line-height: var(--lh);
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Header Section */
        .blog-header {
            position: relative;
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--blue) 100%);
            padding: 50px 0 120px;
            margin-bottom: 0;
            overflow: hidden;
            color: white;
        }

        .header-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .header-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 20%),
                              radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 20%),
                              radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 30%);
            z-index: 1;
        }

        .blog-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .blog-header p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Main Content Wrapper */
        .content-wrapper {
            margin-top: -5%;
            position: relative;
            z-index: 3;
        }

        /* Blog Filters */
        .blog-filters {
            background-color: var(--bg-white);
            padding: 20px 25px;
            border-radius: var(--border-radius-md);
            margin-bottom: 40px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--box-shadow-sm);
            border-top: 2px solid var(--red);
        }

        .blog-categories {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .category-tag {
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid #e9ecef;
            color: var(--text-muted);
        }

        .category-tag.active {
            background-color: var(--blue);
            color: white;
            border-color: var(--blue);
            box-shadow: 0 4px 12px rgba(41, 100, 188, 0.25);
        }

        .category-tag:hover:not(.active) {
            background-color: var(--lightgray);
            transform: translateY(-2px);
        }

        .search-box {
            position: relative;
            margin-top: 0;
            width: 100%;
        }


        .search-box input {
            width: 100%;
            padding: 12px 20px 12px 48px;
            border-radius: 30px;
            border: 1px solid #e9ecef;
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
            background-color: var(--lightgray);
        }

        .search-box input:focus {
            border-color: var(--blue);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(41, 100, 188, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--blue);
        }

        /* Featured Blog */
        .featured-blog {
            background-color: var(--bg-white);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: var(--box-shadow-sm);
            display: grid;
            grid-template-columns: 1fr;
            position: relative;
            border-top: 2px solid var(--red);
        }

        @media (min-width: 768px) {
            .featured-blog {
                grid-template-columns: 1.2fr 1fr;
            }
        }

        .featured-img {
            height: 100%;
            min-height: 280px;
            position: relative;
            overflow: hidden;
        }

        .featured-img::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.4));
        }

        .featured-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .featured-blog:hover .featured-img img {
            transform: scale(1.05);
        }

        .featured-content {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .featured-tag {
            background-color: rgba(200, 9, 25, 0.1);
            color: var(--red);
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .featured-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
            color: var(--navy-blue);
        }

        .featured-excerpt {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .btn-read {
            display: inline-flex;
            align-items: center;
            color: white;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            transition: var(--transition);
            padding: 12px 24px;
            border-radius: 30px;
            background-color: var(--red);
            width: fit-content;
        }

        .btn-read:hover {
            background-color: var(--navy-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(8, 39, 85, 0.25);
        }

        .btn-read i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .btn-read:hover i {
            transform: translateX(4px);
        }

        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin-bottom: 60px;
        }

        @media (min-width: 640px) {
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .blog-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .blog-card {
            background-color: var(--bg-white);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            box-shadow: var(--box-shadow-sm);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            border-top: 3px solid var(--blue);
        }

        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--box-shadow-md);
        }

        .card-img {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-card:hover .card-img img {
            transform: scale(1.08);
        }

        .card-tag {
            position: absolute;
            top: 16px;
            left: 16px;
            background-color: var(--red);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 1;
        }

        .card-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-date {
            display: flex;
            align-items: center;
            color: var(--blue);
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .card-date i {
            margin-right: 6px;
            font-size: 0.8rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.4;
            color: var(--navy-blue);
        }

        .card-excerpt {
            color: var(--text-muted);
            margin-bottom: 24px;
            font-size: 0.95rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            color: var(--red);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            transition: var(--transition);
            margin-top: auto;
        }

        .card-link:hover {
            color: var(--navy-blue);
        }

        .card-link i {
            margin-left: 6px;
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .card-link:hover i {
            transform: translateX(4px);
        }

        /* Newsletter Section */
        .newsletter {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--blue) 100%);
            border-radius: var(--border-radius-md);
            padding: 60px 40px;
            margin-bottom: 80px;
            text-align: center;
            color: white;
            box-shadow: var(--box-shadow-md);
            position: relative;
            overflow: hidden;
        }

        .newsletter::before {
            content: '';
            /* position: absolute; */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 25%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 25%);
            z-index: 1;
        }

        .newsletter-content {
            position: relative;
            z-index: 2;
        }

        .newsletter h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .newsletter p {
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .newsletter-form {
            max-width: 550px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
        }

        @media (min-width: 640px) {
            .newsletter-form {
                flex-direction: row;
            }
        }

        .newsletter-input {
            flex-grow: 1;
            padding: 16px 24px;
            border-radius: 30px;
            border: none;
            font-size: 1rem;
            outline: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .newsletter-btn {
            background-color: var(--red);
            color: white;
            padding: 16px 30px;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .newsletter-btn:hover {
            background-color: var(--navy-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin: 60px 0 80px;
        }

        .pagination-list {
            display: flex;
            list-style: none;
            gap: 8px;
        }

        .pagination-item a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-weight: 500;
            font-size: 1rem;
            text-decoration: none;
            color: var(--text-dark);
            transition: var(--transition);
            background-color: white;
            box-shadow: var(--box-shadow-sm);
        }

        .pagination-item a:hover {
            background-color: var(--lightgray);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .pagination-item.active a {
            background-color: var(--red);
            color: white;
            box-shadow: 0 4px 12px rgba(200, 9, 25, 0.25);
        }

        .pagination-nav {
            width: 44px;
            height: 44px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background-color: white;
            box-shadow: var(--box-shadow-sm);
            cursor: pointer;
            transition: var(--transition);
            margin: 0 4px;
        }

        .pagination-nav:hover {
            background-color: var(--lightgray);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .pagination-nav.prev:hover {
            transform: translateX(-3px) translateY(-2px);
        }

        .pagination-nav.next:hover {
            transform: translateX(3px) translateY(-2px);
        }
    </style>
@endsection


@section('page_title')
    <title>Community Healthcare Clinics</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script src="{{ asset('assets/js/home.js?n=1') }}"></script>
@endsection
@section('content')
    <div class="blog-header">
        <div class="header-pattern"></div>
        <div class="container">
            <div class="header-content">
                <h1>Community Healthcare Clinics Insights</h1>
                <p>Stay informed with the latest insights, tips, and news on health, treatments, and preventive care.</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="content-wrapper">
            <!-- Blog Filters -->
            <div class="blog-filters">
                <div class="search-box">
                    <span class="search-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" placeholder="Search articles...">
                </div>
            </div>

            <!-- Featured Blog -->
            @if (isset($featured_blogs) && $featured_blogs->count() > 0)
                <div class="featured-blog">
                    <div class="featured-img">
                        <img src="{{ $featured_blogs->featured_image }}" alt="{{ $featured_blogs->title }}">
                    </div>
                    <div class="featured-content">
                        <span class="featured-tag">Latest Article</span>
                        <h2 class="featured-title">{{ $featured_blogs->title }}</h2>
                        <p class="featured-excerpt">{!! $featured_blogs->excerpt !!}</p>
                        <a href="{{ route('blog_single', $featured_blogs->slug) }}" class="btn-read">
                            Read the Article
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Blog Grid -->
            <div class="blog-grid">
                @foreach ($blogs as $blog)
                    <div class="blog-card">
                        <div class="card-img">
                            <img src="{{ $blog->featured_image }}" alt="">
                        </div>
                        <div class="card-content">
                            <div class="card-date">
                                <i class="fa-regular fa-calendar"></i>
                                {{ $blog->created_at->format('F d, Y') }}
                            </div>
                            <h3 class="card-title">{{ $blog->title }}</h3>
                            <p class="card-excerpt">{!! $blog->excerpt !!}</p>
                            <a href="{{ route('blog_single', $blogs[0]->slug) }}" class="card-link">
                                Read More
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $blogs->links('pagination::bootstrap-4') }}

            <!-- Newsletter Section -->
            <div class="newsletter">
                <div class="newsletter-content">
                    <h3>Get Expert Health Tips in Your Inbox</h3>
                    <p>Subscribe to our newsletter for the latest health insights, special offers, and personalized
                        care recommendations.</p>
                    <div class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Enter your email address">
                        <button class="newsletter-btn">Subscribe Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
