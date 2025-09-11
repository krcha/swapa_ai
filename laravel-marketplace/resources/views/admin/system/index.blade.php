@extends('admin.layouts.app')

@section('title', 'System Management')

@section('content')
<div class="space-y-6">
    <!-- Page header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                System Management
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Monitor system health, manage cache, and perform maintenance tasks.
            </p>
        </div>
    </div>

    <!-- System Health Status -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Health</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current status of system components.</p>
        </div>
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($systemHealth['overall'] === 'healthy')
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Overall System Status</p>
                                <p class="text-sm text-gray-500">All systems operational</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($systemHealth['overall'] === 'healthy') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($systemHealth['overall']) }}
                            </span>
                        </div>
                    </div>
                </li>

                @foreach($systemHealth['checks'] as $component => $status)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($status === 'healthy')
                                    <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-6 w-6 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $component)) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($status === 'healthy') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Server Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Server Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current server configuration and status.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $serverInfo['php_version'] }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $serverInfo['laravel_version'] }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Memory Limit</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $serverInfo['memory_limit'] }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Max Execution Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $serverInfo['max_execution_time'] }}s</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $serverInfo['timezone'] }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Environment</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($serverInfo['environment'] === 'production') bg-green-100 text-green-800
                            @elseif($serverInfo['environment'] === 'staging') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($serverInfo['environment']) }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($serverInfo['debug_mode']) bg-red-100 text-red-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $serverInfo['debug_mode'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Database Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Database Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Database connection and configuration details.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $databaseInfo['driver'] }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Version</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $databaseInfo['version'] }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($databaseInfo['status'] === 'connected') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($databaseInfo['status']) }}
                        </span>
                    </dd>
                </div>
                @if(isset($databaseInfo['error']))
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Error</dt>
                    <dd class="mt-1 text-sm text-red-600">{{ $databaseInfo['error'] }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- System Actions -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Actions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Perform maintenance and optimization tasks.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Clear Cache -->
                    <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <button type="button" 
                                    onclick="clearCache('all')"
                                    class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                <p class="text-sm font-medium text-gray-900">Clear All Cache</p>
                                <p class="text-sm text-gray-500">Clear application, config, route, and view cache</p>
                            </button>
                        </div>
                    </div>

                    <!-- Clear Config Cache -->
                    <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <button type="button" 
                                    onclick="clearCache('config')"
                                    class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                <p class="text-sm font-medium text-gray-900">Clear Config Cache</p>
                                <p class="text-sm text-gray-500">Clear configuration cache only</p>
                            </button>
                        </div>
                    </div>

                    <!-- Run Migrations -->
                    <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <button type="button" 
                                    onclick="runMigrations()"
                                    class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                <p class="text-sm font-medium text-gray-900">Run Migrations</p>
                                <p class="text-sm text-gray-500">Execute pending database migrations</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Statistics -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Statistics</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current system resource usage and performance metrics.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Memory Usage</dt>
                                        <dd class="text-lg font-medium text-gray-900" id="memory-usage">Loading...</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Peak Memory</dt>
                                        <dd class="text-lg font-medium text-gray-900" id="peak-memory">Loading...</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Memory Limit</dt>
                                        <dd class="text-lg font-medium text-gray-900" id="memory-limit">Loading...</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearCache(type) {
    if (confirm(`Are you sure you want to clear ${type} cache?`)) {
        fetch(`/admin/system/clear-cache?type=${type}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing cache.');
        });
    }
}

function runMigrations() {
    if (confirm('Are you sure you want to run migrations? This will modify the database.')) {
        fetch('/admin/system/run-migrations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while running migrations.');
        });
    }
}

// Load system statistics
function loadSystemStats() {
    fetch('/admin/system/statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('memory-usage').textContent = formatBytes(data.stats.memory_usage);
                document.getElementById('peak-memory').textContent = formatBytes(data.stats.memory_peak);
                document.getElementById('memory-limit').textContent = data.stats.memory_limit;
            }
        })
        .catch(error => {
            console.error('Error loading system stats:', error);
        });
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Load stats on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSystemStats();
});
</script>
@endpush
@endsection
