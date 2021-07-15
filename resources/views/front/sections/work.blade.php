<section id="section-work" class="page-section section m-0">

    <div class="heading-block center">
        <h2>Our Products</h2>
        <span>Some of the Awesome Projects we've worked on.</span>
    </div>

    <div class="container">

        <!-- Portfolio Items
        ============================================= -->
        <div id="portfolio" class="portfolio row grid-container no-gutters" data-layout="fitRows">

         @foreach (\App\Models\Gallery::all() as $g)
         <article class="portfolio-item col-lg-3 col-md-4 col-sm-6 col-12 pf-illustrations">
             <div class="grid-inner">
                 <div class="portfolio-image">
                     <a href="portfolio-single.html">
                         <img src="{{asset($g->image)}}" alt="Locked Steel Gate">
                     </a>
                     <div class="bg-overlay">
                         <div class="bg-overlay-content dark" data-hover-animate="fadeIn">
                             <a href="{{ $g->image }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInDownSmall" data-hover-animate-out="fadeOutUpSmall" data-hover-speed="350" data-lightbox="image" title="{{$g->caption}}"><i class="icon-line-plus"></i></a>

                         </div>
                         <div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
                     </div>
                 </div>
                 <div class="portfolio-desc">
                     <h3><a href="#">{{ $g->caption }}</a></h3>
                 </div>
             </div>
         </article>

         @endforeach

        </div><!-- #portfolio end -->

    </div>
</section>
