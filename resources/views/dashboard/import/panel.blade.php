@extends('default')
@section('content')
    <h2>Traitement des données brutes</h2>
    {{ Form::open(['route' => 'import', 'method' => 'POST', 'files' => true]) }}
        <div class="form-group">
            {{ Form::submit('Data processing', ['class' => 'btn btn-default']) }}
        </div>
    {{ Form::close() }}
    <p>Cette action peut prendre un certain temps.</p>
@endsection