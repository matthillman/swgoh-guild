@extends('layouts.app')

@section('content')

<div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text fill center">{{ $guild->name }}</h2>
    </div>

    <div class="mdl-card__supporting-text flex-vertical-centered wide" id="app">
		<characters
			route="characters"
		></characters>
	</div>
</div>
@endsection