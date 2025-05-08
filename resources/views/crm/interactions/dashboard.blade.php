@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Customer Interactions Dashboard</h1>
        <div class="space-x-4">
            <select id="statusFilter" class="rounded-lg border-gray-300">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Pending Interactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Pending Responses</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($pendingInteractions as $customerId => $customerInteractions)
                    @php 
                        $firstInteraction = $customerInteractions->first();
                        $customer = $firstInteraction->customer;
                    @endphp
                    <div class="border-b pb-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium">{{ $customer->first_name }} {{ $customer->last_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $customerInteractions->count() }} pending interactions</p>
                            </div>
                            <a href="{{ route('customers.interactions.index', $customer) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                View All
                            </a>
                        </div>
                        <div class="space-y-2">
                            @foreach($customerInteractions->take(3) as $interaction)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm">{{ Str::limit($interaction->content, 100) }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $interaction->created_at->diffForHumans() }} 
                                                by {{ $interaction->user->name }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $interaction->requires_followup ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $interaction->requires_followup ? 'Follow-up' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No pending interactions</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentInteractions as $interaction)
                    <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 text-xs rounded-full font-medium
                                        {{ $interaction->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $interaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $interaction->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst($interaction->status) }}
                                    </span>
                                    <span class="text-sm text-gray-600">{{ ucfirst($interaction->type) }}</span>
                                </div>
                                <h3 class="font-medium mb-1">{{ $interaction->subject }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ Str::limit($interaction->description, 100) }}
                                </p>
                                <div class="text-xs text-gray-500 flex items-center gap-2">
                                    <span>{{ $interaction->created_at->format('M d, Y h:ia') }}</span>
                                    <span>•</span>
                                    <span>{{ $interaction->user?->name ?? 'Unknown User' }}</span>
                                    @if($interaction->requires_followup)
                                        <span>•</span>
                                        <span class="text-yellow-600">Requires Follow-up</span>
                                    @endif
                                    @if($interaction->resolved_at)
                                        <span>•</span>
                                        <span class="text-green-600">
                                            Resolved {{ $interaction->resolved_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('customers.interactions.show', [$interaction->customer, $interaction]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const interactions = document.querySelectorAll('.interaction-item');

    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        
        interactions.forEach(interaction => {
            const status = interaction.dataset.status;
            
            if (selectedStatus === 'all' || status === selectedStatus) {
                interaction.style.display = 'block';
            } else {
                interaction.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection