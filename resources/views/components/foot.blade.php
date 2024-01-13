<footer class="footer-distributed">

    <div class="footer-left">

        <div class="logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </a>
        </div>

      <p class="footer-links">

        <a href="/about" class="link-1">عن سياسة عجمان</a>
        <a href="/faq" class="link-1">الأسئلة الشائعة</a>
        {{-- @if(!auth()->user()->uaepass_id)
            <a href="https://stg-id.uaepass.ae/idshub/authorize?redirect_uri=http://localhost:8000/uaepass/callback&client_id=ajm_policy_web_stg&response_type=code&state=ajman2023&scope=urn:uae:digitalid:profile:general urn:uae:digitalid:profile:general:profileType urn:uae:digitalid:profile:general:unifiedId&acr_values=urn:safelayer:tws:policies:authentication:level:low">ربط الحساب بال UAE Pass</a>
        @endif --}}
      </p>

    </div>

    <div class="footer-center">
        <h3>للتواصل :</h3>
      <div>
        <i class="fas fa-map-marker"></i>
        <p>
            إمارة عجمان - الإمارات العربية المتحدة
        </p>
      </div>

      <div>
        <i class="fas fa-phone"></i>
        <p>
            <a href="tel:+9716701666">+9716701666</a>
        </p>
      </div>

      <div>
        <i class="fas fa-envelope" style="font-size: 22px;"></i>
        <p><a href="mailto:support@company.com">policy@ajman.ae</a></p>
      </div>

    </div>

    {{-- <div class="footer-right">

      <p class="footer-company-about">
        <span>About the company</span>
        Lorem ipsum dolor sit amet, consectateur adispicing elit. Fusce euismod convallis velit, eu auctor lacus vehicula sit amet.
      </p>

      <div class="footer-icons">

        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>
        <a href="#"><i class="fa fa-github"></i></a>

      </div>

    </div> --}}

  </footer>

