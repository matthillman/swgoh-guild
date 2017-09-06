@extends('layouts.app')

@section('content')

<div id="app">@{{message}}</div>
<example></example>

<div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text fill center">Guild Characters</h2>
    </div>

    <div class="mdl-card__supporting-text flex-vertical-centered wide">
		<table>
			<thead>
				<tr>
					<th>Character</th>
					<th>Member</th>
					<th>Rarity</th>
					<th>Level</th>
					<th>Gear</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($guild->characters->sortBy('name') as $character)
				<tr>
					<td>{{ $character->name }}</td>
					<td>{{ $character->member->name }}</td>
					<td>{{ $character->rarity }}</td>
					<td>{{ $character->level }}</td>
					<td>{{ $character->gear_level }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection