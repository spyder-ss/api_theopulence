@extends('layouts.admin.main')
@section('content')
    <div class="flex-1 p-6 overflow-auto bg-gray-50">
        <!-- Header Section -->
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
                            {{ $module_name ?? 'Common Image Category Management' }}
                        </h1>

                        <p class="text-gray-600 text-lg">Create and manage common image categories</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/common-image-categories') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>

                        <span>Back to Categories</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST"
                action="{{ isset($model_data) ? route('admin.common-image-categories.edit', $model_data->id) : route('admin.common-image-categories.add') }}"
                class="space-y-8" enctype="multipart/form-data">

                @csrf

                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800">Basic Information</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>

                            <input type="text" id="name" name="name" value="{{ old('name', $model_data->name ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Enter category name" required>

                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(isset($model_data))
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                    Slug
                                </label>

                                <input type="text" id="slug" name="slug" value="{{ old('slug', $model_data->slug ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200"
                                    placeholder="Auto-generated slug" readonly>
                            </div>
                        @endif

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title
                            </label>

                            <input type="text" id="title" name="title" value="{{ old('title', $model_data->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('title') border-red-500 @enderror"
                                placeholder="Enter category title">

                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Image
                            </label>

                            <input type="file" id="image" name="image"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('image') border-red-500 @enderror">

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if (isset($model_data) && $model_data->image)
                                <div class="mt-4">
                                    <img src="{{ asset('storage/common_image_categories/' . $model_data->image) }}"
                                        alt="{{ $model_data->name }}" class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>

                            <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('status') border-red-500 @enderror">
                                <option value="1" {{ old('status', $model_data->status ?? '1') == '1' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ old('status', $model_data->status ?? '1') == '0' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>

                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                        <div>
                            <label for="brief" class="block text-sm font-medium text-gray-700 mb-2">
                                Brief Description
                            </label>

                            <textarea name="brief"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('brief') border-red-500 @enderror"
                                placeholder="Short brief about the page">{{ old('brief', $model_data->brief ?? '') }}</textarea>

                            @error('brief')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/common-image-categories') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($model_data) ? 'Update Category' : 'Create Category' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection