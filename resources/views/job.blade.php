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
        <div class="wrapper" id="app">
            @component('components/navigation')
            @endcomponent
            <div class="content content--property">
                <div class="main">
                    @foreach($job as $j)
                        <div class="main__section section">
                            <h2 class="section__title">{{ $j->job_title }}</h2>
                        </div>
                        <div class="section__body">
                            {!! $j->job_post !!}
                        </div>
                        <div class="section__footer">
                            {{$j->company}}
                        </div>
                    @endforeach
                </div>
            </div>
            @component('components/footer')
            @endcomponent
        </div>

        <script src="{{ mix('js/bootstrap.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
