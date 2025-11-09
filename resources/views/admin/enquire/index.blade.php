@extends('layouts.admin.main')
@section('content')
    <div class="flex-1 p-6 overflow-auto bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ isset($module_name) ? $module_name : 'Enquiry' }} Management</h1>
                        <p class="text-gray-600 text-lg">View and manage customer enquiries</p>
                    </div>
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
                            <input type="text" id="enquirySearch" placeholder="Search enquiries..."
                                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 11116.65 16.65z"/>
                            </svg>
                        </div>

                        {{-- <select id="statusFilter" class="border border-gray-300 rounded-lg text-sm px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            <option value="all">All Status</option>
                            <option value="0">Unread</option>
                            <option value="1">Read</option>
                        </select> --}}
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
                                Enquirer Details
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Contact Info
                            </th>
                            {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Type
                            </th> --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Message
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Received On
                            </th>
                            {{-- <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Status
                            </th> --}}
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(isset($model_data_lists) && $model_data_lists->count() > 0)
                            @foreach($model_data_lists as $enquiry)
                                <tr class="hover:bg-gray-50 transition-colors duration-150" data-status="{{ $enquiry->is_read }}" data-type="{{ $enquiry->type }}" data-name="{{ $enquiry->name }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">{{ substr($enquiry->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $enquiry->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $enquiry->type }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div><strong>Email:</strong> {{ $enquiry->email }}</div>
                                        <div><strong>Phone:</strong> {{ $enquiry->phone }}</div>
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                        {{ $enquiry->type }}
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell max-w-xs">
                                        <div class="truncate" title="{{ $enquiry->message }}">
                                            @if(strlen($enquiry->message) > 50)
                                                {{ substr($enquiry->message, 0, 50) }}...
                                            @else
                                                {{ $enquiry->message }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                        {{ \Carbon\Carbon::parse($enquiry->created_at)->format('M d, Y H:i') }}
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-center hidden lg:table-cell">
                                        @if($enquiry->is_read == 1)
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Read
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                                </svg>
                                                Unread
                                            </span>
                                        @endif
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button type="button"
                                                   onclick="viewEnquiry('{{ addslashes($enquiry->name) }}', '{{ addslashes($enquiry->email) }}', '{{ addslashes($enquiry->phone) }}', '{{ addslashes($enquiry->type) }}', '{{ addslashes($enquiry->address) }}', '{{ addslashes($enquiry->message) }}')"
                                                   class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                                   title="View Enquiry">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- @if($enquiry->is_delete == 0)
                                                <form id="deleteForm{{ $enquiry->id }}" method="POST" action="{{ url(getAdminRouteName() . '/enquiries/delete/' . $enquiry->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                           onclick="confirmDelete('{{ url(getAdminRouteName() . '/enquiries/delete/' . $enquiry->id) }}', '{{ $enquiry->name }}')"
                                                           class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                           title="Delete Enquiry">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No enquiries found</p>
                                        <p class="text-gray-400 text-sm">Enquiries will appear here when customers contact you.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enquiry Details Modal -->
    <div id="enquiryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="enquiryModalTitle">
                                Enquiry Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><strong>Name:</strong> <span id="modalName"></span></div>
                                <div><strong>Email:</strong> <span id="modalEmail"></span></div>
                                <div><strong>Phone:</strong> <span id="modalPhone"></span></div>
                                {{-- <div><strong>Type:</strong> <span id="modalType"></span></div>
                                <div class="md:col-span-2"><strong>Address:</strong> <span id="modalAddress"></span></div> --}}
                            </div>
                            <div class="mt-4">
                                <strong>Message:</strong>
                                <div id="modalMessage" class="mt-2 p-3 bg-gray-50 rounded-lg whitespace-pre-wrap"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="closeModalBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
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
                                Delete Enquiry
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modalMessage">
                                    Are you sure you want to delete this enquiry? This action cannot be undone.
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
        function confirmDelete(url, enquiryName) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('modalMessage').innerHTML = `Are you sure you want to delete the enquiry from "${enquiryName}"? This action cannot be undone.`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
        });

        function viewEnquiry(name, email, phone, type, address, message) {
            document.getElementById('modalName').innerText = name;
            document.getElementById('modalEmail').innerText = email;
            document.getElementById('modalPhone').innerText = phone;
            // document.getElementById('modalType').innerText = type;
            // document.getElementById('modalAddress').innerText = address;
            document.getElementById('modalMessage').innerText = message;
            document.getElementById('enquiryModal').classList.remove('hidden');
        }

        document.getElementById('closeModalBtn').addEventListener('click', function() {
            document.getElementById('enquiryModal').classList.add('hidden');
        });

        // Search functionality
        document.getElementById('enquirySearch').addEventListener('input', filterEnquiries);
        document.getElementById('statusFilter').addEventListener('change', filterEnquiries);
        document.getElementById('clearFilters').addEventListener('click', clearFilters);

        function filterEnquiries() {
            const searchTerm = document.getElementById('enquirySearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('tbody tr[data-name]');

            rows.forEach(row => {
                const name = row.getAttribute('data-name').toLowerCase();
                const type = row.getAttribute('data-type').toLowerCase();
                const status = row.getAttribute('data-status');
                const textContent = row.textContent.toLowerCase();

                const matchesSearch = textContent.includes(searchTerm) || name.includes(searchTerm) || type.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function clearFilters() {
            document.getElementById('enquirySearch').value = '';
            document.getElementById('statusFilter').value = 'all';
            filterEnquiries();
        }
    </script>
@endsection
