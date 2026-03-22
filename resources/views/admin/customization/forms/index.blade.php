@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <i class="fas fa-wpforms text-indigo-400"></i>
        Dynamic Forms
    </h2>
    <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm transition font-medium shadow-[0_0_15px_rgba(79,70,229,0.3)]">
        + Create New Form
    </button>
</div>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
    <div class="mb-4 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($forms as $form)
        <div class="bg-gray-800/60 backdrop-blur-sm border border-gray-700/50 rounded-2xl p-6 transition hover:border-gray-600 hover:shadow-xl hover:bg-gray-800/80">
            <h3 class="text-xl font-bold text-gray-100 mb-2">{{ $form->name }}</h3>
            <p class="text-sm text-gray-400 mb-4 h-10">{{ str($form->description)->limit(60) }}</p>
            <div class="flex items-center justify-between mb-6">
                <span class="text-xs bg-gray-900/80 px-3 py-1.5 rounded-full text-indigo-300 border border-gray-700">
                    <i class="fas fa-layer-group mr-1"></i> {{ $form->fields_count }} Fields
                </span>
                <span class="text-xs text-gray-500 font-mono">{{ $form->slug }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.forms.show', $form->id) }}" class="flex-1 text-center bg-gray-700 hover:bg-indigo-600 text-white font-medium py-2 rounded-lg transition text-sm">
                    Manage Fields
                </a>
                <form action="{{ route('admin.forms.destroy', $form->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/20 hover:bg-red-500/40 text-red-400 px-3 py-2 rounded-lg transition border border-red-500/30">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-gray-900 rounded-2xl w-full max-w-md border border-gray-700 overflow-hidden shadow-2xl">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center bg-gray-800/50">
                <h3 class="text-lg font-bold text-gray-100">Create New Form</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.forms.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Form Name</label>
                    <input type="text" name="name" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:outline-none focus:border-indigo-500 transition-colors" placeholder="e.g., Extended Warranty Request">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description / Guidance</label>
                    <textarea name="description" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:outline-none focus:border-indigo-500 transition-colors" placeholder="Explain the purpose of the form..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-lg transition text-sm font-medium">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition shadow-lg text-sm font-medium">Create Form</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
