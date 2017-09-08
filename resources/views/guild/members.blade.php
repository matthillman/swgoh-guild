@extends('layouts.app')

@section('content')

<div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text fill center">{{ $guild->name }}</h2>
    </div>

    <div class="mdl-card__supporting-text flex-vertical-centered wide" id="app">
        <div class="segmented-control">
            <span v-for="state in states"
                v-on:click="show(state)" 
                :class="{selected: selected === state}"
            >@{{state}}</span>
        </div>
		<characters v-if="selected == 'characters'"></characters>
		<members v-if="selected == 'members'"></members>
	</div>
</div>
@endsection