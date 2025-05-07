@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-6">CRM Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-100 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800">Total Customers</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalCustomers }}</p>
        </div>
        
        <div class="bg-green-100 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-800">Active Customers</h3>
            <p class="text-3xl font-bold text-green-600">{{ $activeCustomers }}</p>
        </div>
        
        <div class="bg-purple-100 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-purple-800">Recent Interactions</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $recentInteractions->count() }}</p>
        </div>
    </div>

    <!-- Recent Interactions Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Interactions</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInteractions as $interaction)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $interaction->customer->first_name }} {{ $interaction->customer->last_name }}
                            </td>
                            <td class="px-4 py-2 capitalize">{{ $interaction->type }}</td>
                            <td class="px-4 py-2">{{ $interaction->subject }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm 
                                    @if($interaction->status === 'open') bg-yellow-100 text-yellow-800
                                    @elseif($interaction->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($interaction->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($interaction->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $interaction->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                No recent interactions
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection