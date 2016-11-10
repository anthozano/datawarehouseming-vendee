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
        </tr>
    </table>
    <p>La personne <a href="bien été supprimée."></a></p>
    <a href="{{ route('personnes.index') }}" class="btn btn-primary">Retour sur l'index</a>
@endsection
