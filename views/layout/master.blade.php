<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Unifact Connector</title>

        <!-- Bootstrap core CSS -->
        <link href="{{ asset('vendor/unifact/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Bootstrap theme -->

        <!-- Custom styles for this template -->
        <link href="{{ asset('vendor/unifact/css/connector.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/unifact/lib/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"
              rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body role="document">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar"
                            aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ URL::route('connector.dashboard.index') }}">Unifact Connector</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    @section('navbar')
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="{{ URL::route('connector.dashboard.index') }}">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ URL::route('connector.logs.index') }}">Logs</a>
                            </li>
                            <form class="navbar-form navbar-right" action="{{ URL::route('connector.search') }}"
                                  method="GET">
                                <input type="text" class="form-control input-sm nav-reference" placeholder="Reference"
                                       name="reference" value="{{ \Request::get('reference') }}">
                                <input type="text" class="form-control input-sm nav-data" placeholder="Data" name="data"
                                       value="{{ \Request::get('data') }}">

                                <div class="input-group input-daterange">
                                    <input name="from" type="text" class="form-control nav-dt input-sm"
                                           value="{{ \Request::get('from') }}">
                                    <span class="input-group-addon">to</span>
                                    <input name="to" type="text" class="form-control nav-dt input-sm"
                                           value="{{ \Request::get('to') }}">
                                </div>
                                <input type="submit" value="Search" name="search" class="btn btn-success btn-sm">
                            </form>
                        </ul>
                        <ul class="nav navbar-nav pull-right">
                            <li><a href="{{ URL::route('connector.auth.logout') }}">Logout</a></li>
                        </ul>
                    @show
                </div>
                <!--/.nav-collapse -->

            </div>
        </nav>

        <div class="container theme-showcase" role="main">
            @yield('content')
        </div>
        <!-- /container -->


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="{{ asset('vendor/unifact/lib/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('vendor/unifact/lib/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('vendor/unifact/js/connector.js') }}"></script>
    </body>
</html>
