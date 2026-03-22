@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <i class="fas fa-file-code text-indigo-400"></i>
        Dynamic Page Builder
    </h2>
    <a href="{{ route('admin.pages.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm transition font-medium shadow-[0_0_15px_rgba(79,70,229,0.3)]">
        + Create New Page
    </a>
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
        @if(!empty($pages)) @foreach($pages as $page)
        <div class="bg-gray-800/60 backdrop-blur-sm border border-gray-700/50 rounded-2xl p-6 transition hover:border-gray-600 hover:shadow-xl hover:bg-gray-800/80">
            <h3 class="text-xl font-bold text-gray-100 mb-2">{{ $page->title }}</h3>
            
            <div class="flex items-center justify-between mb-6">
                <span class="text-xs font-mono text-gray-500 bg-gray-900/80 px-2.5 py-1 rounded border border-gray-700 break-all">{{ $page->slug }}</span>
                @if($page->is_published)
                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/20 uppercase font-bold tracking-wider">Live</span>
                @else
                    <span class="text-[10px] bg-yellow-500/20 text-yellow-500 px-2 py-0.5 rounded border border-yellow-500/20 uppercase font-bold tracking-wider">Draft</span>
                @endif
            </div>

            <div class="flex items-center gap-2 mb-6">
                <div class="w-full bg-gray-900 rounded-lg p-2.5 flex items-center gap-2 border border-gray-700 h-10 overflow-hidden">
                    <span class="text-gray-500 text-xs whitespace-nowrap"><i class="fas fa-cubes text-indigo-400 mr-1"></i> Blocks:</span>
                    <span class="text-gray-300 text-xs font-medium truncate">{{ is_array($page->content_blocks) ? count($page->content_blocks) . ' Sections' : '0' }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.pages.edit', $page->id) }}" class="flex-1 text-center bg-gray-700 hover:bg-indigo-600 text-white font-medium py-2 rounded-lg transition text-sm">
                    Builder Editor
                </a>
                <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Permanently delete this page?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/20 hover:bg-red-500/40 text-red-400 px-3 py-2 rounded-lg transition border border-red-500/30">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach @else
        <div class="col-span-full py-20 text-center">
            <div class="text-gray-600 mb-4 bg-gray-800/30 w-24 h-24 rounded-full flex items-center justify-center mx-auto border border-gray-700">
                <i class="fas fa-layer-group text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-200 mb-2">No pages compiled yet</h3>
            <p class="text-gray-400 max-w-sm mx-auto">Use the Visual Page Builder to assemble dynamic pages using drag-and-drop structural blocks natively integrated into your theme.</p>
        </div>
        @endif
    </div>
</div>
@endsection
