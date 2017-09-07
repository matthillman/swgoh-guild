@extends('layouts.app')

@section('content')


<div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text fill center">Guild Characters</h2>
    </div>

    <div class="mdl-card__supporting-text flex-vertical-centered wide">
		<div id="app">
			<example
				route="members"
				v-bind:columns="memberColumns"
			></example>
		</div>
	</div>
</div>
@endsection