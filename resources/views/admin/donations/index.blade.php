@extends('layouts.app')

@section('title', 'Admin - Donation Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/donation-styles.css') }}">
@endpush

@section('content')
<div class="admin-donation-container">
    <div class="admin-header">
        <h1>💸 Donation Management</h1>
        <div class="admin-actions">
            <a href="{{ route('admin.donations.export') }}" class="btn-export">
                📥 Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-icon">💰</div>
            <div class="card-content">
                <h3>Total Donations</h3>
                <p class="card-value">৳ {{ number_format($totalDonations, 2) }}</p>
                <small>All time</small>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">📅</div>
            <div class="card-content">
                <h3>This Month</h3>
                <p class="card-value">৳ {{ number_format($thisMonthTotal, 2) }}</p>
                <small>{{ date('F Y') }}</small>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">📊</div>
            <div class="card-content">
                <h3>Total Transactions</h3>
                <p class="card-value">{{ $totalTransactions }}</p>
                <small>All statuses</small>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">⏳</div>
            <div class="card-content">
                <h3>Pending</h3>
                <p class="card-value">{{ $pendingCount }}</p>
                <small>Awaiting confirmation</small>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">✅</div>
            <div class="card-content">
                <h3>Success Rate</h3>
                <p class="card-value">{{ $successRate }}%</p>
                <small>Completed payments</small>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-card">
            <h3>Donations by Category</h3>
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h3>Monthly Trend (Last 6 Months)</h3>
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form action="{{ route('admin.donations.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ request('start_date', date('Y-m-01')) }}">
            </div>

            <div class="filter-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ request('end_date', date('Y-m-d')) }}">
            </div>

            <div class="filter-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" 
                                {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method">
                    <option value="">All Methods</option>
                    <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                    <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply Filters</button>
                <a href="{{ route('admin.donations.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Donations Table -->
    <div class="donations-table-section">
        <h3>Recent Donations</h3>
        
        @if($donations->count() > 0)
        <div class="table-responsive">
            <table class="donations-table">
                <thead>
                    <tr>
                        <th>Transaction Ref</th>
                        <th>Date</th>
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr>
                        <td class="ref-cell">
                            <code>{{ $donation->transaction_ref }}</code>
                        </td>
                        <td class="date-cell">
                            {{ $donation->created_at->format('M d, Y') }}
                            <small>{{ $donation->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="donor-cell">
                            <strong>{{ $donation->donor_name }}</strong>
                            <small>{{ $donation->donor_email }}</small>
                        </td>
                        <td class="amount-cell">
                            <strong>৳ {{ number_format($donation->amount, 2) }}</strong>
                        </td>
                        <td class="category-cell">
                            <span class="category-badge">{{ $donation->category->name }}</span>
                        </td>
                        <td class="method-cell">
                            <span class="method-badge {{ $donation->payment_method }}">
                                {{ ucfirst($donation->payment_method) }}
                            </span>
                        </td>
                        <td class="status-cell">
                            <span class="status-badge {{ $donation->payment_status }}">
                                {{ ucfirst($donation->payment_status) }}
                            </span>
                        </td>
                        <td class="message-cell">
                            @if($donation->message)
                                <span class="message-preview" title="{{ $donation->message }}">
                                    {{ Str::limit($donation->message, 30) }}
                                </span>
                            @else
                                <span class="no-message">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $donations->links() }}
        </div>
        @else
        <div class="no-data">
            <p>No donations found matching your filters.</p>
        </div>
        @endif
    </div>
</div>

<!-- Chart.js for charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Chart
    const categoryData = @json($donationsByCategory);
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categoryData.map(item => item.category),
            datasets: [{
                label: 'Total Amount (BDT)',
                data: categoryData.map(item => item.total),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '৳ ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Monthly Trend Chart
    const trendData = @json($monthlyTrend);
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.month),
            datasets: [{
                label: 'Monthly Donations (BDT)',
                data: trendData.map(item => item.total),
                borderColor: 'rgba(20, 184, 166, 1)',
                backgroundColor: 'rgba(20, 184, 166, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '৳ ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
