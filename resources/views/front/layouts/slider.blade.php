<section id="slider" class="slider-element min-vh-60 min-vh-md-100 with-header swiper_wrapper page-section">
    <div class="slider-inner">

        <div class="swiper-container swiper-parent">
            <div class="swiper-wrapper">
                @foreach (\App\Models\Slider::all() as $slid)
                <div class="swiper-slide dark">
                    <div class="container">
                        <div class="slider-caption slider-caption-center">
                            <h2 data-animate="fadeInUp">{{ $slid->heading }}</h2>
                            <p class="d-none d-sm-block" data-animate="fadeInUp" data-delay="200">{{ $slid->title }}</p>
                        </div>
                    </div>
                    <div class="swiper-slide-bg" style="background-image: url('{{asset($slid->image)}}');"></div>
                </div>
                @endforeach
            </div>
            <div class="slider-arrow-left"><i class="icon-angle-left"></i></div>
            <div class="slider-arrow-right"><i class="icon-angle-right"></i></div>
            <div class="slide-number"><div class="slide-number-current"></div><span>/</span><div class="slide-number-total"></div></div>
            <a href="#" data-scrollto="#section-about" class="one-page-arrow dark">
                <i class="icon-angle-down infinite animated fadeInDown"></i>
            </a>
        </div>

    </div>
</section>
