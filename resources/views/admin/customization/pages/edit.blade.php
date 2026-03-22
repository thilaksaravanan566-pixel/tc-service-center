@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <a href="{{ route('admin.pages.index') }}" class="text-gray-400 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
        <span>Compile Render: {{ $page->title }}</span>
    </h2>
    <a href="{{ url($page->slug) }}" target="_blank" class="text-xs bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded hidden hover:bg-indigo-500 hover:text-white">Live Viewer <i class="fas fa-external-link-alt ml-1"></i></a>
</div>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl" x-data="pageBuilder(@js($page->content_blocks ?: []))">
    
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" id="builderForm">
        @csrf
        @method('PUT')
        
        <!-- Header -->
        <div class="bg-gray-800/60 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-700/50 p-6 mb-8 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-300 mb-2">Page Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ $page->title }}" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-gray-200 focus:border-indigo-500 outline-none font-bold text-lg">
                <p class="text-[10px] mt-2 font-mono text-gray-500">Slug ID: {{ $page->slug }}</p>
            </div>
            <div class="flex items-center gap-4 bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 shrink-0">
                <span class="text-sm font-medium text-gray-300">Live Publish?</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $page->is_published ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
        </div>

        <!-- Canvas -->
        <div class="bg-gray-900/50 border border-dashed border-gray-700 rounded-2xl p-6 min-h-[400px]">
            <h3 class="text-gray-400 font-medium mb-6 flex items-center gap-2 border-b border-gray-700 pb-2">
                <i class="fas fa-layer-group text-indigo-400"></i> Assembly Canvas
            </h3>

            <div class="space-y-6" id="blocksContainer">
                <template x-for="(block, index) in blocks" :key="block.id">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg relative group transition-all">
                        <!-- Top Toolbar -->
                        <div class="bg-gray-900 px-4 py-2 border-b border-gray-700 flex justify-between items-center cursor-move handle">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 flex items-center gap-2">
                                <i class="fas" :class="getIcon(block.type)"></i> <span x-text="block.type"></span>
                            </span>
                            <div class="flex gap-2 opacity-100 transition">
                                <button type="button" @click="moveUp(index)" :disabled="index === 0" class="text-gray-500 hover:text-white disabled:opacity-30"><i class="fas fa-arrow-up"></i></button>
                                <button type="button" @click="moveDown(index)" :disabled="index === blocks.length - 1" class="text-gray-500 hover:text-white disabled:opacity-30"><i class="fas fa-arrow-down"></i></button>
                                <button type="button" @click="removeBlock(index)" class="text-red-400 hover:text-red-300 ml-2"><i class="fas fa-times"></i></button>
                            </div>
                        </div>

                        <!-- Payload inputs based on type -->
                        <div class="p-4">
                            <!-- Hidden identifier mapping for Laravel array submission -->
                            <input type="hidden" :name="`blocks[${index}][type]`" :value="block.type">
                            
                            <!-- Text Block -->
                            <template x-if="block.type === 'text'">
                                <div>
                                    <textarea :name="`blocks[${index}][content]`" x-model="block.content" rows="4" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-gray-200 focus:border-indigo-500 outline-none text-sm placeholder-gray-600" placeholder="Type your paragraph or HTML here..."></textarea>
                                </div>
                            </template>

                            <!-- Heading Block -->
                            <template x-if="block.type === 'heading'">
                                <div>
                                    <input type="text" :name="`blocks[${index}][content]`" x-model="block.content" class="w-full font-bold text-lg bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-gray-200 focus:border-indigo-500 outline-none placeholder-gray-600" placeholder="Enter Large Heading Text...">
                                </div>
                            </template>

                            <!-- Image Block -->
                            <template x-if="block.type === 'image'">
                                <div class="flex gap-4 items-center bg-gray-900 border border-gray-700 rounded-xl p-4">
                                    <div class="bg-gray-800 h-16 w-16 rounded border border-gray-600 flex items-center justify-center text-gray-500 overflow-hidden">
                                        <template x-if="block.content">
                                            <img :src="block.content" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!block.content">
                                            <i class="fas fa-image text-xl"></i>
                                        </template>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-400 mb-1">Pass an external Image URL (S3, Imgur) or relative asset path</p>
                                        <input type="text" :name="`blocks[${index}][content]`" x-model="block.content" class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-gray-200 focus:border-indigo-500 outline-none text-sm font-mono" placeholder="https://...">
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Button Block -->
                            <template x-if="block.type === 'button'">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-1">Button Text</label>
                                        <input type="text" :name="`blocks[${index}][content][label]`" x-model="block.content?.label || block.content" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:border-indigo-500 outline-none text-sm" placeholder="Click Here">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-1">Target URL</label>
                                        <input type="text" :name="`blocks[${index}][content][url]`" x-model="block.content?.url || ''" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:border-indigo-500 outline-none text-sm font-mono" placeholder="/shop or https://...">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                
                <div x-show="blocks.length === 0" class="text-center py-12 text-gray-500">
                    <p>Canvas is empty. Add a structural block below to begin.</p>
                </div>
            </div>

            <!-- Block Picker Grid -->
            <div class="mt-8 border-t border-gray-700/50 pt-6">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 block">Add Layout Component</span>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="addBlock('heading')" class="bg-gray-800 hover:bg-gray-700 border border-gray-600 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 transition flex items-center gap-2">
                        <i class="fas fa-heading text-indigo-400"></i> Heading
                    </button>
                    <button type="button" @click="addBlock('text')" class="bg-gray-800 hover:bg-gray-700 border border-gray-600 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 transition flex items-center gap-2">
                        <i class="fas fa-align-left text-indigo-400"></i> Paragraph
                    </button>
                    <button type="button" @click="addBlock('image')" class="bg-gray-800 hover:bg-gray-700 border border-gray-600 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 transition flex items-center gap-2">
                        <i class="fas fa-image text-indigo-400"></i> Image
                    </button>
                    <button type="button" @click="addBlock('button')" class="bg-gray-800 hover:bg-gray-700 border border-gray-600 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 transition flex items-center gap-2">
                        <i class="fas fa-link text-indigo-400"></i> CTA Button
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-3 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-xl transition font-medium">Discard Modifications</a>
            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl transition shadow-[0_0_15px_rgba(79,70,229,0.3)] font-bold">Resync Full Page</button>
        </div>
    </form>
</div>

<!-- Alpine Logic Array Processor -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pageBuilder', (initialBlocks = []) => {
            
            // Format array recursively so object structures map 1:1 visually on reload
            let mappedBlocks = Array.isArray(initialBlocks) ? initialBlocks.map(blk => {
                return {
                    id: Date.now().toString() + Math.random().toString(36).substr(2, 5),
                    type: blk.type,
                    content: blk.content || ''
                }
            }) : [];
            
            return {
                blocks: mappedBlocks,
                
                addBlock(type) {
                    this.blocks.push({
                        id: Date.now().toString() + Math.random().toString(36).substr(2, 5),
                        type: type,
                        content: type === 'button' ? { label: '', url: '' } : ''
                    });
                },
                
                removeBlock(index) {
                    this.blocks.splice(index, 1);
                },
                
                moveUp(index) {
                    if (index > 0) {
                        const temp = this.blocks[index];
                        this.blocks[index] = this.blocks[index - 1];
                        this.blocks[index - 1] = temp;
                    }
                },
                
                moveDown(index) {
                    if (index < this.blocks.length - 1) {
                        const temp = this.blocks[index];
                        this.blocks[index] = this.blocks[index + 1];
                        this.blocks[index + 1] = temp;
                    }
                },
                
                getIcon(type) {
                    const icons = {
                        'heading': 'fa-heading',
                        'text': 'fa-align-left',
                        'image': 'fa-image',
                        'button': 'fa-link'
                    };
                    return icons[type] || 'fa-cube';
                }
            }
        });
    });
</script>
@endsection
