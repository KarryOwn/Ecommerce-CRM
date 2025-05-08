@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">New Interaction - {{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}</h1>
            <a href="{{ route('customers.interactions.index', $customer) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <form action="{{ route('customers.interactions.store', $customer) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id ?? '' }}">
            
            @if(!$customer)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                <select name="customer_id" class="w-full rounded-lg border-gray-300" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300" required>
                        <option value="">Select Type</option>
                        <option value="support">Support</option>
                        <option value="inquiry">Inquiry</option>
                        <option value="complaint">Complaint</option>
                        <option value="feedback">Feedback</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Channel</label>
                    <select name="channel" class="w-full rounded-lg border-gray-300" required>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                        <option value="chat">Chat</option>
                        <option value="in_person">In Person</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           class="w-full rounded-lg border-gray-300" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-lg border-gray-300" required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" class="w-full rounded-lg border-gray-300" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300" required>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date</label>
                    <input type="datetime-local" name="scheduled_at"
                           class="w-full rounded-lg border-gray-300"
                           value="{{ old('scheduled_at') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                    <input type="date" name="follow_up_date"
                           class="w-full rounded-lg border-gray-300"
                           value="{{ old('follow_up_date') }}">
                </div>

                <div class="col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_followup"
                               class="rounded border-gray-300 text-blue-600"
                               {{ old('requires_followup') ? 'checked' : '' }}>
                        <span class="ml-2">Requires Follow-up</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Create Interaction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection