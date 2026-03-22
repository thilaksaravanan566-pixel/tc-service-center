@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <i class="fas fa-shield-alt text-indigo-400"></i>
        Access Control Options
    </h2>
    <button onclick="document.getElementById('createRoleModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm transition font-medium shadow-[0_0_15px_rgba(79,70,229,0.3)]">
        + Create New Role
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
        @foreach($roles as $role)
        <div class="bg-gray-800/60 backdrop-blur-sm border border-gray-700/50 rounded-2xl p-6 transition hover:border-gray-600 hover:shadow-xl hover:bg-gray-800/80">
            <h3 class="text-xl font-bold text-gray-100 mb-2 capitalize">{{ $role->name }}</h3>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs bg-gray-900/80 px-2.5 py-1 rounded text-gray-400 border border-gray-700 font-mono">
                    System ID: {{ $role->slug }}
                </span>
            </div>
            <p class="text-sm text-gray-400 mb-6 h-10">{{ str($role->description ?? 'No specific description provided.')->limit(60) }}</p>
            
            <div class="flex gap-2">
                <button class="flex-1 text-center bg-gray-700 text-white font-medium py-2 rounded-lg text-sm opacity-50 cursor-not-allowed">
                    Protected System Role
                </button>
                @if(!in_array($role->slug, ['admin', 'technician', 'delivery_partner']))
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role definitively?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/20 hover:bg-red-500/40 text-red-400 px-3 py-2 rounded-lg transition border border-red-500/30">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Create Role Modal -->
    <div id="createRoleModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 rounded-2xl w-full max-w-3xl border border-gray-700 overflow-hidden shadow-2xl max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center bg-gray-800/50 shrink-0">
                <h3 class="text-lg font-bold text-gray-100">Create New Security Role</h3>
                <button onclick="document.getElementById('createRoleModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-6 flex-1 custom-scrollbar">
                <form action="{{ route('admin.roles.store') }}" method="POST" id="createRoleForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Role Name</label>
                            <input type="text" name="name" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:outline-none focus:border-indigo-500 transition-colors" placeholder="e.g. Sales Manager">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <input type="text" name="description" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:outline-none focus:border-indigo-500 transition-colors" placeholder="Optional notes about capabilities">
                        </div>
                    </div>

                    <h4 class="font-bold border-b border-gray-700 pb-2 mb-4 text-indigo-400">Access Boundaries</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($permissions as $group => $perms)
                        <div class="bg-gray-800/30 border border-gray-700/50 rounded-xl p-4">
                            <h5 class="font-semibold text-gray-200 mb-3 capitalize border-b border-gray-700/50 pb-2 flex items-center gap-2">
                                <i class="fas fa-layer-group text-gray-500 text-xs"></i> {{ str_replace('_', ' ', $group) }}
                            </h5>
                            <div class="space-y-2">
                                @foreach($perms as $perm)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="peer sr-only">
                                        <div class="w-4 h-4 bg-gray-800 border border-gray-600 rounded peer-checked:bg-indigo-500 peer-checked:border-indigo-500 transition-colors"></div>
                                        <i class="fas fa-check absolute text-[10px] text-white opacity-0 peer-checked:opacity-100 left-[3px] top-[3px]"></i>
                                    </div>
                                    <span class="text-xs text-gray-400 group-hover:text-gray-200 transition">{{ $perm->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-800 bg-gray-800/50 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('createRoleModal').classList.add('hidden')" class="px-4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-lg transition text-sm font-medium">Cancel</button>
                <button type="submit" form="createRoleForm" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition shadow-lg text-sm font-medium">Compile Security Policy</button>
            </div>
        </div>
    </div>
</div>
@endsection
