@extends('connector::layout.master')

@section('content')

    @if($job->status === 'restart' || $job->status === 'retry')
        <div class="alert alert-warning" role="alert"><strong>Status:</strong> This Job is scheduled for {{ $job->status }}.</div>
    @elseif($job->status === 'error')
        <div class="alert alert-danger" role="alert"><strong>Warning:</strong> This Job could not be handled successfully!</div>
    @elseif($job->status === 'handled')
        <div class="alert alert-success" role="alert"><strong>Success:</strong> This Job has been successfully handled!</div>
    @elseif($job->status === 'new')
        <div class="alert alert-info" role="alert"><strong>Info:</strong> This is a new Job scheduled to be handled next `connector:run`.</div>
    @endif

    <div class="panel panel-default panel-job">
        <div class="panel-heading"><strong>Job details</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">ID: <span> {{$job->id}} </span></div>
                <div class="col-md-2">Type: <span> {{ $job->type }} </span></div>
                <div class="col-md-6">Reference: <span> {{$job->reference}} </span></div>
                <div class="col-md-2">
                    <small>Created: {{ $job->created_at->format(CONNECTOR_DATE_FORMAT) }} <br>
                           Status: {{ $job->status }}</small>
                </div>
            </div>


            <form action="{{ URL::route('connector.jobs.update',[$job->id]) }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="PUT">

                <label for="dedit">Data:</label>
                <pre class="dshow">{{ $job->getPrettyData() }}</pre>
                <textarea class="data-editor dedit" name="data" id="" style="display: none;">{{ $job->data }}</textarea>

                <div class="pull-left">
                    <a href="#edit" class="btn btn-primary" onclick="$('.dedit').toggle();$('.dshow').toggle();">Toggle
                                                                                                                 edit
                                                                                                                 data</a>
                </div>
                <div class="pull-right">
                    <input type="submit" name="save_restart" value="Save and restart" class="btn btn-warning dedit" style="display: none;">
                    <input type="submit" name="restart" value="Restart" class="btn btn-warning">
                    <input type="submit" name="retry" value="Retry failed stage" class="btn btn-warning">
                </div>
            </form>

        </div>
    </div>
    @include('connector::stage.partial.table', ['title' => 'Stages', 'stages' => $job->stages->presenters()])
    @include('connector::job.partial.table', ['title' => '10 most recent jobs with "'.$job->reference.'" reference', 'jobs' => $referenced->presenters()])
    @include('connector::log.partial.table', ['title' => 'Associated logs', 'logs' => $logs->presenters()])
@endsection
