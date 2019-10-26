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
        <div id="app" class="wrapper">
            @component('components/navigation')
            @endcomponent
            <div class="content">
                @foreach ($companies as $company)
                    <formcompany :options = "{{json_encode($options)}}" :company_data="{{$company}}" :company_benefits="{{$company->scraped_benefits}}"></formcompany>
                @endforeach
                <div class="pagination-container">
                    {{ $companies->onEachSide(2)->links() }}
                </div>
            </div>
        </div>

        <script src="{{ mix('js/bootstrap.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
