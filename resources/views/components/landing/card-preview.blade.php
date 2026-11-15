<section class="dl-section" style="background: #040D30;">
    <div class="dl-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem; align-items: center;">
        <div>
            <span class="dl-eyebrow">{{ app()->getLocale() === 'ar' ? 'بطاقة العميل' : 'Customer Pass' }}</span>
            <h2 class="font-heading" style="font-size: clamp(1.9rem, 3.2vw, 2.7rem); font-weight: 800; margin: 0.9rem 0 1rem; line-height: 1.15;">
                {{ app()->getLocale() === 'ar' ? 'بطاقة رقمية أنيقة بدون تحميل تطبيق' : 'A Passbook Experience Without App Downloads' }}
            </h2>
            <p style="color: #94a3b8; font-size: 1rem; line-height: 1.7; margin-bottom: 1.5rem;">
                {{ app()->getLocale() === 'ar' ? 'يفتح العميل بطاقته عبر رابط فريد ويضيفها للشاشة الرئيسية في ثوانٍ لمتابعة أختامه ومكافآته.' : 'Every customer gets a unique, instant web pass they can pin to their home screen—showing live stamps, unlocked rewards, and their personal QR code.' }}
            </p>
            <a href="{{ url('/shop/login') }}" class="dl-btn-gold">
                {{ app()->getLocale() === 'ar' ? 'جرّب لوحة المتجر' : 'Open Merchant Portal' }}
            </a>
        </div>

        <div class="dl-card" style="padding: 1.75rem; max-width: 380px; width: 100%; margin: 0 auto; background: linear-gradient(155deg, #0E1A4A 0%, #050C2A 100%); border-color: rgba(116,62,226,0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <div style="font-size: 0.72rem; color: #743EE2; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">ThreeDigits Pass</div>
                    <div style="font-size: 1.15rem; font-weight: 800;">Bite Burger House</div>
                </div>
                <span style="padding: 0.3rem 0.75rem; border-radius: 999px; background: rgba(16,185,129,0.16); color: #10b981; font-size: 0.75rem; font-weight: 700;">READY</span>
            </div>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.45rem; margin-bottom: 1.25rem;">
                @for($i = 1; $i <= 5; $i++)
                    <div style="aspect-ratio: 1; border-radius: 10px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">✓</div>
                @endfor
            </div>
            <div style="font-size: 0.88rem; color: #10b981; font-weight: 700;">
                🎉 {{ app()->getLocale() === 'ar' ? 'وجبتك المجانية جاهزة للاستبدال' : 'Free Signature Burger Unlocked' }}
            </div>
        </div>
    </div>
</section>
