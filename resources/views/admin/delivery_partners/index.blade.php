@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black text-slate-900">Delivery <span class="text-orange-600">Partners DB</span></h1>
            <a href="{{ route('admin.delivery-partners.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-black py-3 px-6 rounded-xl shadow-lg transition-colors text-sm uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Add Delivery Partner
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="p-6">Partner Name</th>
                        <th class="p-6">Contact Email</th>
                        <th class="p-6">Role</th>
                        <th class="p-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium border-t border-slate-100 divide-y divide-slate-100">
                    @if(!empty($partners)) @foreach($partners as $partner)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-6">
                            <div class="font-bold text-slate-900 text-base">{{ $partner->name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">ID: {{ str_pad($partner->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="p-6 text-slate-600 font-semibold">{{ $partner->email }}</td>
                        <td class="p-6">
                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                Logistics
                            </span>
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.delivery-partners.edit', $partner->id) }}" class="text-blue-500 hover:bg-blue-50 p-2 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.delivery-partners.destroy', $partner->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this delivery partner?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-12 text-center">
                            <div class="text-slate-400 font-bold mb-2">No Delivery Partners Found</div>
                            <div class="text-sm text-slate-500">Add a delivery partner to manage local logistics.</div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="mt-8">
            {{ $partners->links() }}
        </div>
    </div>
</div>
@endsection
