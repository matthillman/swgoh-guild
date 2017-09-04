<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body>
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
    			@foreach ($guild->characters as $character)
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
	</body>
</html>