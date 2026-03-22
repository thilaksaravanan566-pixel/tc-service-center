@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto animate-fade-in-up">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Database <span class="text-blue-600">Technicians</span></h1>
                <p class="text-sm text-slate-500 font-medium">Manage repair staff and assignments</p>
            </div>
            <a href="{{ route('admin.technicians.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition-all text-xs uppercase tracking-widest">+ Register Technician</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Technician Name</th>
                        <th class="p-5">Contact Details</th>
                        <th class="p-5">Joined</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($technicians)) @foreach($technicians as $tech)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-black flex items-center justify-center text-lg">
                                    {{ substr($tech->name, 0, 1) }}
                                </div>
                                <h3 class="font-bold text-slate-800 text-base">{{ $tech->name }}</h3>
                            </div>
                        </td>
                        <td class="p-5">
                            <p class="text-sm font-bold text-slate-700">{{ $tech->email }}</p>
                        </td>
                        <td class="p-5">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $tech->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2 text-right">
                                <a href="{{ route('admin.technicians.edit', $tech->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                    Edit Profile
                                </a>
                                <form action="{{ route('admin.technicians.destroy', $tech->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove this technician?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                        Terminate
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic">
                            No technicians registered yet.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
