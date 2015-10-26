@extends('connector::layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            @if($log !== null)
                <strong>Log #{{ $log->id }}</strong>
            @endif
        </div>
        <div class="panel-body">
            @if($log !== null)
                <div class="pull-left">
                    <a href="{{ URL::route('connector.logs.show', [$log->id-1]) }}"
                       class="btn btn-primary">Previous log</a>

                    @if($log->job_id !== null)
                        <a href="{{ URL::route('connector.jobs.show', [$log->job_id]) }}"
                           class="btn btn-success">Job #{{ $log->job_id }}</a>
                    @endif
                </div>
                <a href="{{ URL::route('connector.logs.show', [$log->id+1]) }}"
                   class="btn btn-primary pull-right">Next log</a>

                <div class="clearfix"></div>
                <br>
                <pre>{{ $log->data }}</pre>
                <br>

                <div class="pull-left">
                    <a href="{{ URL::route('connector.logs.show', [$log->id-1]) }}"
                       class="btn btn-primary">Previous log</a>

                    @if($log->job_id !== null)
                        <a href="{{ URL::route('connector.jobs.show', [$log->job_id]) }}"
                           class="btn btn-success">Job #{{ $log->job_id }}</a>
                    @endif
                </div>
                <a href="{{ URL::route('connector.logs.show', [$log->id+1]) }}"
                   class="btn btn-primary pull-right">Next log</a>
            @else
                No logs found, click <a href="{{ URL::route('connector.logs.show',[$last]) }}">here</a> to go to the
                last log record.
            @endif
        </div>
    </div>
@endsection
