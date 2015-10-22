<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{{ $title }}</strong>
    </div>
    <div class="panel-body">
        @if(sizeof($stages) > 0)
            <table class="table table-condensed">
                <thead>
                    <th class="smacol"></th>
                    <th class="smacol"></th>
                    <th class="smacol">ID</th>
                    <th>Stage</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th class="datecol">Processed</th>
                </thead>
                <tbody>
                    @foreach($stages as $stage)
                        <tr class="{{ $stage->getTableRowClass() }}">
                            <td>
                                <span class="{{ STAGE_ICON }}" aria-hidden="true"></span>
                            </td>
                            <td>
                                <a href="{{ URL::route('connector.stages.show',[$stage->job_id, $stage->id]) }}">view</a>
                            </td>
                            <td>{{ $stage->id }}</td>
                            <td>{{ $stage->stage }}</td>
                            <td>{{ $stage->status }}</td>
                            <td>{{ substr($stage->data,0,32) }}</td>
                            <td>
                                <small>{{ $stage->updated_at->format(CONNECTOR_DATE_FORMAT) }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No stages found.</p>
        @endif
    </div>
</div>
