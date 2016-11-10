@extends('default')

@section('content')
    <h2>Statistiques</h2>
    <H2>Nombre de personnes dans la base de données:</H2>
    <table>
        <tr>
            <th>Total</th>
            <th>Mariés</th>
        </tr>
        <tr>
            <th>{{$nbPers}}</th>
            <th></th>
        </tr>

    </table>
@endsection
