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
    {{ Form::open(['url' => 'import', 'method' => 'POST', 'file' => true]) }}
            {{ Form::label('csv', 'Fichier CSV à importer') }}
            {{ Form::file('csv') }}
            {{ Form::submit('Envoyer le fichier', ['name' => 'mariage']) }}
    <h2>Import d'acte de décès</h2>
    <p>Format des colonnes à respecter :</p>
    <table>
        <tr>
            <th>id</th>
            <th>typeActe</th>
            <th>nom</th>
            <th>prenom</th>
            <th>age</th>
            <th>nomPere</th>
            <th>prenomPere</th>
            <th>nomMere</th>
            <th>prenomMere</th>
            <th>lieu</th>
            <th>dept</th>
            <th>dateNaissance</th>
            <th>numVue</th>
        </tr>
    </table>
    {{ Form::close() }}
    {{ Form::open(['url' => 'import', 'method' => 'POST', 'file' => true]) }}
            {{ Form::label('csv', 'Fichier CSV à importer') }}
            {{ Form::file('csv') }}
            {{ Form::submit('Envoyer le fichier', ['name' => 'deces']) }}
    {{ Form::close() }}
@endsection