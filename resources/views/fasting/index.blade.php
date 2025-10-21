<x-app-layout>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/fasting-styles.css') }}">
@endpush

@section('content')
<div class="fasting-container">
    <h1> Fasting Schedule</h1>
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
            <!-- <div class="field">
                <label>Mark as Ramadan</label>
                <select id="is_ramadan">
                    <option value="1">Yes (Ramadan)</option>
                    <option value="0">No</option>
                </select>
            </div> -->
            <div class="field" style="align-self:end;">
                <button class="btn-primary" id="generateBtn">Generate Schedule</button>
            </div>
        </div>

        <!-- Source Info Display -->
        <div id="sourceInfo" class="source-info" style="display: none;">
            <strong> Source:</strong> <span id="sourceText">-</span>
        </div>

        <div class="calendar">
            <!-- Placeholder shown before results -->
            <div id="calendarPlaceholder" class="calendar-placeholder">
                <p class="muted">Pick a month and location, then click Generate.</p>
            </div>

            <!-- Actual calendar table, hidden until results are available -->
            <div id="calendarContainer" style="display: none;">
                <table id="calendarTable">
                    <thead id="calendarHead" style="display: none;">
                        <tr>
                            <th>Date</th>
                            <th>Sehri (Fajr)</th>
                            <th>Iftar (Maghrib)</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('generateBtn').addEventListener('click', async () => {
    const month = document.getElementById('month').value;
    const location_id = document.getElementById('location_id').value;
    // const is_ramadan = document.getElementById('is_ramadan').value;

    const res = await fetch('/fasting/generate', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ month, location_id })
    });

    const data = await res.json();
    const tbody = document.querySelector('#calendarTable tbody');
    const sourceInfo = document.getElementById('sourceInfo');
    const sourceText = document.getElementById('sourceText');
    
    tbody.innerHTML = '';
    
    const placeholder = document.getElementById('calendarPlaceholder');
    const container = document.getElementById('calendarContainer');

    if (!data.results || data.results.length === 0) {
        // hide table and show placeholder when there are no results
        if (container) container.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
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

    // show the table and header now that we have results
    if (placeholder) placeholder.style.display = 'none';
    if (container) container.style.display = 'block';
    const head = document.getElementById('calendarHead');
    if (head) head.style.display = 'table-header-group';

    data.results.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.date}</td>
            <td class="time-cell">${row.sehri_display || '-'}</td>
            <td class="time-cell">${row.iftar_display || '-'}</td>
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
