@extends('front.layouts.app')

@section('content')

@include('front.layouts.slider')

<!-- Content
============================================= -->
		<section id="content">
			<div class="content-wrap py-0">

				@include('front.sections.about')

				@include('front.sections.work')

				@include('front.sections.services')

				@include('front.sections.video')

				{{-- @include('front.sections.testimonials') --}}

				@include('front.sections.contact')

			</div>
		</section><!-- #content end -->
@endsection
