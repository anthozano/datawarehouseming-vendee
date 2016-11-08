@extends('default')
@section('content')
    <h2>Import d'acte de mariages</h2>
    <p>Format des colonnes à respecter :</p>
    <table>
        <tr>
            <th>id</th>
            <th>typeActe</th>
            <th>epoux</th>
            <th>prenomEpoux</th>
            <th>prenomPereEpoux</th>
            <th>nomMereEpoux</th>
            <th>prenomMereEpoux</th>
            <th>epouse</th>
            <th>prenomEpouse</th>
            <th>prenomPereEpouse</th>
            <th>nomMereEpouse</th>
            <th>prenomMereEpouse</th>
            <th>lieu</th>
            <th>dept</th>
            <th>dates</th>
            <th>numVue</th>
        </tr>
    </table>
    {{ Form::open(['url' => 'import', 'method' => 'POST', 'files' => true]) }}
            {{ Form::label('csv', 'Fichier CSV à importer') }}
            {{ Form::file('csv') }}
            {{ Form::submit('Envoyer le fichier') }}
    {{ Form::close() }}
@endsection