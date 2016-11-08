@extends('default')
@section('content')
    <h2>Import d'acte de mariages</h2>
    {{ Form::open(['route' => 'import', 'method' => 'POST', 'files' => true]) }}
        <div class="form-group">
            {{ Form::label('csv', 'Fichier CSV à importer') }}
            {{ Form::file('csv') }}
        </div>
        <div class="form-group">
            {{ Form::submit('Envoyer le fichier') }}
        </div>
    {{ Form::close() }}
@endsection