<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{{ $title }}</strong>
    </div>
    <div class="panel-body">
        @if(sizeof($logs) > 0)
            <table class="table table-condensed">
                <thead>
                    <th class="smacol"></th>
                    <th>Message</th>
                    <th>Job</th>
                    <th>Stage</th>
                    <th>Level</th>
                    <th class="datecol">Date</th>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="{{ $log->getTableRowClass() }}">
                            <td>
                                <span class="{{ LOG_ICON }}" aria-hidden="true"></span>
                            </td>
                            <td>{{ $log->message }}</td>
                            <td>{!! $log->url('connector.jobs.show','job_id',[$log->job_id]) !!}</td>
                            <td>{{ $log->stage }}</td>
                            <td>{{ $log->humanLevel() }}</td>
                            <td>
                                <small>{{ $log->created_at->format(CONNECTOR_DATE_FORMAT) }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No logs found.</p>
        @endif
    </div>
</div>
