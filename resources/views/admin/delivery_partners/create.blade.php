@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.delivery-partners.index') }}" class="text-slate-400 hover:text-orange-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Database
                </a>
                <h1 class="text-3xl font-black text-slate-900">Add <span class="text-orange-600">Delivery Partner</span></h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-sm">
                <ul class="list-disc pl-5 font-bold text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <form action="{{ route('admin.delivery-partners.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Logistics Account Info</h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Partner Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all font-bold text-slate-800">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Account Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Vehicle Number</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all font-bold text-slate-800" placeholder="e.g. TN-01-AB-1234">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Initial Password</label>
                            <input type="password" name="password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 mt-8">
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-500 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest shadow-xl shadow-orange-600/20 transition-all">
                            Register Delivery Partner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
