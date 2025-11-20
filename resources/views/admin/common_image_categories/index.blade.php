@extends('layouts.admin.main')
@section('content')
    <div class="flex-1 p-6 overflow-auto bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">Common Image Categories Management</h1>
                        <p class="text-gray-600 text-lg">Create and manage common image categories</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.common-image-categories.add') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Add New Category</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Listing Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header with Search and Filters -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex flex-col lg:flex-row gap-4 justify-between items-center">
                    <div class="flex-1 flex items-center gap-4">
                        <div class="relative flex-1 max-w-md">
                            <input type="text" id="categorySearch" placeholder="Search categories..."
                                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 11116.65 16.65z"/>
                            </svg>
                        </div>

                        <select id="statusFilter" class="border border-gray-300 rounded-lg text-sm px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                            <option value="all">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button id="clearFilters" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Category Details
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(isset($model_data_lists) && $model_data_lists->count() > 0)
                            @foreach ($model_data_lists as $category)
                                <tr class="hover:bg-gray-50 transition-colors duration-150" data-status="{{ $category->status }}" data-name="{{ $category->name }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">{{ substr($category->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        @if ($category->status)
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.common-image-categories.edit', $category->id) }}"
                                               class="p-2 text-purple-600 hover:text-purple-900 hover:bg-purple-50 rounded-lg transition-colors duration-200"
                                               title="Edit Category">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <form id="deleteForm{{ $category->id }}" method="POST" action="{{ route('admin.common-image-categories.delete', $category->id) }}" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="button"
                                                        onclick="confirmDelete('{{ route('admin.common-image-categories.delete', $category->id) }}', '{{ $category->name }}')"
                                                        class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                        title="Delete Category">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No common image categories found</p>
                                        <p class="text-gray-400 text-sm">Get started by adding your first common image category.</p>
                                        <a href="{{ route('admin.common-image-categories.add') }}" class="mt-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 inline-flex items-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            <span>Add New Category</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                                Delete Category
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modalMessage">
                                    Are you sure you want to delete this common image category? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="confirmDeleteBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" id="cancelDeleteBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(url, categoryName) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('modalMessage').innerHTML = `Are you sure you want to delete the common image category "${categoryName}"? This action cannot be undone.`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
        });

        // Search functionality
        document.getElementById('categorySearch').addEventListener('input', filterCategories);
        document.getElementById('statusFilter').addEventListener('change', filterCategories);
        document.getElementById('clearFilters').addEventListener('click', clearFilters);

        function filterCategories() {
            const searchTerm = document.getElementById('categorySearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('tbody tr[data-name]');

            rows.forEach(row => {
                const name = row.getAttribute('data-name').toLowerCase();
                const status = row.getAttribute('data-status');
                const textContent = row.textContent.toLowerCase();

                const matchesSearch = textContent.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function clearFilters() {
            document.getElementById('categorySearch').value = '';
            document.getElementById('statusFilter').value = 'all';
            filterCategories();
        }
    </script>
@endsection
