@extends('connector::layout.master')

@section('content')

    <div class="panel panel-default panel-job">
        <div class="panel-heading"><strong>Stage details</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">ID: <span> {{$stage->id}} </span></div>
                <div class="col-md-2">Job:
                    <span> {!!  $stage->url('connector.jobs.show','job_id',[$stage->job_id]) !!} </span></div>
                <div class="col-md-5">Stage: <span> {{ $stage->stage }} </span></div>
                <div class="col-md-3">
                    <small>Processed: {{ $stage->created_at->format(CONNECTOR_DATE_FORMAT) }} <br>
                           Status: {{ $stage->status }}</small>
                </div>
            </div>
            <br>
            <label>Input:</label>
            <pre>{{ $previous->getPrettyData() }}</pre>
            <br>
            <label>Ouput:</label>
            <pre>{{ $stage->getPrettyData() }}</pre>
        </div>
    </div>

    @include('connector::log.partial.table', ['title' => 'Associated logs', 'logs' => $logs->presenters()])


    {{--    @include('connector::stage.partial.table', ['title' => 'Stages', 'stages' => $job->stages->presenters()])
        @include('connector::job.partial.table', ['title' => 'Other jobs with '.$job->reference.' reference', 'jobs' => $referenced->presenters()])
        --}}
@endsection

