@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.parts.index') }}" class="text-slate-400 hover:text-blue-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Inventory
                </a>
                <h1 class="text-3xl font-black text-slate-900">Spare Parts <span class="text-blue-600">Categories</span></h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Manage dropdown entries for new stock.</p>
            </div>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold tracking-widest uppercase shadow-lg shadow-blue-200 transition-all text-xs">
                + New Category
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 font-medium flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-medium flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i> 
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                        <th class="p-5 font-black">ID</th>
                        <th class="p-5 font-black">Category Name</th>
                        <th class="p-5 font-black">Status</th>
                        <th class="p-5 font-black text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium text-slate-700">
                    @foreach($categories as $category)
                    <tr class="hover:bg-slate-50 transition-colors {{ $category->status === 'inactive' ? 'opacity-50' : '' }}">
                        <td class="p-5 text-slate-400 font-mono">#{{ $category->id }}</td>
                        <td class="p-5 font-bold text-slate-900">{{ $category->name }}</td>
                        <td class="p-5">
                            @if($category->status === 'active')
                                <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest">Active</span>
                            @else
                                <span class="bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest">Hidden</span>
                            @endif
                        </td>
                        <td class="p-5 text-right">
                            <button onclick="editCategory({{ json_encode($category) }})" class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase tracking-widest mr-3">Edit</button>
                            @if($category->status === 'active')
                            <form action="{{ route('admin.part-categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-widest">Hide</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if($categories->isEmpty())
                    <tr><td colspan="4" class="p-10 text-center text-slate-500">No categories found. Add one to get started!</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm animate-fade-in-up">
        <h3 class="text-xl font-black text-slate-900 mb-6">New Category</h3>
        <form action="{{ route('admin.part-categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Category exact name:</label>
                <input type="text" name="name" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-800" placeholder="e.g. Screen Protector">
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 py-4 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 font-black text-xs uppercase tracking-widest transition-all">Cancel</button>
                <button type="submit" class="flex-1 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-200 transition-all">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm animate-fade-in-up">
        <h3 class="text-xl font-black text-slate-900 mb-6">Edit Category</h3>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Category Name:</label>
                <input type="text" id="editName" name="name" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-800">
            </div>
            <div class="mb-4">
                <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Status:</label>
                <select id="editStatus" name="status" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none font-bold text-slate-800 cursor-pointer">
                    <option value="active">Active (Visible)</option>
                    <option value="inactive">Inactive (Hidden)</option>
                </select>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 py-4 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 font-black text-xs uppercase tracking-widest transition-all">Cancel</button>
                <button type="submit" class="flex-1 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-200 transition-all">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editCategory(cat) {
        document.getElementById('editName').value = cat.name;
        document.getElementById('editStatus').value = cat.status;
        document.getElementById('editForm').action = "/admin/part-categories/" + cat.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
