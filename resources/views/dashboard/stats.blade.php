@extends('default')

@section('content')
    <h2>Statistiques</h2>
    <H2>Nombre de personnes dans la base de données:</H2>
    <table class="table">
        <tr>
            <th>Total</th>
            <th>Mariés</th>
            <th>Morts</th>
        </tr>
        <tr>
            <th>{{$nbPers}}</th>
            <th>{{$nbMaries}}</th>
            <th>{{$nbMorts}}</th>
        </tr>

    </table>
@endsection
