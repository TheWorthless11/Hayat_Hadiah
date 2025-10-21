@extends('layouts.app')

@section('title', 'Thank You for Your Donation')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/donation-styles.css') }}">
@endpush

@section('content')
<div class="thank-you-container">
    <div class="thank-you-card">
        <!-- Success Icon -->
        <div class="success-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="38" stroke="#10b981" stroke-width="4"/>
                <path d="M25 40L35 50L55 30" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Thank You Message -->
        <h1>Jazakallahu Khairan! 🤲</h1>
        <p class="thank-you-message">Your donation has been received successfully.</p>

        <!-- Transaction Details -->
        <div class="transaction-details">
            <h3>Transaction Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Transaction Reference:</span>
                <span class="detail-value">{{ $donation->transaction_ref }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <span class="detail-value amount-highlight">{{ $donation->formatted_amount }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Purpose:</span>
                <span class="detail-value">{{ $donation->category->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ ucfirst($donation->payment_method) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date & Time:</span>
                <span class="detail-value">{{ $donation->created_at->format('F d, Y - h:i A') }}</span>
            </div>

            @if($donation->message)
            <div class="detail-row">
                <span class="detail-label">Your Message:</span>
                <span class="detail-value">{{ $donation->message }}</span>
            </div>
            @endif
        </div>

        <!-- Dua Box -->
        <div class="dua-box">
            <div class="dua-icon">🤲</div>
            <p class="dua-text">
                "May Allah accept your charity and multiply your rewards. May He bless you with prosperity and make this donation a means of purification and elevation for you."
            </p>
            <p class="dua-arabic">اللَّهُمَّ تَقَبَّلْ وَبَارِكْ</p>
            <cite>— Ameen</cite>
        </div>

        <!-- Receipt Notice -->
        <div class="receipt-notice">
            <span class="notice-icon">📧</span>
            <p>A receipt has been sent to <strong>{{ $donation->donor_email }}</strong></p>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('donations.index') }}" class="btn-secondary">
                Make Another Donation
            </a>
            <a href="{{ route('home') }}" class="btn-primary">
                Return to Home
            </a>
        </div>

        <!-- Inspirational Quote -->
        <div class="quote-footer">
            <p>"The believer's shade on the Day of Resurrection will be their charity."</p>
            <cite>— Hadith, Tirmidhi</cite>
        </div>
    </div>
</div>
@endsection
