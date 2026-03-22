@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto animate-fade-in-up">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.employees.index') }}" class="text-slate-400 hover:text-blue-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Payroll
                </a>
                <h1 class="text-3xl font-black text-slate-900">Add <span class="text-blue-600">Employee</span></h1>
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
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Personal & System Login</h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Initial Password</label>
                            <input type="password" name="password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mt-8 pt-4">Company & Accounting</h3>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-blue-600 uppercase mb-2">Job Designation / Role</label>
                            <input type="text" name="role" value="{{ old('role') }}" placeholder="e.g. Technician, Manager, Accountant" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all font-bold text-slate-800 uppercase tracking-tight">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-green-600 uppercase mb-2">Monthly Base Salary (₹)</label>
                            <input type="number" step="0.01" name="salary" value="{{ old('salary', 0.00) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400 transition-all font-black text-slate-800 text-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-purple-600 uppercase mb-2">ZKTeco Biometric User ID (Hardware mapping)</label>
                        <input type="text" name="biometric_id" value="{{ old('biometric_id') }}" placeholder="Leave blank if not tracking via ZKTeco" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-purple-100 focus:border-purple-400 transition-all font-bold text-slate-800 font-mono tracking-widest">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase">This ID MUST match the employee ID programmed directly into the wall-mounted fingerprint scanner.</p>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest shadow-xl transition-all">
                            Onboard Employee
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
