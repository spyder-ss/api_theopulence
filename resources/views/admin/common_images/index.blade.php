@extends('layouts.admin.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $module_name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $module_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $module_name }} List</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.common-images.add') }}" class="btn btn-primary btn-sm">Add New</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Image</th>
                                        <th>Category</th>
                                        <th>Alt Text</th>
                                        <th>Sort Order</th>
                                        <th>Status</th>
                                        <th style="width: 150px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($model_data_lists->count() > 0)
                                        @foreach($model_data_lists as $key => $image)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if($image->image)
                                                        <img src="{{ asset('storage/common_images/' . $image->id . '/' . $image->image) }}"
                                                            alt="{{ $image->alt_text }}" width="100">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>{{ $image->category->name ?? 'N/A' }}</td>
                                                <td>{{ $image->alt_text }}</td>
                                                <td>{{ $image->sort_order }}</td>
                                                <td>
                                                    @if($image->status == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.common-images.add', ['id' => $image->id]) }}"
                                                        class="btn btn-info btn-sm">Edit</a>
                                                    <form action="{{ route('admin.common-images.delete', ['id' => $image->id]) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this image?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No Common Images Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection