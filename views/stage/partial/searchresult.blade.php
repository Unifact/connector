<tr>
    <td>
        <span class="{{ STAGE_ICON }}" aria-hidden="true"></span>
    </td>
    <td><a href="{{ URL::route('connector.stages.show',[$item->job_id,$item->id]) }}">view</a></td>
    <td>{{ $item->id }}</td>
    <td>{{ $item->stage }}</td>
    <td>-</td>
    <td>{{ $item->status }}</td>
    <td>
        <small>{{ $item->created_at->format(CONNECTOR_DATE_FORMAT) }}</small>
    </td>
</tr>
