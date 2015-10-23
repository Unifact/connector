@extends('connector::layout.master')

@section('content')
	{!! $logs->render() !!}
	@include('connector::log/partial/table', ['logs' => $logs->presenters()])
@endsection
