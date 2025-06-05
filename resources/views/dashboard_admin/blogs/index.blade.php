@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title> Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="dashboard-title">Blogs</h4>
                    <a href="{{ route('admin_blog.create') }}" class="btn process-pay">Add New Blog</a>
                </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Blog Name</th>
                      <th scope="col">Slug</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td data-label="Id">{{ $blog->id }}</td>
                            <td data-label="Blog Name">{{ $blog->title }}</td>
                            <td data-label="Slug">{{ $blog->slug }}</td>
                            <td data-label="Status">{{ $blog->status }}</td>
                            <td data-label="Action">
                                <div class="dropdown">
                                    <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                      OPTIONS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      <li><a class="dropdown-item" href="{{ route('admin_blog.edit', $blog->id) }}">Edit</a></li>
                                      <form action="{{ route('admin_blog.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <li><button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</button></li>
                                      </form>
                                        @if ($blog->status == '1')
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin_blog.status', $blog->id) }}" title="Deactivate">
                                                    Deactivate
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin_blog.status', $blog->id) }}" title="Activate">
                                                    Activate
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6>No Blogs Found</h6>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
                {{ $blogs->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
