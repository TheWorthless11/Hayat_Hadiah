<x-app-layout>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/fasting-styles.css') }}">
@endpush

@section('content')
<div class="fasting-container">
    <h1>🌙 Fasting Schedule</h1>
    <p class="muted">Generate Suhoor (Sehri) and Iftar times for a selected month and location.</p>

    <div class="fasting-card">
        <div class="controls">
            <div class="field">
                <label>Month</label>
                <input type="month" id="month" value="{{ $defaultMonth }}">
            </div>
            <div class="field">
                <label>Location</label>
                <select id="location_id">
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name ?? ($loc->city . ', ' . $loc->country) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Mark as Ramadan</label>
                <select id="is_ramadan">
                    <option value="1">Yes (Ramadan)</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="field" style="align-self:end;">
                <button class="btn-primary" id="generateBtn">Generate Schedule</button>
            </div>
        </div>

        <!-- Source Info Display -->
        <div id="sourceInfo" class="source-info" style="display: none;">
            <strong>📍 Source:</strong> <span id="sourceText">-</span>
        </div>

        <div class="calendar">
            <table id="calendarTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sehri (Fajr)</th>
                        <th>Iftar (Maghrib)</th>
                        <th>Ramadan Day</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="muted">Pick a month and location, then click Generate.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('generateBtn').addEventListener('click', async () => {
    const month = document.getElementById('month').value;
    const location_id = document.getElementById('location_id').value;
    const is_ramadan = document.getElementById('is_ramadan').value;

    const res = await fetch('/fasting/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ month, location_id, is_ramadan: is_ramadan === '1', persist: true })
    });

    const data = await res.json();
    const tbody = document.querySelector('#calendarTable tbody');
    const sourceInfo = document.getElementById('sourceInfo');
    const sourceText = document.getElementById('sourceText');
    
    tbody.innerHTML = '';
    
    if (!data.results || data.results.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="muted">No results generated. Check location/timezone data.</td></tr>';
        sourceInfo.style.display = 'none';
        return;
    }

    // Show source info at the top (get from first result)
    if (data.results[0] && data.results[0].source) {
        sourceText.textContent = data.results[0].source;
        sourceInfo.style.display = 'block';
    } else {
        sourceInfo.style.display = 'none';
    }

    data.results.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.date}</td>
            <td class="time-cell">${row.sehri_display || '-'}</td>
            <td class="time-cell">${row.iftar_display || '-'}</td>
            <td>${row.is_ramadan ? `<span class="badge">Day ${row.ramadan_day}</span>` : '-'}</td>
            <td>${row.saved ? '<span class="status-saved">✓ Saved</span>' : '<span class="status-preview">Preview</span>'}</td>
        `;
        tbody.appendChild(tr);
    });
});

// Encourage native dropdowns to open downward by ensuring viewport space
(() => {
    const card = document.querySelector('.fasting-card');
    const selects = [
        document.getElementById('location_id'),
        document.getElementById('is_ramadan'),
        document.getElementById('month')
    ].filter(Boolean);

    const ensureDownward = (el) => {
        try {
            // Center in viewport to bias dropdown direction
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Add temporary bottom padding to allow space for dropdown
            card.classList.add('dropdown-space');
            // Remove after some time or on blur/change
            const cleanup = () => card.classList.remove('dropdown-space');
            el.addEventListener('blur', cleanup, { once: true });
            el.addEventListener('change', cleanup, { once: true });
            setTimeout(cleanup, 1500);
        } catch (e) { /* no-op */ }
    };

    selects.forEach(sel => {
        sel.addEventListener('mousedown', () => ensureDownward(sel));
        sel.addEventListener('focus', () => ensureDownward(sel));
    });
})();
</script>
</x-app-layout>
