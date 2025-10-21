@extends('layouts.app')

@section('title', 'Make a Donation')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/donation-styles.css') }}">
@endpush

@section('content')
<div class="donation-container">
    <!-- Introduction Section -->
    <div class="intro-section">
        <h1>💸 Support Hayat Hadiah</h1>
        <p>Support the mission of Hayat Hadiah by contributing to Islamic causes. Your generosity brings barakah to our community.</p>
    </div>

    <!-- Quran Verse Section -->
    <div class="verse-box">
        <div class="verse-icon">📖</div>
        <blockquote class="verse-text">
            "{{ $randomVerse['text'] }}"
        </blockquote>
        <cite class="verse-reference">— {{ $randomVerse['reference'] }}</cite>
    </div>

    <!-- Donation Goals Progress -->
    @if($categoriesWithGoals->count() > 0)
    <div class="goals-section">
        <h2>🎯 Current Donation Goals</h2>
        <p class="goals-subtitle">Help us reach our goals and support our community</p>
        
        <div class="goals-grid">
            @foreach($categoriesWithGoals as $category)
            <div class="goal-card {{ $category->is_goal_reached ? 'goal-reached' : '' }}">
                <div class="goal-header">
                    <h3>{{ $category->name }}</h3>
                    @if($category->is_goal_reached)
                        <span class="goal-badge reached">✅ Goal Reached!</span>
                    @else
                        <span class="goal-badge">{{ $category->progress_percentage }}%</span>
                    @endif
                </div>
                
                @if($category->description)
                <p class="goal-description">{{ Str::limit($category->description, 80) }}</p>
                @endif
                
                <div class="progress-bar-container">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $category->progress_percentage }}%"></div>
                    </div>
                </div>
                
                <div class="goal-stats">
                    <div class="stat">
                        <span class="stat-label">Raised</span>
                        <span class="stat-value">৳ {{ number_format($category->total_raised, 2) }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Goal</span>
                        <span class="stat-value">৳ {{ number_format($category->goal_amount, 2) }}</span>
                    </div>
                </div>
                
                @if(!$category->is_goal_reached)
                <div class="goal-remaining">
                    <small>৳ {{ number_format($category->remaining_amount, 2) }} remaining</small>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Donation Form -->
    <div class="donation-form-card">
        <h2>Make Your Donation</h2>
        
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('donations.store') }}" method="POST" id="donationForm">
            @csrf

            <!-- Donor Information -->
            <div class="form-section">
                <h3>Your Information</h3>
                
                <div class="form-group">
                    <label for="donor_name">Name <span class="required">*</span></label>
                    <input type="text" id="donor_name" name="donor_name" 
                           value="{{ old('donor_name') }}" 
                           placeholder="Enter your name or 'Anonymous'" 
                           required>
                    <small>You can enter "Anonymous" if you prefer</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="donor_email">Email <span class="required">*</span></label>
                        <input type="email" id="donor_email" name="donor_email" 
                               value="{{ old('donor_email') }}" 
                               placeholder="your@email.com" 
                               required>
                        <small>For receipt and tracking</small>
                    </div>

                    <div class="form-group">
                        <label for="donor_phone">Phone <span class="required">*</span></label>
                        <input type="tel" id="donor_phone" name="donor_phone" 
                               value="{{ old('donor_phone') }}" 
                               placeholder="01XXXXXXXXX" 
                               required>
                        <small>Required for payment</small>
                    </div>
                </div>
            </div>

            <!-- Donation Details -->
            <div class="form-section">
                <h3>Donation Details</h3>
                
                <div class="form-group">
                    <label for="amount">Amount (BDT) <span class="required">*</span></label>
                    <div class="amount-buttons">
                        <button type="button" class="amount-btn" data-amount="100">৳ 100</button>
                        <button type="button" class="amount-btn" data-amount="500">৳ 500</button>
                        <button type="button" class="amount-btn" data-amount="1000">৳ 1,000</button>
                        <button type="button" class="amount-btn" data-amount="5000">৳ 5,000</button>
                    </div>
                    <input type="number" id="amount" name="amount" 
                           value="{{ old('amount') }}" 
                           placeholder="Enter custom amount" 
                           min="10" 
                           step="0.01" 
                           required>
                    <small>Minimum donation: ৳ 10</small>
                </div>

                <div class="form-group">
                    <label for="category_id">Donation Purpose <span class="required">*</span></label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a cause</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($categories->where('name', 'Zakat')->count() > 0)
                        <small>For Zakat calculation, visit our <a href="{{ route('zakat.index') }}">Zakat Calculator</a></small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="message">Message (Optional)</label>
                    <textarea id="message" name="message" 
                              placeholder="E.g., 'For my parents', 'In memory of...'"
                              rows="3">{{ old('message') }}</textarea>
                    <small>Maximum 500 characters</small>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="form-section">
                <h3>Payment Method</h3>
                
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bkash" 
                               {{ old('payment_method', 'bkash') == 'bkash' ? 'checked' : '' }} 
                               required>
                        <div class="payment-card">
                            <div class="payment-logo bkash-logo">bKash</div>
                            <span>Pay with bKash</span>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="nagad" 
                               {{ old('payment_method') == 'nagad' ? 'checked' : '' }}>
                        <div class="payment-card">
                            <div class="payment-logo nagad-logo">Nagad</div>
                            <span>Pay with Nagad</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn-donate" id="submitBtn">
                    <span class="btn-icon">💖</span>
                    <span class="btn-text">Donate Now</span>
                </button>
            </div>

            <p class="sandbox-note">
                <small>🔒 Note: Currently in test mode (sandbox). No real money will be charged.</small>
            </p>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountButtons = document.querySelectorAll('.amount-btn');
    const amountInput = document.getElementById('amount');
    const form = document.getElementById('donationForm');
    const submitBtn = document.getElementById('submitBtn');

    // Amount button clicks
    amountButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            
            // Visual feedback
            amountButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Remove active state when typing custom amount
    amountInput.addEventListener('input', function() {
        amountButtons.forEach(b => b.classList.remove('active'));
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="btn-icon">⏳</span><span class="btn-text">Processing...</span>';
    });
});
</script>
@endsection
