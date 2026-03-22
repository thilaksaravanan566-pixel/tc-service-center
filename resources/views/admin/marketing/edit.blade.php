@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.marketing.index') }}" class="text-gray-400 hover:text-white">← Back</a>
        <h1 class="text-2xl font-bold text-white">Edit Campaign: {{ $campaign->name }}</h1>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <form action="{{ route('admin.marketing.update', $campaign->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-gray-400 text-xs mb-1">Campaign Name *</label>
                <input type="text" name="name" value="{{ $campaign->name }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Type</label>
                    <select name="type" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                        @foreach(['banner','push','discount','email','sms'] as $t)
                        <option value="{{ $t }}" {{ $campaign->type == $t ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Target Audience</label>
                    <select name="target_audience" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                        <option value="all" {{ $campaign->target_audience == 'all' ? 'selected' : '' }}>All Customers</option>
                        <option value="new_customers" {{ $campaign->target_audience == 'new_customers' ? 'selected' : '' }}>New Customers</option>
                        <option value="repeat_customers" {{ $campaign->target_audience == 'repeat_customers' ? 'selected' : '' }}>Repeat Customers</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Content *</label>
                <textarea name="content" rows="5" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 resize-none" required>{{ $campaign->content }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Discount Code</label>
                    <input type="text" name="discount_code" value="{{ $campaign->discount_code }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 font-mono">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Discount %</label>
                    <input type="number" name="discount_percent" value="{{ $campaign->discount_percent }}" min="1" max="100" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ $campaign->start_date?->format('Y-m-d') }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ $campaign->end_date?->format('Y-m-d') }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $campaign->is_active ? 'checked' : '' }} id="is_active" class="w-4 h-4">
                <label for="is_active" class="text-gray-300 text-sm">Campaign Active</label>
            </div>
            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-semibold py-3 rounded-lg transition-all">
                Update Campaign
            </button>
        </form>
    </div>

</div>
@endsection
