<div class="card footer">
  <div class="support-text">
    {{ __('emails.questions') }}
    <a href="{{ $footerData['support_url'] }}" class="support-link"> {{ $footerData['support_url']}}</a>
    {{ __('emails.or_contact') }}
    <a href="mailto:{{ $footerData['support_email'] }}" class="support-link">{{ $footerData['support_email'] }}</a>
  </div>

  <div class="social-links">
    @if($footerData['facebook_url'])
    <a href="{{ $footerData['facebook_url'] }}" class="social-link">
      <img src="{{ asset('images/gif/facebook.gif') }}" alt="Facebook" width="24" height="24" />
    </a>
    @endif
    @if($footerData['instagram_url'])
    <a href="{{ $footerData['instagram_url'] }}" class="social-link">
      <img src="{{ asset('images/gif/instagram.gif') }}" alt="Instagram" width="24" height="24" />
    </a>
    @endif
    @if($footerData['whatsapp_number'])
    <a href="https://wa.me/{{ $footerData['whatsapp_number'] }}" class="social-link">
      <img src="{{ asset('images/gif/whatapp.gif') }}" alt="WhatsApp" width="24" height="24" />
    </a>
    @endif
  </div>


  <div class="copyright">
    {{  __('emails.default_footer') }} {{ $footerData['footer_text'] ??  '' }}
  </div>
</div>

<style>
  .footer {
    margin-top: 20px;
    padding: 20px;
    /* background-color: #f9f9f9; */
    /* border-top: 1px solid #ddd; */
    text-align: center;
  }

  .support-text {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
  }

  .support-link {
    color: #007bff;
    text-decoration: none;
  }

  .social-links {
    margin-bottom: 10px;
  }

  .social-link {
    margin: 0 5px;
  }



  .footer-link {
    color: #007bff;
    text-decoration: none;
    margin: 0 10px;
  }

  .copyright {
    font-size: 12px;
    color: #777;
  }
</style>