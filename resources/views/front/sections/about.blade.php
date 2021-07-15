<section id="section-about" class="page-section section bg-transparent m-0">
    <div class="container clearfix">
        @php
            $abt = \App\Models\Aboutus::first();
        @endphp
        <div class="heading-block bottommargin-lg center">
            <h2>About Us.</h2>
            <span>Everything you need to know about us.</span>
        </div>

        <div class="row justify-content-center col-mb-50 mb-0">
            <div class="col-sm-6 col-lg-4">
                <div class="heading-block fancy-title border-bottom-0 title-bottom-border">
                    <h4>{{$abt->title1}}.</h4>
                </div>

                <p>{{$abt->desc1}}</p>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="heading-block fancy-title border-bottom-0 title-bottom-border">
                    <h4>{{$abt->title2}}.</h4>
                </div>

                <p>{{$abt->desc2}}</p>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="heading-block fancy-title border-bottom-0 title-bottom-border">
                    <h4>{{$abt->title3}}.</h4>
                </div>

                <p>{{$abt->desc3}}</p>
            </div>
        </div>

    </div>
</section>
