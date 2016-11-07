@extends('default')

@section('content')
    <form action="" method="POST">
        <p>
            <label for="">Fichier CSV à importer</label>
            <input type="file" name="csv" id="">
        </p>
        <p>
            <input type="submit" value="Envoyer">
        </p>
    </form>
@endsection
