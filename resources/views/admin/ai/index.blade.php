@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">🤖 AI Assistant</h1>
            <p class="text-gray-400 text-sm mt-1">AI-powered device diagnosis, troubleshooting & smart inventory forecasting</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- AI Technician Diagnosis Tool --}}
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center text-xl">🔍</div>
                <div>
                    <h2 class="text-white font-semibold">AI Device Diagnosis</h2>
                    <p class="text-gray-500 text-xs">Enter problem description to get AI analysis</p>
                </div>
            </div>

            <form id="diagnosisForm" action="{{ route('admin.ai.diagnose') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-400 text-xs mb-1">Device Type</label>
                        <select name="device_type" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                            <option value="laptop">Laptop</option>
                            <option value="desktop">Desktop PC</option>
                            <option value="cctv">CCTV Camera</option>
                            <option value="networking">Networking Device</option>
                            <option value="printer">Printer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1">Problem Description</label>
                        <textarea name="problem_description" rows="4" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm resize-none" placeholder="Describe the issue in detail... e.g., 'The laptop screen is flickering and showing vertical lines. Battery drains very fast. Sometimes the fan makes loud noise.'" required minlength="10"></textarea>
                    </div>
                    <button type="submit" id="diagnoseBtn" class="w-full bg-gradient-to-r from-yellow-600 to-amber-500 hover:from-yellow-500 hover:to-amber-400 text-white font-semibold py-3 rounded-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        Run AI Diagnosis
                    </button>
                </div>
            </form>

            {{-- AI Result display --}}
            <div id="aiResult" class="mt-5 hidden">
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                    <h4 class="text-yellow-400 font-semibold mb-3">🤖 AI Analysis Results</h4>
                    <div id="aiIssues" class="mb-3"></div>
                    <div id="aiSteps"></div>
                </div>
                <div id="aiParts" class="mt-3 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 hidden">
                    <h4 class="text-blue-400 font-semibold mb-2">🔩 Suggested Spare Parts in Inventory</h4>
                    <div id="aiPartsContent"></div>
                </div>
            </div>
        </div>

        {{-- Smart Inventory Forecast --}}
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center text-xl">📦</div>
                    <div>
                        <h2 class="text-white font-semibold">Smart Inventory Forecast</h2>
                        <p class="text-gray-500 text-xs">AI demand prediction based on 3-month sales</p>
                    </div>
                </div>
                <button onclick="loadForecast()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs rounded-lg transition-all">
                    🔄 Refresh
                </button>
            </div>

            <div id="forecastContainer">
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-3">🔮</div>
                    <p class="text-sm">Click Refresh to load AI inventory forecast</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    @if($lowStockParts->count() > 0)
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6">
        <h3 class="text-red-400 font-semibold mb-4">⚠️ Low Stock Alert — {{ $lowStockParts->count() }} Parts Need Attention</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($lowStockParts as $part)
            <div class="bg-black/20 border border-red-500/20 rounded-lg p-3">
                <p class="text-white text-sm font-medium">{{ $part->name }}</p>
                <p class="text-gray-400 text-xs">{{ $part->category }}</p>
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-red-400 font-bold text-lg">{{ $part->stock }}</span>
                    <span class="text-gray-500 text-xs">in stock</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent AI Diagnoses --}}
    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <h3 class="text-white font-semibold mb-4">Recent AI Diagnoses</h3>
        @if($recentDiagnoses->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left py-2 px-3">Customer</th>
                        <th class="text-left py-2 px-3">Problem</th>
                        <th class="text-left py-2 px-3">Confidence</th>
                        <th class="text-left py-2 px-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDiagnoses as $diag)
                    <tr class="border-t border-white/5">
                        <td class="py-2 px-3 text-gray-300 text-sm">{{ $diag->customer?->name ?? 'Walk-in' }}</td>
                        <td class="py-2 px-3 text-gray-400 text-sm">{{ str($diag->problem_description)->limit(60) }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 rounded text-xs {{ $diag->confidence_score >= 80 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                {{ $diag->confidence_score }}%
                            </span>
                        </td>
                        <td class="py-2 px-3 text-gray-500 text-xs">{{ $diag->created_at->format('d M, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-6">No AI diagnoses yet. Use the tool above to analyze device issues.</p>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
// AJAX AI Diagnosis
document.getElementById('diagnosisForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('diagnoseBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Analyzing...';

    const formData = new FormData(this);
    try {
        const response = await fetch('{{ route("admin.ai.diagnose") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: new URLSearchParams(formData),
        });
        const data = await response.json();

        if (data.success) {
            const result = data.analysis;
            document.getElementById('aiResult').classList.remove('hidden');

            // Issues
            document.getElementById('aiIssues').innerHTML = `
                <div class="flex flex-wrap gap-2 mb-3">
                    ${result.issues.map(i => `<span class="bg-yellow-500/20 text-yellow-400 text-xs px-2 py-1 rounded-full">⚠️ ${i}</span>`).join('')}
                </div>
                <div class="text-xs text-gray-400">Confidence: <span class="text-yellow-400 font-bold">${result.confidence}%</span></div>
            `;

            // Steps
            document.getElementById('aiSteps').innerHTML = `
                <div class="mt-3 space-y-1">
                    <p class="text-gray-400 text-xs font-semibold">TROUBLESHOOTING STEPS:</p>
                    ${result.steps.map(s => `<p class="text-gray-300 text-sm">${s}</p>`).join('')}
                </div>
            `;

            // Parts
            if (data.matched_parts && data.matched_parts.length > 0) {
                document.getElementById('aiParts').classList.remove('hidden');
                document.getElementById('aiPartsContent').innerHTML = data.matched_parts.map(p => `
                    <div class="flex items-center justify-between py-1 border-b border-white/5">
                        <span class="text-white text-sm">${p.name}</span>
                        <span class="text-blue-400 text-xs">${p.stock} in stock · ₹${p.price}</span>
                    </div>
                `).join('');
            }
        }
    } catch(err) {
        alert('AI diagnosis failed. Please try again.');
    }
    btn.disabled = false;
    btn.innerHTML = '🔍 Run AI Diagnosis';
});

// Load Inventory Forecast
async function loadForecast() {
    const container = document.getElementById('forecastContainer');
    container.innerHTML = '<div class="text-center py-8 text-yellow-400">⏳ Computing AI forecast...</div>';

    try {
        const resp = await fetch('{{ route("admin.ai.inventory.forecast") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });
        const data = await resp.json();
        const forecasts = data.forecasts;

        const statusColors = {
            'out_of_stock': 'text-red-400',
            'low_stock': 'text-yellow-400',
            'adequate': 'text-emerald-400'
        };

        container.innerHTML = `
            <div class="max-h-80 overflow-y-auto space-y-2 pr-1">
                ${forecasts.filter(f => f.reorder_alert).slice(0, 15).map(f => `
                    <div class="flex items-center justify-between bg-black/20 rounded-lg p-3">
                        <div>
                            <p class="text-white text-sm">${f.name}</p>
                            <p class="text-gray-500 text-xs">${f.category} · Avg ${f.avg_monthly_sales}/month</p>
                        </div>
                        <div class="text-right">
                            <p class="${statusColors[f.status]} font-bold text-lg">${f.current_stock}</p>
                            <p class="text-gray-500 text-xs">Reorder: +${f.reorder_quantity}</p>
                        </div>
                    </div>
                `).join('') || '<p class="text-emerald-400 text-center py-4">✅ All stock levels are adequate!</p>'}
            </div>
        `;
    } catch(e) {
        container.innerHTML = '<div class="text-red-400 text-center py-4">Failed to load forecast.</div>';
    }
}
</script>
@endpush
