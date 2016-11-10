@extends('default')

@section('content')
        <table class="table">
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Sexe</th>
                <th>Date naissance</th>
            </tr>
            @foreach($personnes as $personne)
            <tr>
                <td>{{ $personne->nom }}</td>
                <td>{{ $personne->prenom }}</td>
                <td>{{ $personne->sexe }}</td>
                <td>{{ $personne->naissance }}</td>
                <td>{{ $personne->acte->type->date }}</td>
            </tr>
            @endforeach
        </table>
@endsection
