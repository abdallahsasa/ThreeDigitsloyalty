@props([
    'compact' => false,
])

<div class="td-wallet-badges" dir="ltr" aria-label="Apple Wallet & Google Wallet" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: {{ $compact ? '0.35rem' : '0.45rem' }}; margin-top: {{ $compact ? '0.55rem' : '0.7rem' }};">
    {{-- Apple Wallet Badge --}}
    <span data-wallet-badge="apple" style="display: inline-flex; align-items: center; gap: 0.35rem; background: #141210; color: #FFFFFF; padding: {{ $compact ? '0.26rem 0.52rem' : '0.32rem 0.65rem' }}; border-radius: 8px; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif; font-size: {{ $compact ? '0.62rem' : '0.68rem' }}; font-weight: 700; letter-spacing: 0.01em; line-height: 1; white-space: nowrap; box-shadow: 0 2px 6px rgba(20, 18, 16, 0.14);">
        {{-- Apple Wallet Pass Icon + Apple Mark --}}
        <svg width="{{ $compact ? '13' : '15' }}" height="{{ $compact ? '13' : '15' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="display: block; flex-shrink: 0;">
            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.33c.64-.78 1.08-1.86.96-2.94-.93.04-2.06.62-2.72 1.4-.58.68-1.1 1.79-.96 2.84 1.04.08 2.09-.52 2.72-1.3z"/>
        </svg>
        <span>Apple Wallet</span>
    </span>

    {{-- Google Wallet Badge --}}
    <span data-wallet-badge="google" style="display: inline-flex; align-items: center; gap: 0.36rem; background: #141210; color: #FFFFFF; padding: {{ $compact ? '0.26rem 0.52rem' : '0.32rem 0.65rem' }}; border-radius: 8px; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif; font-size: {{ $compact ? '0.62rem' : '0.68rem' }}; font-weight: 700; letter-spacing: 0.01em; line-height: 1; white-space: nowrap; box-shadow: 0 2px 6px rgba(20, 18, 16, 0.14);">
        {{-- Authentic 4-Color Google Wallet Card Emblem --}}
        <svg width="{{ $compact ? '14' : '16' }}" height="{{ $compact ? '14' : '16' }}" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display: block; flex-shrink: 0;">
            <rect x="2" y="4" width="20" height="16" rx="4" fill="#4285F4"/>
            <path d="M2 8.5C2 6.843 3.343 5.5 5 5.5H19C20.657 5.5 22 6.843 22 8.5V12H2V8.5Z" fill="#34A853"/>
            <path d="M2 11.2C2 9.875 3.075 8.8 4.4 8.8H19.6C20.925 8.8 22 9.875 22 11.2V15.5H2V11.2Z" fill="#FBBC04"/>
            <path d="M2 14.2L10.8 11.4C11.58 11.15 12.42 11.15 13.2 11.4L22 14.2V16C22 18.209 20.209 20 18 20H6C3.791 20 2 18.209 2 16V14.2Z" fill="#EA4335"/>
        </svg>
        <span>Google Wallet</span>
    </span>
</div>
