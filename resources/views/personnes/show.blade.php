@extends('default')

@section('content')
    <table class="table">
        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Sexe</th>
            <th>Date naissance</th>
        </tr>
        <tr>
            <td>{{ $personne->nom }}</td>
            <td>{{ $personne->prenom }}</td>
            <td>{{ $personne->sexe }}</td>
            <td>{{ $personne->naissance }}</td>
            <td><a class="btn btn-primary" href="{{ route('personnes.edit', $personne)}}">Editer</a></td>
        </tr>
    </table>
    <a href="{{ route('personnes.create') }}" class="btn btn-primary">Créer une nouvelle personne</a>
@endsection
