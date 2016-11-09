@extends('default')
@section('content')
    <h2>Import d'acte de mariages</h2>
    {{ Form::open(['route' => 'import', 'method' => 'POST', 'files' => true]) }}
        <div class="form-group">
            {{ Form::submit('Data process') }}
        </div>
    {{ Form::close() }}
@endsection