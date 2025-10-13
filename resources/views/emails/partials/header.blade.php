<div class="logo-container">
   
    <div class="logo">
        @if(isset($isPdf) && $isPdf)
            <img src="{{  $invoice->company['logo'] ? public_path($invoice->company['logo']) : $invoice->company['name'] ?? '' }}" alt="{{ $invoice->company['name'] ?? '' }}">
        @else
            <img src="{{ $invoice->company['logo'] ? asset($invoice->company['logo']) : '' }}" alt="{{ $invoice->company['name'] ?? '' }}">
        @endif
    </div>
    <div class="slogan-wrapper">
    <div class="slogan" style="
        font-size: {{ app()->getLocale() === 'ar' ? '16px' : '12px' }}; 
        color: #666;
        margin-top: 5px;
        text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
    ">
            {{ $invoice->company['slogan'] ? $invoice->company['slogan'] : '' }}
        </div>
    </div>
</div>

<style>
     @font-face {
            font-family: 'Almarai';
            src: url('{{ public_path(' fonts/Almarai-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Almarai';
            src: url('{{ public_path(' fonts/Almarai-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

       .logo-container {
        display: flex;
        flex-direction: column;
        align-items: {{ in_array(app()->getLocale(), ['ar']) ? 'flex-start' : 'flex-start' }};
        direction: {{ in_array(app()->getLocale(), ['ar']) ? 'ltr' : 'ltr' }};
    }

    .logo {
        display: flex;
        align-items: start;
        margin-bottom: 1px;
       
    }

    .logo img {
        width: 200px;
        height: 70px;
        object-fit: cover;
    }
    .slogan-wrapper {
        position: relative;
        padding: 1px 0;
        width: 100%;
        max-width: 200px;
    }

    .company-slogan {
        font-size: 12px;
        color: #444;
        font-family: {{ in_array(app()->getLocale(), ['ar']) ? "'Almarai'" : "'Jersey 15'" }}, sans-serif;
        letter-spacing: {{ in_array(app()->getLocale(), ['ar']) ? '0' : '1.5px' }};
        text-transform: {{ in_array(app()->getLocale(), ['ar']) ? 'none' : 'uppercase' }};
        position: relative;
        display: inline-block;
        padding-bottom: 4px;
        text-align: {{ in_array(app()->getLocale(), ['ar']) ? 'right' : 'left' }};
        font-style: {{ in_array(app()->getLocale(), ['ar']) ? 'normal' : 'italic' }};
    }

    .company-slogan::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(to {{ in_array(app()->getLocale(), ['ar']) ? 'left' : 'right' }}, #ff0000 0%, rgba(255, 0, 0, 0.7) 50%, rgba(255, 0, 0, 0) 100%);
    }

    [dir="rtl"] .company-slogan {
        font-family: 'Almarai', sans-serif;
        letter-spacing: 0;
        font-style: normal;
        text-transform: none;
    }

    @media print {
        .company-slogan {
            color: #333;
        }

        .company-slogan::after {
            background: linear-gradient(to {{ in_array(app()->getLocale(), ['ar']) ? 'left' : 'right' }}, rgba(200, 0, 0, 0.8) 0%, rgba(200, 0, 0, 0.3) 50%, rgba(200, 0, 0, 0) 100%);
        }
    }
</style>