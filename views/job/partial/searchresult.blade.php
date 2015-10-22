<tr>
    <td>
        <span class="{{ JOB_ICON }}" aria-hidden="true"></span>
    </td>
    <td><a href="{{ URL::route('connector.jobs.show',[$item->id]) }}">view</a></td>
    <td>{{ $item->id }}</td>
    <td>{{ $item->type }}</td>
    <td>{{ $item->reference }}</td>
    <td>{{ $item->status }}</td>
    <td>
        <small>{{ $item->created_at->format(CONNECTOR_DATE_FORMAT) }}</small>
    </td>
</tr>
