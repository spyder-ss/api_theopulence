@extends('layouts.admin.main')
@section('content')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div class="flex-1 p-6 overflow-auto bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $module_name ?? 'Property Management' }}</h1>
                        <p class="text-gray-600 text-lg">Create and manage properties</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/properties') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Properties</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST" action="{{ url(getAdminRouteName() . '/properties/add' . (isset($property->id) ? '?id=' . $property->id : '')) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Property Details</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $property->title ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('title') border-red-500 @enderror"
                                   placeholder="Enter property title"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(isset($property->id))
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                    Slug
                                </label>
                                <input type="text"
                                       id="slug"
                                       name="slug"
                                       value="{{ old('slug', $property->slug ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('slug') border-red-500 @enderror"
                                       placeholder="Slug will be generated automatically"
                                       readonly>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text"
                                   id="location"
                                   name="location"
                                   value="{{ old('location', $property->location ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('location') border-red-500 @enderror"
                                   placeholder="Enter property location">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guest Capacity -->
                        <div>
                            <label for="guest_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Guest Capacity
                            </label>
                            <input type="number"
                                   id="guest_capacity"
                                   name="guest_capacity"
                                   value="{{ old('guest_capacity', $property->guest_capacity ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('guest_capacity') border-red-500 @enderror"
                                   placeholder="0">
                            @error('guest_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bedrooms -->
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                Bedrooms
                            </label>
                            <input type="number"
                                   id="bedrooms"
                                   name="bedrooms"
                                   value="{{ old('bedrooms', $property->bedrooms ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('bedrooms') border-red-500 @enderror"
                                   placeholder="0">
                            @error('bedrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                Bathrooms
                            </label>
                            <input type="number"
                                   id="bathrooms"
                                   name="bathrooms"
                                   value="{{ old('bathrooms', $property->bathrooms ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('bathrooms') border-red-500 @enderror"
                                   placeholder="0">
                            @error('bathrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                         <!-- Amenities (Multiple Selection Enabled) -->
                        <div>
                            <label for="amenities" class="block text-sm font-medium text-gray-700 mb-2">
                                Amenities
                            </label>
                            <select id="amenities"
                                    name="amenities[]"
                                    multiple
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('amenities') border-red-500 @enderror">
                                @if(isset($amenities) && $amenities->count() > 0)
                                    @foreach($amenities as $amenity)
                                        <option value="{{ $amenity->id }}"
                                            {{ (isset($property) && $property->amenities->contains($amenity->id)) ? 'selected' : '' }}>
                                            {{ $amenity->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('amenities')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Brief -->
                        <div class="lg:col-span-2">
                            <label for="property_brief" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Brief
                            </label>
                            <textarea id="property_brief"
                                      name="property_brief"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('property_brief') border-red-500 @enderror"
                                      placeholder="Brief description of the property">{{ old('property_brief', $property->property_brief ?? '') }}</textarea>
                            @error('property_brief')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Description -->
                        <div class="lg:col-span-2">
                            <label for="property_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Description
                            </label>
                            <textarea id="property_description"
                                      name="property_description"
                                      rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('property_description') border-red-500 @enderror"
                                      placeholder="Full description of the property">{{ old('property_description', $property->property_description ?? '') }}</textarea>
                            @error('property_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Experience -->
                        <div class="lg:col-span-2">
                            <label for="property_experience" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Experience
                            </label>
                            <textarea id="property_experience"
                                      name="property_experience"
                                      rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('property_experience') border-red-500 @enderror"
                                      placeholder="Describe the property experience">{{ old('property_experience', $property->property_experience ?? '') }}</textarea>
                            @error('property_experience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spaces -->
                        <div class="lg:col-span-2">
                            <label for="spaces" class="block text-sm font-medium text-gray-700 mb-2">
                                Spaces
                            </label>
                            <textarea id="spaces"
                                      name="spaces"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('spaces') border-red-500 @enderror"
                                      placeholder="Describe spaces">{{ old('spaces', $property->spaces ?? '') }}</textarea>
                            @error('spaces')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cancellation Policy -->
                        <div class="lg:col-span-2">
                            <label for="cancellation_policy" class="block text-sm font-medium text-gray-700 mb-2">
                                Cancellation Policy
                            </label>
                            <textarea id="cancellation_policy"
                                      name="cancellation_policy"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('cancellation_policy') border-red-500 @enderror"
                                      placeholder="Enter cancellation policy">{{ old('cancellation_policy', $property->cancellation_policy ?? '') }}</textarea>
                            @error('cancellation_policy')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Other Important Information -->
                        <div class="lg:col-span-2">
                            <label for="other_important_information" class="block text-sm font-medium text-gray-700 mb-2">
                                Other Important Information
                            </label>
                            <textarea id="other_important_information"
                                      name="other_important_information"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('other_important_information') border-red-500 @enderror"
                                      placeholder="Enter other important information">{{ old('other_important_information', $property->other_important_information ?? '') }}</textarea>
                            @error('other_important_information')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- FAQs -->
                        <div class="lg:col-span-2">
                            <label for="faqs" class="block text-sm font-medium text-gray-700 mb-2">
                                FAQs
                            </label>
                            <textarea id="faqs"
                                      name="faqs"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('faqs') border-red-500 @enderror"
                                      placeholder="Enter FAQs">{{ old('faqs', $property->faqs ?? '') }}</textarea>
                            @error('faqs')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Multiple Image Upload -->
                        <div class="lg:col-span-2">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Images
                            </label>
                            <input type="file"
                                   id="images"
                                   name="images[]"
                                   accept="image/*"
                                   multiple
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('images') border-red-500 @enderror">
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if(isset($property) && $property->images->count() > 0)
                                <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($property->images as $image)
                                        <div class="relative border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                            <img src="{{ asset('storage/property_images/' . $image->property_id . '/' . $image->image_path) }}" alt="Property Image" class="w-full h-32 object-cover">
                                            <div class="absolute top-2 right-2">
                                                <button type="button" onclick="deletePropertyImage({{ $image->id }})" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1 text-xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-2 flex items-center justify-center bg-gray-50">
                                                <input type="radio"
                                                       name="main_image_id"
                                                       value="{{ $image->id }}"
                                                       {{ $image->is_main ? 'checked' : '' }}
                                                       class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
                                                <label class="ml-2 text-sm text-gray-700">Main</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">SEO Settings</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title', $property->meta_title ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('meta_title') border-red-500 @enderror"
                                   placeholder="SEO meta title">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keyword" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Keywords
                            </label>
                            <input type="text"
                                   id="meta_keyword"
                                   name="meta_keyword"
                                   value="{{ old('meta_keyword', $property->meta_keyword ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('meta_keyword') border-red-500 @enderror"
                                   placeholder="SEO meta keywords">
                            @error('meta_keyword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div class="lg:col-span-2">
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('meta_description') border-red-500 @enderror"
                                      placeholder="SEO meta description">{{ old('meta_description', $property->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/properties') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($property->id) ? 'Update Property' : 'Create Property' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function deleteImage(id, type) {
            if (confirm('Are you sure you want to delete this image?')) {
                let url = '{{ url(getAdminRouteName() . "/properties/ajax_img_delete") }}';
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

        function deletePropertyImage(imageId) {
            if (confirm('Are you sure you want to delete this property image?')) {
                let url = '{{ url(getAdminRouteName() . "/properties/ajax_property_img_delete") }}';
                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('image_id', imageId);

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
                    alert('An error occurred while deleting the property image.');
                });
            }
        }
    </script>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#amenities').select2();

                // Auto-generate slug on title change for new properties
                $('#title').on('keyup', function() {
                    if (!$('#slug').length) { // Only generate if slug field is not present (i.e., on create page)
                        let title = $(this).val();
                        if (title.length > 0) {
                            $.ajax({
                                url: '{{ url(getAdminRouteName() . "/properties/generate-slug") }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    title: title
                                },
                                success: function(response) {
                                    // If the slug field is not present, we don't need to update it visually
                                    // The backend will handle the generation.
                                    // This AJAX call is primarily for demonstration or if we decide to show it dynamically later.
                                }
                            });
                        }
                    }
                });
            });
        </script>

        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
                // Toastr configuration
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                // Display success message
                @if(session('success'))
                    toastr.success("{{ session('success') }}");
                @endif

                // Display validation errors
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
            });
        </script>
    @endpush
@endsection
