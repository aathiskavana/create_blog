@extends('layouts.base')
@section('content')
    <section style="min-height:100vh; background-image:url({{ asset('/assets/images/background.png') }});background-position: center;background-size: cover; padding-bottom:100px" id="home">
        <div class="container-fluid">
            <img class="bulirkiriheader" src="{{ asset('/assets/images/bulir-kiri-header.png') }}">
            <img class="bulirkananheader" src="{{ asset('/assets/images/bulir-kanan-header.png') }}">
            
            <div class="container gallery-card">
                <ul class="nav nav-tabs-outline gallery-mb">
                    @foreach ($years as $year)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(2) == $year) ? 'active' : '' }}" data-toggle="tab" href="#k20" id="2020-btn">{{ $year }}</a>
                        </li>
                    @endforeach
                </ul>                
                <div class="tab-content " data-aos="fade-in">
                    <div class="tab-pane fade show active" id="k20">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-8">
                                            @yield('blog')
                                        </div>
                                        <div class="col-md-4">
                                            <div class="accordion" id="accordion-1">
                                                @foreach ($months as $month)
                                                    <div class="card m-0">
                                                        <h5 class="card-title">
                                                        <a class="collapsed blog-accordion" data-toggle="collapse" href="#collapse-1-{{ $loop->index }}"> {{$month['month']}} </a>
                                                        </h5>
                                    
                                                        <div id="collapse-1-{{ $loop->index }}" class="collapse {{ intval(request()->segment(3)) == $month['number'] ? 'show' : ''}}" data-parent="#accordion-1">
                                                            @if ($month['blog']->count() != 0)
                                                                <div class="card-body">
                                                                    @foreach ($month['blog'] as $blog)
                                                                    <a href="/blog/{{ $blog->created_at->year }}/{{ $blog->created_at->month }}/{{ $blog->slug_title }}"><p class="pblog-mini-title">{{ $blog->title }}</p></a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="card-body">
                                                                    -
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
    
      