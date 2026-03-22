@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <a href="{{ route('admin.forms.index') }}" class="text-gray-400 hover:text-white transition"><i class="fas fa-arrow-left"></i></a>
        <div>
            <span class="block text-sm text-indigo-400 font-normal">Form Builder</span>
            {{ $form->name }}
        </div>
    </h2>
</div>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Field Builder List -->
    <div class="lg:col-span-2">
        <div class="bg-gray-800/60 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-700/50 p-6 flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-gray-100 text-lg">Current Schema</h3>
                <p class="text-sm text-gray-400">Drag to reorder functionality coming soon in Phase 7</p>
            </div>
            <span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-3 py-1 rounded-full text-xs font-bold">{{ $form->fields->count() }} Fields</span>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl border border-emerald-500/30">{{ session('success') }}</div>
        @endif

        <div class="space-y-4">
            @if(!empty($form->fields)) @foreach($form->fields as $field)
            <div class="bg-gray-900 border border-gray-700 rounded-xl p-5 flex items-start justify-between group hover:border-indigo-500/50 transition">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-gray-100 font-bold">{{ $field->label }}</span>
                        @if($field->is_required)
                            <span class="text-[10px] bg-red-500/20 text-red-400 px-1.5 py-0.5 rounded border border-red-500/20 uppercase font-bold tracking-wider">Required</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <span class="text-indigo-400"><i class="fas fa-code mr-1"></i> {{ $field->name }}</span>
                        <span class="text-emerald-400"><i class="fas fa-keyboard mr-1"></i> {{ strtoupper($field->type) }}</span>
                    </div>

                    @if(in_array($field->type, ['select', 'radio', 'checkbox']) && $field->options)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($field->options as $opt)
                                <span class="bg-gray-800 border border-gray-700 text-gray-400 text-[11px] px-2 py-1 rounded">{{ $opt }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <form action="{{ route('admin.forms.destroyField', [$form->id, $field->id]) }}" method="POST" onsubmit="return confirm('Remove this field?');">
                    @csrf @method('DELETE')
                    <button class="text-gray-500 hover:text-red-400 p-2 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @endforeach @else
            <div class="text-center bg-gray-800/30 border border-dashed border-gray-700 rounded-2xl py-12">
                <i class="fas fa-clipboard-list text-gray-600 text-4xl mb-4"></i>
                <p class="text-gray-400 font-medium">No fields found.<br>Use the sidebar to add your first input.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Add Field Form -->
    <div class="bg-gray-800/60 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-700/50 p-6 h-fit sticky top-6">
        <h3 class="font-bold text-gray-100 text-lg mb-4 pb-4 border-b border-gray-700"><i class="fas fa-plus-circle text-indigo-400 mr-2"></i> Add Form Field</h3>
        
        <form action="{{ route('admin.forms.addField', $form->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Field Label <span class="text-red-400">*</span></label>
                <input type="text" name="label" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-200 focus:border-indigo-500 outline-none text-sm" placeholder="e.g. Serial Number">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Input Type <span class="text-red-400">*</span></label>
                <select name="type" x-data @change="showOptions = ['select', 'radio', 'checkbox'].includes($event.target.value)" onchange="document.getElementById('optsBox').style.display = ['select', 'radio', 'checkbox'].includes(this.value) ? 'block' : 'none'" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-200 focus:border-indigo-500 outline-none text-sm appearance-none">
                    <option value="text">Short Text (Input)</option>
                    <option value="textarea">Long Text (Textarea)</option>
                    <option value="select">Dropdown (Select)</option>
                    <option value="radio">Radio Buttons</option>
                    <option value="checkbox">Multiple Choice (Checkbox)</option>
                    <option value="date">Date Picker</option>
                    <option value="file">File Upload</option>
                </select>
            </div>

            <div id="optsBox" class="mb-4" style="display: none;">
                <label class="block text-sm font-medium text-gray-300 mb-1">Options</label>
                <p class="text-[10px] text-gray-500 mb-2">Comma separated (e.g. Yes, No, Maybe)</p>
                <input type="text" name="options" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-gray-200 focus:border-indigo-500 outline-none text-sm" placeholder="Option 1, Option 2, Option 3">
            </div>

            <div class="mb-6 flex items-center justify-between p-3 bg-gray-900 rounded-xl border border-gray-700">
                <span class="text-sm text-gray-300 font-medium">Required Field?</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_required" value="1" class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 rounded-xl shadow-lg shadow-indigo-500/20 transition-all text-sm">
                Insert Field
            </button>
        </form>
    </div>
</div>
@endsection
