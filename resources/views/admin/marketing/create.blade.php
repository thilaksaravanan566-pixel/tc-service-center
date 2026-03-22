@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.marketing.index') }}" class="text-gray-400 hover:text-white">← Back</a>
        <h1 class="text-2xl font-bold text-white">Create Marketing Campaign</h1>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <form action="{{ route('admin.marketing.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-gray-400 text-xs mb-1">Campaign Name *</label>
                <input type="text" name="name" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" placeholder="e.g., Summer Sale 2026" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Type *</label>
                    <select name="type" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                        <option value="banner">🖼 Banner</option>
                        <option value="push">📲 Push Notification</option>
                        <option value="discount">🏷 Discount</option>
                        <option value="email">📧 Email</option>
                        <option value="sms">💬 SMS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Target Audience *</label>
                    <select name="target_audience" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                        <option value="all">All Customers</option>
                        <option value="new_customers">New Customers</option>
                        <option value="repeat_customers">Repeat Customers</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Description</label>
                <input type="text" name="description" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" placeholder="Short description">
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Content / Message *</label>
                <textarea name="content" rows="5" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 resize-none" placeholder="Campaign content or notification message..." required></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Discount Code</label>
                    <input type="text" name="discount_code" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 font-mono" placeholder="SAVE20">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Discount %</label>
                    <input type="number" name="discount_percent" min="1" max="100" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" placeholder="20">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Start Date</label>
                    <input type="date" name="start_date" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">End Date</label>
                    <input type="date" name="end_date" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Campaign Image (optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-semibold py-3 rounded-lg transition-all">
                    🚀 Launch Campaign
                </button>
                <p class="text-gray-500 text-xs text-center mt-2">Push notification campaigns will be sent to customers immediately.</p>
            </div>
        </form>
    </div>

</div>
@endsection
