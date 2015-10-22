@extends('connector::layout.restricted')

@section('content')
    <form class="form-signin" method="post" action="{{ URL::route('connector.auth.login.post') }}">

        @if($errors->hasBag('login'))
            <div class="alert alert-warning" role="alert">Invalid credentials, please try again.</div>
        @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" class="form-control" placeholder="Username" required autofocus name="username">
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" class="form-control" placeholder="Password" required name="password">
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
@endsection

