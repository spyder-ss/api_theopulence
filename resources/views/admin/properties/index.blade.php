@extends('layouts.admin.main')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Properties</h4>
                            <a href="{{ route(getAdminRouteName() . '.properties.add') }}" class="btn btn-primary">Add New</a>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Location</th>
                                            <th>Guest Capacity</th>
                                            <th>Bedrooms</th>
                                            <th>Bathrooms</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($properties as $property)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $property->title }}</td>
                                                <td>{{ $property->slug }}</td>
                                                <td>{{ $property->location }}</td>
                                                <td>{{ $property->guest_capacity }}</td>
                                                <td>{{ $property->bedrooms }}</td>
                                                <td>{{ $property->bathrooms }}</td>
                                                <td>
                                                    <a href="{{ route(getAdminRouteName() . '.properties.edit', $property->id) }}"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    <form
                                                        action="{{ route(getAdminRouteName() . '.properties.delete', $property->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
