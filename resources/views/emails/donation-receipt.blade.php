<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 1.1rem;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .message {
            font-size: 0.95rem;
            color: #4b5563;
            margin-bottom: 25px;
            line-height: 1.7;
        }
        .receipt-box {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }
        .receipt-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #14b8a6;
            margin-bottom: 15px;
            text-align: center;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }
        .receipt-row:last-child {
            border-bottom: none;
        }
        .receipt-label {
            color: #6b7280;
            font-weight: 500;
        }
        .receipt-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
        }
        .amount-highlight {
            color: #10b981;
            font-size: 1.2rem;
        }
        .dua-box {
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, rgba(13, 148, 136, 0.1) 100%);
            border: 2px solid rgba(20, 184, 166, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .dua-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .dua-text {
            font-size: 0.9rem;
            color: #374151;
            line-height: 1.7;
            margin: 10px 0;
            font-style: italic;
        }
        .dua-arabic {
            font-size: 1.4rem;
            color: #14b8a6;
            margin: 15px 0;
            font-weight: 600;
        }
        .verse-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .verse-text {
            font-size: 0.9rem;
            color: #78350f;
            font-style: italic;
            margin: 0;
        }
        .verse-reference {
            font-size: 0.85rem;
            color: #92400e;
            margin-top: 8px;
            text-align: right;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 0.85rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #14b8a6;
            text-decoration: none;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 5px;
            color: #14b8a6;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .receipt-row {
                flex-direction: column;
                gap: 5px;
            }
            .receipt-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🤲 Jazakallahu Khairan!</h1>
            <p>Your donation has been received</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Assalamu Alaikum {{ $donation->donor_name }},</p>
            
            <p class="message">
                Thank you for your generous donation to <strong>Hayat Hadiah</strong>. 
                May Allah accept your contribution and multiply your rewards. Your donation helps support 
                <strong>{{ $donation->category->name }}</strong> and makes a real difference in our community.
            </p>

            <!-- Receipt Details -->
            <div class="receipt-box">
                <div class="receipt-title">📋 Donation Receipt</div>
                
                <div class="receipt-row">
                    <span class="receipt-label">Transaction Reference:</span>
                    <span class="receipt-value">{{ $donation->transaction_ref }}</span>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Date & Time:</span>
                    <span class="receipt-value">{{ $donation->created_at->format('F d, Y - h:i A') }}</span>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Amount:</span>
                    <span class="receipt-value amount-highlight">৳ {{ number_format($donation->amount, 2) }}</span>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Donation Purpose:</span>
                    <span class="receipt-value">{{ $donation->category->name }}</span>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Payment Method:</span>
                    <span class="receipt-value">{{ ucfirst($donation->payment_method) }}</span>
                </div>

                @if($donation->message)
                <div class="receipt-row">
                    <span class="receipt-label">Your Message:</span>
                    <span class="receipt-value">{{ $donation->message }}</span>
                </div>
                @endif
            </div>

            <!-- Dua Box -->
            <div class="dua-box">
                <div class="dua-icon">🤲</div>
                <p class="dua-text">
                    "May Allah accept your charity and multiply your rewards. May He bless you with prosperity 
                    and make this donation a means of purification and elevation for you."
                </p>
                <div class="dua-arabic">اللَّهُمَّ تَقَبَّلْ وَبَارِكْ</div>
                <p style="font-size: 0.85rem; color: #6b7280; margin: 0;">— Ameen</p>
            </div>

            <!-- Quran Verse -->
            <div class="verse-box">
                <p class="verse-text">
                    "The believer's shade on the Day of Resurrection will be their charity."
                </p>
                <p class="verse-reference">— Hadith, Tirmidhi</p>
            </div>

            <p class="message">
                This email serves as your official receipt. Please keep it for your records. 
                If you have any questions about your donation, please don't hesitate to contact us.
            </p>

            <!-- Button -->
            <div class="button-container">
                <a href="{{ url('/donations') }}" class="button">Make Another Donation</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Hayat Hadiah</strong></p>
            <p>Supporting Islamic causes and strengthening our community</p>
            <p>📧 Email: support@hayathadiah.com | 🌐 Website: <a href="{{ url('/') }}">{{ url('/') }}</a></p>
            
            <div class="social-links">
                <p style="margin: 10px 0 5px; font-size: 0.8rem;">
                    © {{ date('Y') }} Hayat Hadiah. All rights reserved.
                </p>
            </div>
            
            <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 15px;">
                This is an automated receipt for your donation. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
