@php
$almaraiRegular = public_path('fonts/Almarai-Regular.ttf');
$almaraiBold = public_path('fonts/Almarai-Bold.ttf');
$logoUrl = $invoice->company['logoUrl'] ?? asset('images/default-logo.png');
@endphp

<div class="logo-container">
    <div class="logo">
        <img 
            src="{{ isset($isPdf) && $isPdf ? $logoUrl : asset($logoUrl) }}" 
            alt="{{ $invoice->company['name'] ?? '' }}"
            style="
                @if($invoice->company['logo_width'] ?? false)
                    width: {{ $invoice->company['logo_width'] }}px;
                @endif
                @if($invoice->company['logo_height'] ?? false)
                    height: {{ $invoice->company['logo_height'] }}px;
                @endif
            "
        >
    </div>

    <div class="slogan-wrapper">
        <div class="slogan" style="
            font-size: {{ app()->getLocale() === 'ar' ? '16px' : '12px' }};
            color: #666;
            margin-top: 5px;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        ">
            {{ $invoice->company['slogan'] ?? '' }}
        </div>
    </div>
</div>

<style>
@font-face {
    font-family: 'Almarai Regular';
    src: url('{{ $almaraiRegular }}') format('truetype');
}
@font-face {
    font-family: 'Almarai Bold';
    src: url('{{ $almaraiBold }}') format('truetype');
    font-weight: bold;
}

.logo-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.logo {
    width: 100%;
    max-width: 200px;
    min-height: 50px;
    height: 70px;
    max-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.slogan-wrapper {
    padding: 1px 0;
    width: 100%;
    max-width: 200px;
}
</style>
