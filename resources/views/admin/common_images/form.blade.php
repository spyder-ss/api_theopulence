@extends('layouts.admin.main')
@section('content')
    <div class="flex-1 p-6 overflow-auto bg-gray-50">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">
                            {{ $module_name ?? 'Common Image Management' }}
                        </h1>
                        <p class="text-gray-600 text-lg">Create and manage common images</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.common-images.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Common Images</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('admin.common-images.add', isset($model_data) ? ['id' => $model_data->id] : []) }}"
                method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Image Details</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="common_image_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>

                            <select name="common_image_category_id" id="common_image_category_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('common_image_category_id') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (isset($model_data) && $model_data->common_image_category_id == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('common_image_category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Alt Text <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="alt_text"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('alt_text') border-red-500 @enderror"
                                id="alt_text" placeholder="Enter Alt Text"
                                value="{{ old('alt_text', $model_data->alt_text ?? '') }}">

                            @error('alt_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>

                            <input type="number" name="sort_order"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('sort_order') border-red-500 @enderror"
                                id="sort_order" placeholder="Enter Sort Order"
                                value="{{ old('sort_order', $model_data->sort_order ?? '0') }}">

                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-3">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                                Image(s)
                            </label>

                            <input type="file" name="images[]" multiple
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('images.*') border-red-500 @enderror"
                                id="images">

                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if(isset($model_data) && $model_data->image)
                                <div class="mt-4 flex items-center space-x-2">
                                    <img src="{{ asset('storage/common_images/' . $model_data->id . '/' . $model_data->image) }}"
                                        alt="{{ $model_data->alt_text }}" class="w-20 h-20 object-cover rounded">
                                    <span class="text-sm text-gray-500">Current image</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.common-images.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($model_data->id) ? 'Update Image' : 'Create Image' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection