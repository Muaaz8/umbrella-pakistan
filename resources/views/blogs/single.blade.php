@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (isset($blog->meta_tags))
        @foreach ($blog->meta_tags as $key => $value)
            <meta name="{{ $key }}" content="{{ $value }}" />
        @endforeach
    @endif
    <style>
      :root {
        --red: #c80919;
        --blue: #2964bc;
        --maroon: #c80919;
        --navy-blue: #082755;
        --green: #35b518;
        --lh: 1.4rem;
        --lightgray: #f5f5f5;
        --lightblue: #2964bca3;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Poppins", sans-serif;
        background-color: var(--lightgray);
        color: #333;
        line-height: var(--lh);
      }

      /* Container */
      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
      }

      .blog-title {
        color: var(--navy-blue);
        font-size: 38px;
        margin-bottom: 16px;
        line-height: 1.2;
        font-weight: 700;
      }

      .blog-meta {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        color: #666;
        font-size: 14px;
      }

      .author {
        display: flex;
        align-items: center;
        margin-right: 25px;
      }

      .author-img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: var(--blue);
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        font-weight: 600;
      }

      .date {
        display: flex;
        align-items: center;
      }

      .date i {
        margin-right: 6px;
        color: var(--blue);
      }

      .blog-excerpt {
        margin-bottom: 30px;
        color: #555;
        font-size: 16px;
        line-height: 1.8;
      }

      .reads-more {
        display: inline-flex;
        align-items: center;
        background-color: var(--blue);
        color: white;
        padding: 12px 24px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(41, 100, 188, 0.3);
      }

      .reads-more i {
        margin-left: 8px;
        transition: transform 0.3s ease;
      }

      .reads-more:hover {
        background-color: var(--navy-blue);
        transform: translateY(-3px);
      }

      .reads-more:hover i {
        transform: translateX(5px);
      }

      .blog-content {
        background-color: #fff;
        border-radius: 16px;
        padding: 50px;
        margin-bottom: 40px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
      }

      .blog-content h2 {
        color: var(--navy-blue);
        margin: 35px 0 20px;
        font-size: 28px;
        font-weight: 700;
        position: relative;
        padding-bottom: 12px;
      }

      .blog-content h2:first-child {
        margin-top: 0;
      }

      .blog-content h2::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: var(--red);
        border-radius: 3px;
      }

      .blog-content h3 {
        color: var(--blue);
        margin: 30px 0 15px;
        font-size: 22px;
        font-weight: 600;
      }

      .blog-content p {
        margin-bottom: 25px;
        font-size: 16px;
        color: #444;
        line-height: 1.8;
      }

      .blog-content ul,
      .blog-content ol {
        margin-bottom: 25px;
        padding-left: 20px;
      }

      .blog-content li {
        margin-bottom: 12px;
        position: relative;
      }

      .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 25px 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      }

      .blog-content blockquote {
        border-left: 4px solid var(--red);
        padding: 15px 25px;
        margin: 25px 0;
        font-style: italic;
        color: #555;
        background-color: var(--lightgray);
        border-radius: 0 8px 8px 0;
      }

      /* Service Cards */
      .service-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin: 40px 0;
      }

      .service-card {
        background: linear-gradient(145deg, #ffffff, var(--lightgray));
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(200, 9, 25, 0.1);
      }

      .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(200, 9, 25, 0.15);
        border-color: rgba(200, 9, 25, 0.3);
      }

      .service-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(
          135deg,
          var(--blue) 0%,
          var(--navy-blue) 100%
        );
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        box-shadow: 0 8px 20px rgba(8, 39, 85, 0.2);
      }

      .service-icon svg {
        width: 35px;
        height: 35px;
        fill: white;
      }

      .service-title {
        color: var(--navy-blue);
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: 600;
      }

      .service-description {
        font-size: 15px;
        color: #555;
        margin-bottom: 20px;
        line-height: 1.7;
      }

      .service-card ul {
        list-style: none;
        margin-top: 20px;
      }

      .service-card li {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        color: #444;
      }

      .service-card li svg {
        min-width: 18px;
        height: 18px;
        fill: var(--green);
        margin-right: 12px;
        margin-top: 4px;
      }

      /* CTA Section */
      .cta-section {
        background: linear-gradient(
          135deg,
          var(--blue) 0%,
          var(--navy-blue) 100%
        );
        color: white;
        border-radius: 16px;
        padding: 50px;
        margin: 50px 0;
        text-align: center;
        box-shadow: 0 15px 35px rgba(8, 39, 85, 0.3);
        position: relative;
        overflow: hidden;
      }

      .cta-section::before {
        content: "";
        /* position: absolute; */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.3;
      }

      .cta-section h3 {
        font-size: 32px;
        margin-bottom: 20px;
        font-weight: 700;
        position: relative;
      }

      .cta-section p {
        margin-bottom: 30px;
        font-size: 17px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        opacity: 0.9;
      }

      .cta-button {
        display: inline-flex;
        align-items: center;
        background-color: var(--red);
        color: white;
        padding: 14px 32px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      }

      .cta-button i {
        margin-left: 8px;
        transition: transform 0.3s ease;
      }

      .cta-button:hover {
        background-color: #a60715;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      }

      .cta-button:hover i {
        transform: translateX(5px);
      }

      /* Header Navigation */
      /* .header {
        background-color: #fff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 15px 0;
        position: sticky;
        top: 0;
        z-index: 100;
      }

      .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .logo {
        font-size: 24px;
        font-weight: 700;
        color: var(--navy-blue);
        text-decoration: none;
        display: flex;
        align-items: center;
      }

      .logo img {
        height: 40px;
        margin-right: 10px;
      } */

      .nav-menu {
        display: flex;
        list-style: none;
      }

      .nav-item {
        margin-left: 30px;
      }

      .nav-link {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        font-size: 16px;
        transition: color 0.3s ease;
        position: relative;
      }

      .nav-link:hover {
        color: var(--blue);
      }

      .nav-link.active {
        color: var(--blue);
      }

      .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--blue);
        border-radius: 2px;
      }

      /* Top Banner */
      .top-banner {
        background-color: var(--navy-blue);
        color: white;
        padding: 8px 0;
        text-align: center;
        font-size: 14px;
      }

      .top-banner a {
        color: white;
        text-decoration: underline;
      }

      /* Back to Top Button */
      .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: var(--red);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 15px rgba(200, 9, 25, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 999;
        opacity: 0;
        visibility: hidden;
      }

      .back-to-top.show {
        opacity: 1;
        visibility: visible;
      }

      .back-to-top:hover {
        background-color: #a60715;
        transform: translateY(-5px);
      }

      /* Blog Header Section */
      .blog-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin: 50px 0;
        align-items: center;
      }

      .header-image img {
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      }

      .header-tag {
        background-color: var(--lightblue);
        color: white;
        padding: 6px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 15px;
        letter-spacing: 1px;
      }

      /* Footer */
      .footer {
        background-color: var(--navy-blue);
        color: white;
        padding: 60px 0 30px;
      }

      .footer-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
      }

      .footer-logo {
        margin-bottom: 20px;
        display: inline-block;
      }

      .footer-logo img {
        height: 50px;
      }

      .footer-about p {
        margin-bottom: 20px;
        opacity: 0.8;
        line-height: 1.8;
      }

      .footer-heading {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
      }

      .footer-heading::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: var(--red);
      }

      .footer-links {
        list-style: none;
      }

      .footer-links li {
        margin-bottom: 12px;
      }

      .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
      }

      .footer-links a i {
        margin-right: 10px;
        color: var(--red);
        font-size: 14px;
      }

      .footer-links a:hover {
        color: white;
      }

      .social-links {
        display: flex;
        margin-top: 20px;
      }

      .social-links a {
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 10px;
        transition: all 0.3s ease;
      }

      .social-links a:hover {
        background-color: var(--red);
        transform: translateY(-5px);
      }

      .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        text-align: center;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
      }

      /* Responsive Design */
      @media (max-width: 1100px) {
        .blog-header {
          grid-template-columns: 1fr;
        }

        .blog-title {
          font-size: 32px;
        }

        .blog-content {
          padding: 40px;
        }

        .service-cards {
          grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }

        .footer-content {
          grid-template-columns: 1fr 1fr;
          gap: 30px;
        }
      }

      @media (max-width: 900px) {
        .nav-menu {
          display: none;
        }

        .blog-title {
          font-size: 28px;
        }

        .blog-content {
          padding: 30px;
        }

        .blog-content h2 {
          font-size: 24px;
        }

        .service-cards {
          grid-template-columns: 1fr;
        }

        .cta-section {
          padding: 40px 30px;
        }

        .cta-section h3 {
          font-size: 26px;
        }
      }

      @media (max-width: 768px) {
        .header-container {
          flex-direction: column;
          align-items: flex-start;
        }

        .blog-content {
          padding: 25px;
        }

        .footer-content {
          grid-template-columns: 1fr;
          gap: 30px;
        }
      }

      @media (max-width: 480px) {
        .container {
          padding: 0 15px;
        }

        .top-banner {
          font-size: 12px;
          padding: 6px 0;
        }

        .blog-title {
          font-size: 24px;
        }

        .blog-content {
          padding: 20px;
        }

        .blog-content h2 {
          font-size: 22px;
        }

        .cta-section {
          padding: 30px 20px;
        }

        .cta-section h3 {
          font-size: 22px;
        }

        .service-icon {
          width: 60px;
          height: 60px;
        }

        .service-icon svg {
          width: 30px;
          height: 30px;
        }

        .back-to-top {
          bottom: 20px;
          right: 20px;
          width: 40px;
          height: 40px;
          font-size: 16px;
        }
      }
    </style>
@endsection


@section('page_title')
    @if ($blog->meta_title)
        <title>{{ $blog->meta_title }}</title>
    @else
        <title>Community Healthcare Clinics</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection
@section('content')
    <div class="container">
        <section class="blog-header">
            <div class="header-image">
                <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" />
            </div>
            <div class="header-content">
                {{-- <span class="header-tag">DENTAL CARE</span> --}}
                <h1 class="blog-title">{{ $blog->title }}</h1>
                <div class="blog-meta">
                    <div class="date">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}</span>
                    </div>
                </div>
                <p class="blog-excerpt">{{ $blog->excerpt }}</p>
                <a href="#blog_content" class="reads-more">Continue Reading <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </section>

        <section class="blog-content rich-text-content" id="blog_content">
            {!! $blog->content !!}
        </section>

        <section class="cta-section">
            <h3>Need urgent health care?</h3>
            <p>
                Our specialists are ready to help you with any health emergency. Don't
                wait—contact us now for immediate assessment and guidance.
            </p>
            <a href="{{route('contact_us')}}" class="cta-button">Contact Us Now <i class="fa-solid fa-arrow-right"></i></a>
        </section>
    </div>
@endsection
