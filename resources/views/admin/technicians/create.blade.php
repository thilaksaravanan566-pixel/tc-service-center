@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto animate-fade-in-up">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.technicians.index') }}" class="text-slate-400 hover:text-blue-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Database
                </a>
                <h1 class="text-3xl font-black text-slate-900">Register <span class="text-blue-600">Technician</span></h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm">
                <ul class="list-disc pl-5 font-medium text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('admin.technicians.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Email Address (Login ID)</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Secure Password</label>
                            <input type="password" name="password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-xl transition-all">
                            Authorize Access
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
