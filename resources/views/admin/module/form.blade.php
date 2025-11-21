@extends('layouts.admin.main')
@section('content')
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
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $module_name ?? 'Module Management' }}</h1>
                        <p class="text-gray-600 text-lg">Add and manage module information</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/modules') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Modules</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST" action="{{ url(getAdminRouteName() . '/modules/add' . (isset($module->id) ? '?id=' . $module->id : '')) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Basic Information Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Module Details</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Key -->
                        @if(!isset($module->id))
                            <div class="lg:col-span-2">
                                <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Module Key <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="key"
                                       name="key"
                                       value="{{ old('key', $module->key ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('key') border-red-500 @enderror"
                                       placeholder="Enter module key (e.g., USER_MODULE)"
                                       required>
                                @error('key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="lg:col-span-2">
                                <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Module Key
                                </label>
                                <input type="text"
                                       id="key"
                                       name="key"
                                       value="{{ old('key', $module->key ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed @error('key') border-red-500 @enderror"
                                       readonly>
                                @error('key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Name -->
                        <div class="lg:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Module Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $module->name ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('name') border-red-500 @enderror"
                                   placeholder="Enter module name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permission -->
                        <div>
                            <label for="permission" class="block text-sm font-medium text-gray-700 mb-2">
                                Permission <span class="text-red-500">*</span>
                            </label>
                            <select id="permission"
                                    name="permission"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('permission') border-red-500 @enderror">
                                <option value="">Select Permission</option>
                                <option value="read" {{ old('permission', $module->permission ?? '') == 'read' ? 'selected' : '' }}>Read</option>
                                <option value="write" {{ old('permission', $module->permission ?? '') == 'write' ? 'selected' : '' }}>Write</option>
                                <option value="delete" {{ old('permission', $module->permission ?? '') == 'delete' ? 'selected' : '' }}>Delete</option>
                                <option value="admin" {{ old('permission', $module->permission ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('permission')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status"
                                    name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('status') border-red-500 @enderror">
                                <option value="1" {{ old('status', $module->status ?? 1) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $module->status ?? 1) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/modules') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($module->id) ? 'Update Module' : 'Create Module' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
