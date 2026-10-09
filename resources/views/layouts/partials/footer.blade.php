  <footer class="footer bg-surface border-top mt-4">
    <div class="container py-5">
      <div class="row g-4">
        <div class="col-lg-5">
          <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-2 fs-4 fw-800 text-body">
            <span class="logo-mark"><svg class="ico"><use href="#i-bolt"/></svg></span>
            Volt<span class="text-primary">ix</span>
          </a>
          <p class="text-secondary small mt-3" style="max-width:300px">Your online electronics store. Quality-checked gear, fast delivery and support you can trust.</p>
          <div class="d-flex gap-2">
            <a href="#" class="social" title="Facebook"><svg class="ico"><use href="#i-facebook"/></svg></a>
            <a href="#" class="social" title="Instagram"><svg class="ico"><use href="#i-instagram"/></svg></a>
            <a href="#" class="social" title="YouTube"><svg class="ico"><use href="#i-youtube"/></svg></a>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
          <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"><svg class="ico"><use href="#i-grid"/></svg> Categories</h6>
          <ul class="list-unstyled small d-grid gap-2">
            @foreach ($categories->take(4) as $category)
              <li><a href="{{ route('categories.show', $category->slug) }}" data-ajax-link data-update-target="#page-content">{{ $category->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
          <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"><svg class="ico"><use href="#i-bag"/></svg> Shopping</h6>
          <ul class="list-unstyled small d-grid gap-2">
            <li><a href="#">Payment methods</a></li>
            <li><a href="#">Shipping</a></li>
            <li><a href="#">Warranty claims</a></li>
            <li><a href="#">Easy returns</a></li>
          </ul>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"><svg class="ico"><use href="#i-building"/></svg> Company</h6>
          <ul class="list-unstyled small d-grid gap-2">
            <li><a href="#">About us</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Careers</a></li>
            <li><a href="#">Terms of use</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="border-top">
      <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 small text-secondary">
        <span>© 2026 Voltix. All rights reserved.</span>
        <div class="d-flex flex-wrap gap-2">
          <span class="pay"><svg class="ico"><use href="#i-lock"/></svg> Secure checkout</span>
          <span class="pay"><svg class="ico"><use href="#i-card"/></svg> Visa</span>
          <span class="pay"><svg class="ico"><use href="#i-card"/></svg> Mastercard</span>
        </div>
      </div>
    </div>
  </footer>
