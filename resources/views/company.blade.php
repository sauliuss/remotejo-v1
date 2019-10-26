<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

         <link href=" {{ mix('css/style.css') }}" rel="stylesheet">

        <!-- Fonts -->

        <!-- Styles -->
        <style>

        </style>
    </head>
    <body>
        <div class="wrapper">
            @component('components/navigation')
            @endcomponent
            <div class="hero">
                <div class="hero__container">
                    <img class="avatar__img is-company is-large" src="{{asset($company->logo)}}" alt="">
                    <div class="overview">
                        <div class="company-overview__title is-large is-verified">
                          {{ $company->name }}   
                        </div>
                        <div class="overview__links">
                            <div class="overview__links__item">
                                <a target="_blank" href="{{$company->url}}">
                                    <span>
                                        <?php echo parse_url($company->url, PHP_URL_HOST) ?>  
                                    </span>
                                </a>
                            </div>
                            <div class="overview__links__item">
                                <a class="" target="_blank" href="{{$company->twitter}}">
                                    <svg class="icon icon-twitter" aria-hidden="true">
                                        <use xlink:href="{{ asset('img/svg_symbols.svg#icon-twitter')}}"></use>
                                    </svg>
                                </a>
                            </div>
                            <div class="overview__links__item">
                                <a class="" target="_blank" href="{{$company->facebook}}">
                                    <svg class="icon icon-twitter" aria-hidden="true">
                                        <use xlink:href="{{ asset('img/svg_symbols.svg#icon-facebook')}}"></use>
                                    </svg>
                                </a>
                            </div>
                            <div class="overview__links__item">
                                <a class="" target="_blank" href="{{$company->github}}">
                                    <svg class="icon icon-twitter" aria-hidden="true">
                                        <use xlink:href="{{ asset('img/svg_symbols.svg#icon-facebook')}}"></use>
                                    </svg>
                                </a>
                            </div>       
                        </div>
                    </div>
                </div>
            </div>
            <div class="content content--company">
                <div class="sidebar"></div>
                <div class="main">
                    <div class="company-overview__description">
                            {{ $company->description_short }}      
                    </div>
                    <div class="main__section">
                        @foreach($company->benefits as $benefit)
                            <span>{{ $benefit->name }}</span>  
                        @endforeach
                    </div>
                    <div class="main__section">
                        <div class="company-tools">
                            @foreach($company->tools as $tool)
                                <div class="company-tools__item">
                                    <a href="/tool/{{$tool->slug}}" class="tool">
                                        @if(isset($tool->logo))
                                            <img class="tool__img" src="{{ asset($tool->logo)}}" alt="">
                                        @else
                                            <img class="tool__img" src="{{ asset('/logos/default.png')}}" alt="">
                                        @endif
                                        <span class="tool__name">{{ $tool->name }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
{{-- 
                    <div class="overview__links__item">
                        <svg class="icon-tag" id="icon-tag" aria-hidden="true">
                            <use xlink:href="/assets/img/sprite.svg#icon-tag"></use>
                        </svg>
                        <span>{{ $company->type }} </span>   
                    </div>
                    <div class="overview__links__item">
                        <svg class="icon-tag" id="icon-tag" aria-hidden="true">
                            <use xlink:href="/assets/img/sprite.svg#icon-tag"></use>
                        </svg>
                        @foreach($company->industries as $industry)
                            <span>{{ $industry->name }}</span>  
                        @endforeach
                    </div>
                    <div class="overview__links__item">
                        <svg class="icon-users" id="icon-users" aria-hidden="true">
                            <use xlink:href="/assets/img/sprite.svg#icon-users"></use>
                        </svg>
                        <span>100-200 Employees</span>
                    </div>
                    <div class="overview__links__item">
                        <svg title="Headquaters" class="icon-location" id="icon-location" aria-hidden="true">
                            <use xlink:href="/assets/img/sprite.svg#icon-location"></use>
                        </svg>
                        <span>Berlin, Germany</span>
                    </div>  --}}
                </div>
            </div>
        </div>

        <script src="{{ mix('js/bootstrap.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
