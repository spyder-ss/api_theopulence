@extends('layouts.admin.main')
@section('content')
    <div class="flex-1 p-6 overflow-auto bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">{{ isset($module_name) ? $module_name : 'Activity Log' }} Management</h1>
                        <p class="text-gray-600 text-lg">View system activity logs and user actions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Today's Logs</p>
                        <p class="text-2xl font-bold text-gray-800">{{ isset($activity_logs) ? $activity_logs->where('created_at', '>=', today()->startOfDay())->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">This Week</p>
                        <p class="text-2xl font-bold text-gray-800">{{ isset($activity_logs) ? $activity_logs->where('created_at', '>=', today()->startOfWeek())->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-800">{{ isset($activity_logs) ? $activity_logs->where('created_at', '>=', today()->startOfMonth())->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Logs</p>
                        <p class="text-2xl font-bold text-gray-800">{{ isset($activity_logs) ? $activity_logs->count() : 0 }}</p>
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
                            <input type="text" id="logSearch" placeholder="Search activity logs..."
                                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 11116.65 16.65z"/>
                            </svg>
                        </div>

                        <select id="moduleFilter" class="border border-gray-300 rounded-lg text-sm px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200">
                            <option value="all">All Modules</option>
                            @if(isset($activity_logs))
                                @foreach($activity_logs->pluck('module')->unique() as $module)
                                    <option value="{{ $module }}">{{ $module }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select id="actionFilter" class="border border-gray-300 rounded-lg text-sm px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-200">
                            <option value="all">All Actions</option>
                            @if(isset($activity_logs))
                                @foreach($activity_logs->pluck('action')->unique() as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            @endif
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
                                User & Time
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Module
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Action
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Description
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden xl:table-cell">
                                IP Address
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(isset($activity_logs) && $activity_logs->count() > 0)
                            @foreach($activity_logs as $log)
                                <tr class="hover:bg-gray-50 transition-colors duration-150" data-module="{{ $log->module }}" data-action="{{ $log->action }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($log->GetAddedBy)
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-white">{{ substr($log->GetAddedBy->name ?? 'N', 0, 1) }}</span>
                                                    </div>
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-white">N</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $log->GetAddedBy->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $log->created_at ? $log->created_at->format('M d, Y H:i') : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $log->module ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        @if($log->action == 'created' || $log->action == 'create')
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        @elseif($log->action == 'updated' || $log->action == 'update')
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        @elseif($log->action == 'deleted' || $log->action == 'delete')
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($log->action ?? 'N/A') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="max-w-xs truncate" title="{{ $log->description }}">
                                            {{ $log->description ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden xl:table-cell">
                                        {{ $log->ip ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ url(getAdminRouteName() . '/activity_logs/details/' . $log->id) }}"
                                               class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                               title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No activity logs found</p>
                                        <p class="text-gray-400 text-sm">System activity will appear here once actions are performed.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('logSearch').addEventListener('input', filterLogs);
        document.getElementById('moduleFilter').addEventListener('change', filterLogs);
        document.getElementById('actionFilter').addEventListener('change', filterLogs);
        document.getElementById('clearFilters').addEventListener('click', clearFilters);

        function filterLogs() {
            const searchTerm = document.getElementById('logSearch').value.toLowerCase();
            const moduleFilter = document.getElementById('moduleFilter').value;
            const actionFilter = document.getElementById('actionFilter').value;
            const rows = document.querySelectorAll('tbody tr[data-module]');

            rows.forEach(row => {
                const module = row.getAttribute('data-module') ? row.getAttribute('data-module').toLowerCase() : '';
                const action = row.getAttribute('data-action') ? row.getAttribute('data-action').toLowerCase() : '';
                const textContent = row.textContent.toLowerCase();

                const matchesSearch = textContent.includes(searchTerm);
                const matchesModule = moduleFilter === 'all' || module === moduleFilter.toLowerCase();
                const matchesAction = actionFilter === 'all' || action === actionFilter.toLowerCase();

                if (matchesSearch && matchesModule && matchesAction) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function clearFilters() {
            document.getElementById('logSearch').value = '';
            document.getElementById('moduleFilter').value = 'all';
            document.getElementById('actionFilter').value = 'all';
            filterLogs();
        }
    </script>
@endsection
