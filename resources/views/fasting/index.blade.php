@extends('layouts.app')

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

        <div class="calendar">
            <table id="calendarTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sehri (12h)</th>
                        <th>Iftar (12h)</th>
                        <th>Ramadan</th>
                        <th>Source</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="6" class="muted">Pick a month and location, then click Generate.</td></tr>
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
    tbody.innerHTML = '';
    if (!data.results || data.results.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="muted">No results generated. Check location/timezone data.</td></tr>';
        return;
    }
    data.results.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.date}</td>
            <td>${row.sehri_display || '-'}</td>
            <td>${row.iftar_display || '-'}</td>
            <td>${row.is_ramadan ? `<span class="badge">Day ${row.ramadan_day}</span>` : '-'}</td>
            <td class="muted">${row.source || '-'}</td>
            <td>${row.saved ? 'Saved' : 'Preview'}</td>
        `;
        tbody.appendChild(tr);
    });
});
</script>
@endsection
