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
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $module_name ?? 'Website Setting Management' }}</h1>
                        <p class="text-gray-600 text-lg">Configure website settings and values</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/website_settings') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Settings</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST" action="{{ url(getAdminRouteName() . '/website_settings/' . (isset($website_setting->id) ? 'edit/' . $website_setting->id : 'add')) }}" class="space-y-8" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Setting Details</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Setting Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Setting Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $website_setting->name ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('name') border-red-500 @enderror"
                                   placeholder="Enter setting name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Setting Key -->
                        @if(!isset($website_setting->id))
                        <div>
                            <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                                Setting Key <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="key"
                                   name="key"
                                   value="{{ old('key') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('key') border-red-500 @enderror"
                                   placeholder="e.g., APP_NAME, EMAIL_HOST"
                                   required>
                            @error('key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">This will be converted to uppercase automatically</p>
                        </div>
                        @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Setting Key
                            </label>
                            <input type="text"
                                   value="{{ $website_setting->key }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Key cannot be changed after creation</p>
                        </div>
                        @endif

                        <!-- Setting Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Setting Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type"
                                    name="type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('type') border-red-500 @enderror"
                                    {{ isset($website_setting->id) ? 'disabled' : '' }}
                                    required>
                                <option value="">Select Type</option>
                                <option value="text" {{ old('type', $website_setting->type ?? '') == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="email" {{ old('type', $website_setting->type ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="password" {{ old('type', $website_setting->type ?? '') == 'password' ? 'selected' : '' }}>Password</option>
                                <option value="number" {{ old('type', $website_setting->type ?? '') == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="textarea" {{ old('type', $website_setting->type ?? '') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="boolean" {{ old('type', $website_setting->type ?? '') == 'boolean' ? 'selected' : '' }}>Boolean (Yes/No)</option>
                                <option value="url" {{ old('type', $website_setting->type ?? '') == 'url' ? 'selected' : '' }}>URL</option>
                                <option value="file" {{ old('type', $website_setting->type ?? '') == 'file' ? 'selected' : '' }}>File</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if(isset($website_setting->id))
                                <input type="hidden" name="type" value="{{ $website_setting->type }}">
                            @endif
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status"
                                    name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="1" {{ old('status', $website_setting->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $website_setting->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Setting Value Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Setting Value</h3>
                    </div>

                    <div id="valueContainer">
                        @php
                            $settingType = old('type', $website_setting->type ?? 'text');
                            $settingValue = old('value', $website_setting->value ?? '');
                        @endphp

                        @if($settingType === 'file')
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                    Value <span class="text-red-500">*</span>
                                </label>
                                <input type="file"
                                       id="value"
                                       name="value"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('value') border-red-500 @enderror">
                                @if(isset($website_setting->id) && $website_setting->value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/settings/' . $website_setting->value) }}" alt="Current Image" class="h-20 w-20 object-cover rounded-md">
                                    </div>
                                @endif
                                @error('value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @elseif($settingType === 'textarea')
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                    Value <span class="text-red-500">*</span>
                                </label>
                                <textarea id="value"
                                          name="value"
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('value') border-red-500 @enderror"
                                          placeholder="Enter setting value">{{ $settingValue }}</textarea>
                                @error('value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @elseif($settingType === 'boolean')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">
                                    Value
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="radio"
                                               name="value"
                                               value="1"
                                               {{ old('value', $settingValue) == '1' ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <span class="ml-3">Yes / True</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio"
                                               name="value"
                                               value="0"
                                               {{ old('value', $settingValue) == '0' || empty($settingValue) ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <span class="ml-3">No / False</span>
                                    </label>
                                </div>
                                @error('value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                    Value <span class="text-red-500">*</span>
                                </label>
                                
                                <input type="{{ $settingType === 'password' ? 'password' : ($settingType === 'email' ? 'email' : ($settingType === 'url' ? 'url' : ($settingType === 'number' ? 'number' : 'text'))) }}"
                                       id="value"
                                       name="value"
                                       value="{{ $settingType === 'password' ? '' : $settingValue }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('value') border-red-500 @enderror"
                                       placeholder="Enter setting value"
                                       {{ $settingType === 'password' && isset($website_setting->id) ? 'placeholder="Leave empty to keep current password"' : '' }}>
                                
                                       @error('value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if($settingType === 'url')
                                    <p class="mt-1 text-sm text-gray-500">Please include the protocol (http:// or https://)</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/website_settings') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($website_setting->id) ? 'Update Setting' : 'Create Setting' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dynamic input type based on setting type
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const valueContainer = document.getElementById('valueContainer');

            let html = '';

            if (type === 'file') {
                html = `
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                            Value <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                               id="value"
                               name="value"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200">
                    </div>
                `;
            } else if (type === 'textarea') {
                html = `
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                            Value <span class="text-red-500">*</span>
                        </label>
                        <textarea id="value"
                                  name="value"
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200"
                                  placeholder="Enter setting value"></textarea>
                    </div>
                `;
            } else if (type === 'boolean') {
                html = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">
                            Value
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio"
                                       name="value"
                                       value="1"
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3">Yes / True</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio"
                                       name="value"
                                       value="0"
                                       checked
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3">No / False</span>
                            </label>
                        </div>
                    </div>
                `;
            } else {
                const inputType = type === 'password' ? 'password' :
                                 type === 'email' ? 'email' :
                                 type === 'url' ? 'url' :
                                 type === 'number' ? 'number' : 'text';

                html = `
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                            Value <span class="text-red-500">*</span>
                        </label>
                        <input type="${inputType}"
                               id="value"
                               name="value"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200"
                               placeholder="Enter setting value">
                        ${type === 'url' ? '<p class="mt-1 text-sm text-gray-500">Please include the protocol (http:// or https://)</p>' : ''}
                    </div>
                `;
            }

            valueContainer.innerHTML = html;
        });
    </script>
@endsection
