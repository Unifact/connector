@extends('connector::layout.master')

@section('content')

    <div class="panel panel-default panel-job">
        <div class="panel-heading"><strong>Search result for "{{ $query }}"</strong></div>
        <div class="panel-body">

            <table class="table table-condensed">
                <thead>
                    <th class="smacol"></th>
                    <th class="smacol"></th>
                    <th class="smacol">ID</th>
                    <th>Type/Stage</th>
                    <th>Reference</th>
                    <th>Status</th>
                    <th class="datecol">Created</th>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        {!! $result->getSearchResultRow() !!}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
