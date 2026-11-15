<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>مرحباً بك في {{ $shop->name }} — ThreeDigits Loyalty</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap');

        body, table, td, p, a, h1, h2, h3, li {
            font-family: 'Cairo', Tahoma, Arial, sans-serif !important;
        }
    </style>
</head>
<body style="margin: 0; padding: 24px 12px; background-color: #010A29; color: #f8fafc; font-family: 'Cairo', Tahoma, Arial, sans-serif; direction: rtl; text-align: right;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 580px; margin: 0 auto; background-color: #071033; border: 1px solid rgba(116, 62, 226, 0.35); border-radius: 22px; overflow: hidden;">
        {{-- Header --}}
        <tr>
            <td style="padding: 28px 28px 22px; background: linear-gradient(135deg, #743EE2 0%, #6145E8 52%, #3B42D5 100%); text-align: center;">
                <div style="font-size: 13px; font-weight: 700; color: #e0e7ff; letter-spacing: 1px; margin-bottom: 6px;">
                    THREEDIGITS LOYALTY
                </div>
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff;">
                    مرحباً بك في {{ $shop->name }} 🎉
                </h1>
            </td>
        </tr>

        {{-- Body --}}
        <tr>
            <td style="padding: 28px;">
                <p style="margin: 0 0 14px; font-size: 16px; line-height: 1.8; color: #f1f5f9;">
                    أهلاً <strong>{{ $customer->name }}</strong>،
                </p>
                <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.8; color: #cbd5e1;">
                    يسعدنا انضمامك إلى برنامج الولاء الرقمي الخاص بـ <strong>{{ $shop->name }}</strong> عبر منصة <strong>ThreeDigits Loyalty</strong>. تم إصدار بطاقتك الرقمية بنجاح وهي جاهزة للاستخدام فوراً!
                </p>

                {{-- Campaign Box --}}
                <div style="background-color: #0C1742; border: 1px solid rgba(116, 62, 226, 0.32); border-radius: 16px; padding: 18px; margin-bottom: 24px;">
                    <div style="font-size: 14px; color: #94a3b8; margin-bottom: 4px;">حملة الولاء الحالية:</div>
                    <div style="font-size: 17px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">
                        {{ $card->campaign->name ?? 'بطاقة الولاء' }}
                    </div>
                    <div style="font-size: 14px; color: #A78BFA; font-weight: 700;">
                        🎁 المكافأة: {{ $card->campaign->reward_name ?? 'مكافأة مجانية' }} (عند جمع {{ $card->campaign->required_purchases_count ?? 8 }} أختام)
                    </div>
                </div>

                {{-- Primary CTA & Direct Wallet Buttons --}}
                <div style="text-align: center; margin: 28px 0 18px;">
                    <a href="{{ $cardUrl }}"
                       style="display: inline-block; background: linear-gradient(135deg, #743EE2 0%, #6145E8 50%, #3B42D5 100%); color: #ffffff; text-decoration: none; font-weight: 800; font-size: 16px; padding: 14px 32px; border-radius: 999px;">
                        افتح بطاقة الولاء الخاصة بك
                    </a>
                </div>

                <div style="text-align: center; margin: 0 0 24px;">
                    <a href="{{ route('loyalty-card.apple-pass', $card->token) }}"
                       style="display: inline-block; background-color: #0f172a; border: 1px solid #334155; color: #f8fafc; text-decoration: none; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 12px; margin: 4px;">
                         أضف إلى Apple Wallet (.pkpass)
                    </a>
                    <a href="{{ route('loyalty-card.google-pass', $card->token) }}"
                       style="display: inline-block; background-color: #0f172a; border: 1px solid #334155; color: #f8fafc; text-decoration: none; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 12px; margin: 4px;">
                        💳 أضف إلى Google Wallet
                    </a>
                </div>

                {{-- Instructions --}}
                <div style="background-color: #050C2A; border: 1px solid rgba(116, 62, 226, 0.25); border-radius: 16px; padding: 20px; margin-top: 20px;">
                    <h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 800; color: #A78BFA;">
                        📱 تعليمات الاستخدام وإضافة البطاقة إلى المحفظة (Wallet):
                    </h3>
                    <ol style="margin: 0; padding: 0 20px 0 0; color: #cbd5e1; font-size: 14px; line-height: 1.9;">
                        <li>اضغط على زر <strong>"افتح بطاقة الولاء الخاصة بك"</strong> أعلاه لعرض بطاقتك ورمز الـ QR الخاص بك.</li>
                        <li><strong>لمستخدمي iPhone (آيفون):</strong> اضغط على زر <strong>"أضف إلى Apple Wallet"</strong> لتحميل بطاقة المحفظة (<code>.pkpass</code>) وإضافتها مباشرة إلى تطبيق Apple Wallet.</li>
                        <li><strong>لمستخدمي Android (أندرويد):</strong> اضغط على زر <strong>"أضف إلى Google Wallet"</strong> لحفظ البطاقة مباشرة داخل تطبيق Google Wallet.</li>
                        <li><strong>عند كل عملية شراء:</strong> اعرض البطاقة من محفظة هاتفك للكاشير ليتم مسح رمز الـ QR وإضافة ختم جديد إلى رصيدك.</li>
                    </ol>
                </div>

                <p style="margin: 24px 0 0; font-size: 13px; color: #64748b; text-align: center;">
                    رابط البطاقة المباشر: <a href="{{ $cardUrl }}" style="color: #818cf8;">{{ $cardUrl }}</a>
                </p>
            </td>
        </tr>

        {{-- Footer --}}
        <tr>
            <td style="padding: 18px 28px; background-color: #090d16; border-top: 1px solid #1e293b; text-align: center; font-size: 12px; color: #64748b;">
                مرسل من قِبل <strong>ThreeDigits Loyalty</strong> بالنيابة عن <strong>{{ $shop->name }}</strong>
            </td>
        </tr>
    </table>
</body>
</html>
