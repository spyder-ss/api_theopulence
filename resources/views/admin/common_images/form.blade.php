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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $module_name }}</h3>
                        </div>
                        <form
                            action="{{ route('admin.common-images.add', isset($model_data) ? ['id' => $model_data->id] : []) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="common_image_category_id">Category</label>
                                    <select name="common_image_category_id" id="common_image_category_id"
                                        class="form-control @error('common_image_category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (isset($model_data) && $model_data->common_image_category_id == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('common_image_category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alt_text">Alt Text</label>
                                    <input type="text" name="alt_text"
                                        class="form-control @error('alt_text') is-invalid @enderror" id="alt_text"
                                        placeholder="Enter Alt Text"
                                        value="{{ old('alt_text', $model_data->alt_text ?? '') }}">
                                    @error('alt_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="sort_order">Sort Order</label>
                                    <input type="number" name="sort_order"
                                        class="form-control @error('sort_order') is-invalid @enderror" id="sort_order"
                                        placeholder="Enter Sort Order"
                                        value="{{ old('sort_order', $model_data->sort_order ?? '') }}">
                                    @error('sort_order')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="images">Image(s)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="images[]"
                                                class="custom-file-input @error('images.*') is-invalid @enderror"
                                                id="images" multiple>
                                            <label class="custom-file-label" for="images">Choose file(s)</label>
                                        </div>
                                    </div>
                                    @if(isset($model_data) && $model_data->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/common_images/' . $model_data->id . '/' . $model_data->image) }}"
                                                alt="{{ $model_data->alt_text }}" width="150">
                                        </div>
                                    @endif
                                    @error('images.*')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.common-images.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection