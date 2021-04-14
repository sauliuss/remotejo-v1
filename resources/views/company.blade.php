<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

         <link href=" {{ mix('css/style.css') }}" rel="stylesheet">
         <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
         <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
         <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
         <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
         <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
         <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
         <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
         <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
         <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
         <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
         <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
         <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
         <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
         <link rel="manifest" href="/manifest.json">
         <meta name="msapplication-TileColor" content="#ffffff">
         <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
         <meta name="theme-color" content="#ffffff">

        <!-- Fonts -->

        <!-- Styles -->
        <style>

        </style>
    </head>
    <body>
        @foreach($data as $company)
        <div class="wrapper" id="app">
            @component('components/navigation')
            @endcomponent
            <div class="hero">
                <div class="hero__container">
                    <div class="avatar">
                        @if(isset($company->logo))
                            <img class="avatar__img avatar__img--hero is-company is-large" src="{{asset($company->logo)}}" alt="{{ $company->name }} logo">
                        @else
                           <img class="avatar__img avatar__img--hero is-company is-large" src="{{asset('/img/default.svg')}}" alt="No image icon"> 
                        @endif
                    </div>
                    <div class="overview">
                        <div class="overview__header">
                            <div class="company-title company-title--large">
                              {{ $company->name }}

                              @if($company->is_verified)
                                <span class="badge badge--verified">
                                    <svg class="icon icon-verified" aria-hidden="true">
                                        <use xlink:href="{{ asset('img/svg_symbols.svg#icon-verified')}}"></use>
                                    </svg>                
                                </span>
                              @endif
                            </div>
                            @if(!$company->is_verified)
                                <modalclaim :company_logo="`{!!(isset($company->logo) && !empty($company->logo)) ? asset($company->logo) : '' !!}`" :company_url="`{!! $meta_data['company_url_host'] !!}`" :company_name="`{!!  $company->name !!}`"></modalclaim>
                            @endif

                        </div>
                        <div class="overview__links">
                            <div class="overview__links__item">
                                <a target="_blank" href="{{$company->url}}">
                                    <span>
                                        <?php echo preg_replace('#^www\.(.+\.)#i', '$1',parse_url($company->url, PHP_URL_HOST)) ?>  
                                    </span>
                                </a>
                            </div>

                            @if(isset($company->twitter) && !empty($company->twitter))
                                <div class="overview__links__item">
                                    <a class="" target="_blank" href="{{$company->twitter}}">
                                        <svg class="icon icon-social" aria-hidden="true">
                                            <use xlink:href="{{ asset('img/svg_symbols.svg#icon-twitter')}}"></use>
                                        </svg>
                                    </a>
                                </div>
                            @endif

                            @if(isset($company->facebook) && !empty($company->facebook))
                            <div class="overview__links__item">
                                <a class="" target="_blank" href="{{$company->facebook}}">
                                    <svg class="icon icon-social" aria-hidden="true">
                                        <use xlink:href="{{ asset('img/svg_symbols.svg#icon-facebook')}}"></use>
                                    </svg>
                                </a>
                            </div>
                            @endif
                            
                            @if(isset($company->github) && !empty($company->github))
                            <div class="overview__links__item">
                                <a class="" target="_blank" href="{{$company->github}}">
                                    <svg class="icon icon-social" aria-hidden="true">
                                        <use xlink:href="{{ asset('img/svg_symbols.svg#icon-github')}}"></use>
                                    </svg>
                                </a>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="content content--company">
                <div class="sidebar sidebar--left">
                    <div class="list">
                        @if(isset($company->remote_level))
                        <div class="list__item">
                            @if($company->remote_level == 0)
                            <div class="title">
                                <svg class="icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-remote-friendly')}}"></use>
                                </svg>
                                <span>
                                    {{ $meta_data['remote_level'] }}
                                </span>
                            </div>
                            @elseif($company->remote_level == 1)
                            <div class="title">
                                <svg class="icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-remote-first')}}"></use>
                                </svg>
                                <span>
                                    {{ $meta_data['remote_level'] }}
                                </span>
                            </div>
                            @elseif($company->remote_level == 2)
                            <div class="title">
                                <svg class="icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-fully-remote')}}"></use>
                                </svg>
                                <span>
                                    {{ $meta_data['remote_level'] }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if(isset($company->headquaters) && !empty($company->headquaters))
                        <div class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-location')}}"></use>
                                </svg>
                                <span>
                                    {{ $company->headquaters }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if(isset($meta_data['company_type']) && !empty($meta_data['company_type']))
                        <div class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-type')}}"></use>
                                </svg>
                                <span>
                                    {{ $meta_data['company_type'] }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if(isset($meta_data['company_size']) && !empty($meta_data['company_size']))
                        <div class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-people')}}"></use>
                                </svg>
                                <span>
                                    {{ $meta_data['company_size'] }}
                                </span>
                            </div>
                        </div>
                        @endif

{{--                         @if(sizeof($company->industries) > 0)
                        <div class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="{{ asset('img/svg_symbols.svg#icon-tag')}}"></use>
                                </svg>
                                <span>
                                    @foreach($company->industries as $industry)
                                        <a href="/industries/{{$industry->slug}}">{{ $industry->name }}</a>
                                        @if(!$loop->last)
                                            <span class="divider">&nbsp;&#183;&nbsp;</span>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        @endif --}}
                        <div class="list__item">
                            <span class="title title--disclaimer">
                                This profile was updated last time by {{ $company->is_verified ? $company->name : 'community' }} {{$company->updated_at->diffForHumans()}}
                            </span>
                         </div>
                     </div>
                </div>
                <div class="main">
                    <div class="main__section section">
                        <p class="company-description">{{ $company->description_short }}</p>
                    </div>
                    <div class="main__section section">
                        <h2 class="section__title">Hires in</h2>
                        <h3 class="section__subtitle">Timezones</h3>
                        <div class="editor-timezones__body editor-timezones__body--display">
                            @foreach($meta_data['timezones'] as $timezone)
                                <div class="editor-timezones__item">
                                    <div class="timezone">
                                        <span class="timezone__bar {{in_array($timezone->id, $meta_data['company_timezones']) ? 'timezone__bar--active' : null  }}  timezone__bar--display"></span>
                                        <div class="timezone__label {{in_array($timezone->id, $meta_data['company_timezones']) ? 'timezone__label--active' : null  }}">
                                            {{ $timezone->name }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach 
                        </div>
                        <h3 class="section__subtitle">Continents and countries</h3>
                        <div class="grid">                    
                            @foreach($company->hiring_regions as $region)
                                @if($region->type !== 'timezone')
                                    <div class="grid__item grid__item--regions">
                                        <a href="/region/{{ $region->slug }}" class="thumbnail thumbnail--region">
                                            <span>{{ $region->emoji }}</span>
                                            <span>{{ $region->name }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="main__section section">
                        <h2 class="section__title">Tech and Tools Stack</h2>
                            @foreach($meta_data['company_tools'] as $tools_group)
                                 <h3 class="section__subtitle">{{ $tools_group[0]['type']['name'] }}</h3>
                                <div class="grid grid--tools">
                                    @foreach($tools_group as $tool)
                                        <div class="grid__item grid__item--tools">
                                            <a href="/stack/{{$tool->slug}}" class="thumbnail thumbnail--tool">
                                                @if(isset($tool->logo))
                                                    <img class="thumbnail--tool__img" src="{{ asset($tool->logo)}}" alt="{{ $tool->name }} logo">
                                                @else
                                                    <img class="thumbnail--tool__img" src="{{ asset('/img/default.svg')}}" alt="No image icon">
                                                @endif
                                                <span class="thumbnail--tool__name">{{ $tool->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                    </div>
                    <div class="main__section section">
                        <h2 class="section__title">Offered Benefits</h2>
                        <div class="grid">
                            @foreach($company->benefits as $benefit)
                                    <div class="grid__item grid__item--benefits">
                                        <a href="/benefits/{{ $benefit->slug }}" class="thumbnail thumbnail--benefit">
                                                <div class="icon-container">
                                                    <svg class="icon icon-benefit" aria-hidden="true">
                                                        @if($benefit->parent_id == 1)
                                                            <use xlink:href="{{ asset('img/svg_symbols.svg#icon-benefit-health')}}"></use>
                                                        @elseif($benefit->parent_id == 7)
                                                            <use xlink:href="{{ asset('img/svg_symbols.svg#icon-benefit-compensation')}}"></use>
                                                        @elseif($benefit->parent_id == 12)
                                                            <use xlink:href="{{ asset('img/svg_symbols.svg#icon-benefit-timeoff')}}"></use>
                                                        @elseif($benefit->parent_id == 19)
                                                            <use xlink:href="{{ asset('img/svg_symbols.svg#icon-benefit-other')}}"></use>
                                                        @endif
                                                    </svg>
                                                </div>
                                            <span class="name">
                                                {{ $benefit->name }}
                                            </span>
                                        </a>                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="sidebar sidebar--right">
                    <formnotification :subscribers_count="12" :categories="{{$meta_data['job_categories']}}" :selected="{{$meta_data['notification']}}" :company_name="`{!!  $company->name !!}`"></formnotification>
                </div>
            </div>
        </div>

        <script src="{{ mix('js/bootstrap.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        @endforeach
    </body>
</html>
