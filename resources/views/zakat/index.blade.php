<x-app-layout>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/zakat-styles.css') }}">
@endpush

@section('content')
<div class="zakat-container">
    <h1>Zakat Calculator</h1>
    <p class="muted">Calculate your zakat based on your assets, liabilities and nisab.</p>

    <div class="zakat-card">
        <form id="zakatForm">
            @csrf
            <div class="grid-2">
                <div class="field">
                    <label>Cash</label>
                    <input type="number" step="0.01" name="cash" placeholder="0.00">
                </div>
                <div class="field">
                    <label>Business Assets</label>
                    <input type="number" step="0.01" name="business_assets" placeholder="0.00">
                </div>

                <div class="field">
                    <label>Receivables</label>
                    <input type="number" step="0.01" name="receivables" placeholder="0.00">
                </div>
                <div class="field">
                    <label>Investments</label>
                    <input type="number" step="0.01" name="investments" placeholder="0.00">
                </div>

                <div class="field">
                    <label>Gold (grams)</label>
                    <input type="number" step="0.01" name="gold_grams" placeholder="0">
                </div>
                <div class="field">
                    <label>Gold Carat (purity)</label>
                    <select name="gold_carat">
                        <option value="24" selected>24K (100% pure)</option>
                        <option value="22">22K (91.67%)</option>
                        <option value="21">21K (87.5%)</option>
                        <option value="18">18K (75%)</option>
                        <option value="14">14K (58.33%)</option>
                        <option value="10">10K (41.67%)</option>
                    </select>
                </div>

                <div class="field">
                    <label>Silver (grams)</label>
                    <input type="number" step="0.01" name="silver_grams" placeholder="0">
                </div>
                <div class="field">
                    <label>Liabilities</label>
                    <input type="number" step="0.01" name="liabilities" placeholder="0.00">
                </div>
                <div class="field">
                    <label>Year</label>
                    <input type="number" name="year" value="{{ $defaults['year'] }}">
                </div>
            </div>

            <div class="grid-2">
                <div class="field">
                    <label>Gold Price per Gram ({{ $defaults['currency'] }})</label>
                    <input type="number" step="0.01" name="gold_price_per_gram" value="{{ $defaults['gold_price_per_gram'] }}">
                </div>
                <div class="field">
                    <label>Silver Price per Gram ({{ $defaults['currency'] }})</label>
                    <input type="number" step="0.01" name="silver_price_per_gram" value="{{ $defaults['silver_price_per_gram'] }}">
                </div>

                <div class="field">
                    <label>Nisab Basis</label>
                    <select name="basis">
                        <option value="gold" {{ $defaults['basis'] === 'gold' ? 'selected' : '' }}>Gold (85g)</option>
                        <option value="silver" {{ $defaults['basis'] === 'silver' ? 'selected' : '' }}>Silver (595g)</option>
                    </select>
                </div>
                <div class="field">
                    <label>Zakat Rate (%)</label>
                    <input type="number" step="0.01" name="zakat_rate" value="{{ $defaults['zakat_rate'] }}">
                </div>
            </div>

            <div class="actions">
                <button type="button" id="calcBtn" class="btn-primary">Calculate</button>
            </div>
        </form>
    </div>

    <div class="results-card" id="results" style="display:none;">
        <h2>Results</h2>
        <div class="summary">
            <div><strong>Nisab ({{ $defaults['currency'] }}):</strong> <span id="nisabValue">0.00</span></div>
            <div><strong>Net Assets ({{ $defaults['currency'] }}):</strong> <span id="netAssets">0.00</span></div>
            <div><strong>Zakat Due ({{ $defaults['currency'] }}):</strong> <span id="zakatDue">0.00</span></div>
            <div><strong>Above Nisab:</strong> <span id="aboveNisab">No</span></div>
        </div>
        <h3>Breakdown</h3>
        <ul id="breakdown"></ul>
    </div>
</div>

<script>
document.getElementById('calcBtn').addEventListener('click', async () => {
    const form = document.getElementById('zakatForm');
    const data = new FormData(form);
    const payload = Object.fromEntries(data.entries());

    const res = await fetch('{{ route('zakat.calculate') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    });

    const result = await res.json();
    if (!result.success) return;

    document.getElementById('nisabValue').textContent = result.nisab_value.toFixed(2);
    document.getElementById('netAssets').textContent = result.totals.net_assets.toFixed(2);
    document.getElementById('zakatDue').textContent = result.zakat_due.toFixed(2);
    document.getElementById('aboveNisab').textContent = result.is_above_nisab ? 'Yes' : 'No';

    const ul = document.getElementById('breakdown');
    ul.innerHTML = '';
    const map = result.totals;
    const labels = {
        cash: 'Cash',
        gold_value: 'Gold (value)',
        silver_value: 'Silver (value)',
        business_assets: 'Business Assets',
        receivables: 'Receivables',
        investments: 'Investments',
        liabilities: 'Liabilities',
        total_assets: 'Total Assets',
        net_assets: 'Net Assets',
    };
    Object.keys(labels).forEach(k => {
        const li = document.createElement('li');
        li.textContent = `${labels[k]}: ${Number(map[k]).toFixed(2)}`;
        ul.appendChild(li);
    });
    document.getElementById('results').style.display = 'block';
});
</script>
</x-app-layout>