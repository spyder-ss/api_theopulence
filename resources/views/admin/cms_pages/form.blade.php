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
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $module_name ?? 'CMS Page Management' }}</h1>
                        <p class="text-gray-600 text-lg">Create and manage page content</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/cms_pages') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Pages</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST"
                action="{{ url(getAdminRouteName() . '/cms_pages/add' . (isset($model_data->id) ? '?id=' . $model_data->id : '')) }}"
                enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Basic Information Section -->
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

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="lg:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $model_data->name ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Enter page name" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Page -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent Page
                            </label>
                            <select id="parent_id" name="parent_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('parent_id') border-red-500 @enderror">
                                <option value="0">Choose parent page (optional)</option>
                                @if(isset($model_data) && $model_data->parent_id == 0)
                                    <option value="{{ $model_data->parent_id }}" selected>Main Page</option>
                                @endif
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Template -->
                        <div>
                            <label for="template" class="block text-sm font-medium text-gray-700 mb-2">
                                Template
                            </label>
                            <select id="template" name="template"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('template') border-red-500 @enderror">
                                <option value="">Choose template</option>
                                @if(isset($files) && $files)
                                    @foreach($files as $file)
                                        <option value="{{ $file }}" {{ old('template', $model_data->template ?? '') == $file ? 'selected' : '' }}>{{ $file }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('template')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number" id="sort_order" name="sort_order"
                                value="{{ old('sort_order', $model_data->sort_order ?? 0) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('sort_order') border-red-500 @enderror"
                                placeholder="0">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('status') border-red-500 @enderror">
                                <option value="1" {{ old('status', $model_data->status ?? 1) == '1' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ old('status', $model_data->status ?? 1) == '0' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Content</h3>
                    </div>

                    <div class="space-y-6">
                        <!-- Brief -->
                        <div>
                            <label for="brief" class="block text-sm font-medium text-gray-700 mb-2">
                                Brief Description
                            </label>
                            <textarea id="brief" name="brief"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('brief') border-red-500 @enderror"
                                placeholder="Short brief about the page">{{ old('brief', $model_data->brief ?? '') }}</textarea>
                            @error('brief')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Page Content -->
                        <div>
                            <label for="page_content" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Content
                            </label>
                            <textarea id="page_content" name="page_content"
                                class="w-full border border-gray-300 rounded-lg ckeditor @error('page_content') border-red-500 @enderror"
                                placeholder="Detailed page content">{{ old('page_content', $model_data->page_content ?? '') }}</textarea>
                            @error('page_content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Media Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Media & Images</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Image (Optional)
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('image') border-red-500 @enderror">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF up to 2MB</p>
                            @if(isset($model_data->image) && $model_data->image)
                                <div class="mt-2 flex items-center space-x-2">
                                    <img src="{{ asset('storage/cms_page/' . $model_data->id . '/' . $model_data->image) }}"
                                        alt="Current Image" class="w-20 h-20 object-cover rounded-lg">
                                    <span class="text-sm text-gray-500">Current image</span>
                                    <button type="button" onclick="deleteImage({{ $model_data->id }}, 'image')"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Banner Image -->
                        <div>
                            <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Banner Image (Optional)
                            </label>
                            <input type="file" id="banner_image" name="banner_image" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('banner_image') border-red-500 @enderror">
                            @error('banner_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF up to 2MB</p>
                            @if(isset($model_data->banner_image) && $model_data->banner_image)
                                <div class="mt-2 flex items-center space-x-2">
                                    <img src="{{ asset('storage/cms_page/' . $model_data->id . '/' . $model_data->banner_image) }}"
                                        alt="Banner Image" class="w-20 h-20 object-cover rounded-lg">
                                    <span class="text-sm text-gray-500">Current banner</span>
                                    <button type="button" onclick="deleteImage({{ $model_data->id }}, 'banner_image')"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Mobile Banner Image -->
                        <div>
                            <label for="mobile_banner_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Mobile Banner Image (Optional)
                            </label>
                            <input type="file" id="mobile_banner_image" name="mobile_banner_image" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('mobile_banner_image') border-red-500 @enderror">
                            @error('mobile_banner_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF up to 2MB</p>
                            @if(isset($model_data->mobile_banner_image) && $model_data->mobile_banner_image)
                                <div class="mt-2 flex items-center space-x-2">
                                    <img src="{{ asset('storage/cms_page/' . $model_data->id . '/' . $model_data->mobile_banner_image) }}"
                                        alt="Mobile Banner" class="w-20 h-20 object-cover rounded-lg">
                                    <span class="text-sm text-gray-500">Current mobile banner</span>
                                    <button type="button" onclick="deleteImage({{ $model_data->id }}, 'mobile_banner_image')"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Links & Navigation -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Links & Navigation</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- External Link -->
                        <div>
                            <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                                External Link
                            </label>
                            <input type="url" id="link" name="link" value="{{ old('link', $model_data->link ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('link') border-red-500 @enderror"
                                placeholder="https://example.com">
                            @error('link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Video Link -->
                        <div>
                            <label for="video_link" class="block text-sm font-medium text-gray-700 mb-2">
                                Video Link
                            </label>
                            <input type="url" id="video_link" name="video_link"
                                value="{{ old('video_link', $model_data->video_link ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('video_link') border-red-500 @enderror"
                                placeholder="https://youtube.com/watch?v=...">
                            @error('video_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Title
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $model_data->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('title') border-red-500 @enderror"
                                placeholder="SEO friendly title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Heading -->
                        <div>
                            <label for="heading" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Heading
                            </label>
                            <input type="text" id="heading" name="heading"
                                value="{{ old('heading', $model_data->heading ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('heading') border-red-500 @enderror"
                                placeholder="Display heading">
                            @error('heading')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub Heading -->
                        <div class="lg:col-span-2">
                            <label for="sub_heading" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub Heading
                            </label>
                            <input type="text" id="sub_heading" name="sub_heading"
                                value="{{ old('sub_heading', $model_data->sub_heading ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('sub_heading') border-red-500 @enderror"
                                placeholder="Optional sub heading">
                            @error('sub_heading')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">SEO Settings</h3>
                    </div>

                    <div class="space-y-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" id="meta_title" name="meta_title"
                                value="{{ old('meta_title', $model_data->meta_title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('meta_title') border-red-500 @enderror"
                                placeholder="Page meta title for SEO">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keyword" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Keywords
                            </label>
                            <input type="text" id="meta_keyword" name="meta_keyword"
                                value="{{ old('meta_keyword', $model_data->meta_keyword ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('meta_keyword') border-red-500 @enderror"
                                placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keyword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea id="meta_description" name="meta_description"
                                class="w-full border border-gray-300 rounded-lg @error('meta_description') border-red-500 @enderror"
                                placeholder="Brief meta description for search engines">{{ old('meta_description', $model_data->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/cms_pages') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($model_data->id) ? 'Update Page' : 'Create Page' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteImage(id, type) {
            if (confirm('Are you sure you want to delete this image?')) {
                let url = '{{ url(getAdminRouteName() . "/cms_pages/ajax_img_delete") }}';
                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('id', id);
                formData.append('type', type);

                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the image.');
                    });
            }
        }
    </script>
@endpush
