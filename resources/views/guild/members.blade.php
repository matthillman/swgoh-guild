@extends('layouts.app')

@section('content')
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
@endsection