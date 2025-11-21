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
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ $module_name ?? 'Role Management' }}</h1>
                        <p class="text-gray-600 text-lg">Manage role details and assign permissions</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ url(getAdminRouteName() . '/roles') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Roles</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form method="POST" action="{{ url(getAdminRouteName() . '/roles/' . (isset($role->id) ? 'edit/' . $role->id : 'add')) }}" class="space-y-8">
                @csrf
                @if(isset($role->id))
                    @method('PUT')
                @endif

                <!-- Basic Information Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Basic Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $role->name ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('name') border-red-500 @enderror"
                                   placeholder="Enter role name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                <option value="1" {{ old('status', $role->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $role->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Role For (only for new roles) -->
                    @if(!isset($role->id))
                    <div>
                        <label for="role_for" class="block text-sm font-medium text-gray-700 mb-2">
                            Role For <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="role_for"
                               name="role_for"
                               value="{{ old('role_for') }}"
                               class="w-full md:w-1/2 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200 @error('role_for') border-red-500 @enderror"
                               placeholder="e.g., admin, manager, user"
                               required>
                        @error('role_for')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">This is used as a slug for the role identifier.</p>
                    </div>
                    @endif
                </div>

                <!-- Permissions Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Permissions</h3>
                        <span class="text-sm text-gray-500">(Assign access permissions for this role)</span>
                    </div>

                    @if(isset($modules) && $modules->count() > 0)
                        <div class="space-y-6">
                            @php
                                $currentPermissions = isset($role->permission) ? json_decode($role->permission, true) : [];
                            @endphp

                            @foreach($modules as $module)
                                @php
                                    $modulePermissions = explode(',', $module->permission);
                                    $moduleKey = strtolower($module->key);
                                @endphp

                                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                    <div class="flex items-center mb-4">
                                        <h4 class="text-lg font-medium text-gray-800">{{ $module->name }}</h4>
                                        <span class="ml-2 text-sm text-gray-500">({{ $module->key }})</span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($modulePermissions as $permission)
                                            @php
                                                $permissionKey = trim($permission);
                                                $checkboxName = "permission[{$moduleKey}][]";
                                                $checkboxValue = $permissionKey;
                                                $isChecked = isset($currentPermissions[$moduleKey]) && in_array($permissionKey, $currentPermissions[$moduleKey]);
                                            @endphp

                                            <label class="flex items-center space-x-3 cursor-pointer">
                                                <input type="checkbox"
                                                       name="{{ $checkboxName }}"
                                                       value="{{ $checkboxValue }}"
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $permissionKey }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No modules found</h3>
                            <p class="mt-1 text-sm text-gray-500">Create modules first to assign permissions.</p>
                            <div class="mt-6">
                                <a href="{{ url(getAdminRouteName() . '/module/add') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Module
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ url(getAdminRouteName() . '/roles') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        {{ isset($role->id) ? 'Update Role' : 'Create Role' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
