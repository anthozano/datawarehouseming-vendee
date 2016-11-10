@extends('default')

@section('content')
    <h2>Editer une personne</h2>
    {{ Form::open(['method' => 'put']) }}
    <div class="form-group">
        {{ Form::label('nom', 'Nom') }}
        {{ Form::text('nom', $personne->nom, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('prenom', 'Prenom') }}
        {{ Form::text('prenom', $personne->prenom, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('sexe', 'Sexe') }}
        {{ Form::text('prenom', $personne->sexe, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('date', 'Date de naissance') }}
        {{ Form::date('date', $personne->naissance, ['class' => 'form-control']) }}
    </div>
    {{ Form::submit('Envoyer') }}
    {{ Form::close() }}
@endsection