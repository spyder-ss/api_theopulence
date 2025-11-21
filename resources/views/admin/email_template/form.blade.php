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
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $module_name ?? 'Email Template Management' }}
                        </h1>
                        <p class="text-gray-600 text-lg">Create and manage email template details</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/email_templates') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Email Templates</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST"
                action="{{ url(getAdminRouteName() . '/email_templates/add' . (isset($email_template->id) ? '?id=' . $email_template->id : '')) }}"
                class="space-y-8">
                @csrf

                <!-- Template Information Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Email Template Details</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @if(!isset($email_template->id))
                            <!-- Key (only for add) -->
                            <div class="lg:col-span-2">
                                <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Key <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="key" name="key" value="{{ old('key') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('key') border-red-500 @enderror"
                                    placeholder="e.g., REGISTRATION_EMAIL" required>
                                @error('key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Unique identifier for the email template (will be
                                    converted to uppercase slug)</p>
                            </div>
                        @endif

                        <!-- Title -->
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $email_template->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('title') border-red-500 @enderror"
                                placeholder="Enter template title" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="lg:col-span-2">
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="value" name="value" rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 ckeditor @error('value') border-red-500 @enderror"
                                placeholder="Enter the customer's content">{{ old('value', $model_data->value ?? '') }}</textarea>
                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200">
                                <option value="1" {{ old('status', $email_template->status ?? 1) == '1' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ old('status', $email_template->status ?? 1) == '0' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/email_templates') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($email_template->id) ? 'Update Template' : 'Create Template' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
