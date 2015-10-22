@extends('connector::layout.master')

@section('content')

    @include('connector::job.partial.table', ['title' => 'Recent jobs', 'jobs' => $jobs->presenters()])
    @include('connector::job.partial.table', ['title' => 'Jobs with issues', 'jobs' => $issues->presenters()])
    @include('connector::log.partial.table', ['title' => 'Recent log entries', 'logs' => $logs->presenters()])
@endsection
