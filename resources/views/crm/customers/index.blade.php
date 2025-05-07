@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Customers</h1>
        <a href="{{ route('customers.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Add Customer
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('customers.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" 
                   placeholder="Search customers..."
                   class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   value="{{ request('search') }}">
            <select name="status" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Search
            </button>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $customer->first_name }} {{ $customer->last_name }}
                                @if($customer->interactions_count > 0)
                                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        {{ $customer->interactions_count }} interactions
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $customer->email }}</td>
                            <td class="px-4 py-2">{{ $customer->phone ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm 
                                    @if($customer->status === 'active') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('customers.show', $customer) }}" 
                                   class="text-blue-500 hover:text-blue-700 mr-2">View</a>
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="text-green-500 hover:text-green-700 mr-2">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                No customers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection