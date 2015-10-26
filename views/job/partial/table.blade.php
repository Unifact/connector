<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{{ $title }}</strong>
    </div>
    <div class="panel-body">
        @if(sizeof($jobs) > 0)
            <table class="table table-condensed">
                <thead>
                    <th class="smacol"></th>
                    <th class="smacol">ID</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th class="medcol">Status</th>
                    <th class="datecol">Created</th>
                    <th class="datecol">Handled</th>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr class="{{ $job->getTableRowClass() }}">
                            <td>
                                <span class="{{ JOB_ICON }}" aria-hidden="true"></span>
                            </td>
                            <td><a href="{{ URL::route('connector.jobs.show',[$job->id]) }}">{{ $job->id }}</a></td>
                            <td>{{ $job->type }}</td>
                            <td>{{ $job->reference }}</td>
                            <td>{{ $job->status }}</td>
                            <td>
                                <small>{{ $job->created_at->format(CONNECTOR_DATE_FORMAT) }}</small>
                            </td>
                            <td>
                                <small>{{ $job->updated_at->format(CONNECTOR_DATE_FORMAT) }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No jobs found.</p>
        @endif
    </div>
</div>
