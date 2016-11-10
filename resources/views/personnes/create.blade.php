@extends('default')

@section('content')
    <h2>Ajouter une nouvelle personne</h2>
    {{ Form::open(['method' => 'post', 'url' => route('personnes.store')]) }}
    <div class="form-group">
        {{ Form::label('nom', 'Nom') }}
        {{ Form::text('nom', null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('prenom', 'Prenom') }}
        {{ Form::text('prenom', null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('sexe', 'Sexe') }}
        {{ Form::text('sexe', null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('date', 'Date de naissance') }}
        {{ Form::date('date',null, ['class' => 'form-control']) }}
    </div>
    {{ Form::submit('Envoyer', ['class' => 'btn btn-primary']) }}
    {{ Form::close() }}
@endsection