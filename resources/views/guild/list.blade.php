@extends('layouts.app')

@section('content')

@if (count($guilds) > 0)
<div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text fill">Guilds</h2>
    </div>
    <div class="mdl-card__supporting-text">
		<div class="flex-vertical-centered">
			<ul class="app-list mdl-list">
			@foreach ($guilds as $guild)
				<li class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
						<button class="mdl-button mdl-js-button" e-go="{{ route('guild.show', ['guild' => $guild->id]) }}">
							<i class="material-icons  mdl-list__item-avatar accent4 fixme">group</i>
							<span>{{ $guild->name }}</span>
						</button>
					</span>
					<span class="mdl-list__item-secondary-content horizontal">
						<form action="{{ route('guild.destroy', ['guild' => $guild->id]) }}" method="POST">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
							<button type="submit" class="mdl-button mdl-js-button mdl-button--icon error" id="delete-{{ $guild->id }}">
								<i class="material-icons">remove_circle</i>
							</button>
							<div class="mdl-tooltip" for="delete-{{ $guild->id }}">Delete {{ $guild->name }}</div>
						</form>
					</span>
				</li>
			@endforeach
			</ul>
		</div>
    </div>

</div>
@endif

<!-- New Guild Form -->
<form action="{{ url('guild') }}" method="POST" class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
    {{ csrf_field() }}
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Add a Guild</h2>
    </div>

    <div class="mdl-card__supporting-text">
        <div class="flex-vertical-centered">
            <!-- Display Validation Errors -->
            @include('common.errors')

            <!-- Guild Name -->

            @include('common.textinput', ['name' => 'name'])
            @include('common.textinput', ['display_name' => 'Webhook URL', 'name' => 'webhook'])


            <!-- Add Guild Button -->
            <p>
                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    <i class="material-icons">add_circle</i> Add Guild
                </button>
            </p>
        </div>
    </div>
</form>

@endsection
