@extends('layouts.app')

@section('title', __('races.title'))

@section('content')
    @auth
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <a href="{{ route('races.create') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    @lang('races.link.create')
                </a>              
            </div>
        </div>  
    @endauth

    <div class="mdl-list list-resource">
        @foreach($races as $race)
            <div class="mdl-list__item mdl-list__item--three-line">
                <span class="mdl-list__item-primary-content">
                    <span><a href="{!! route('races.show', [$race->id]) !!}"> {!! $race->name !!} </a></span>
                    <span class="mdl-list__item-text-body">
                        {{ $race->date_start }} - {{ $race->time_start }}
                        <br>
                        @if($race->isTypeHours())
                            {{ $race->hours }}  @lang('races.index.hours'),
                            {{ $race->group }}  @lang('races.index.group')
                        @else
                            {{ $race->laps }} @lang('races.index.laps')
                        @endif
                    </span>
                </span>
                @auth
                    <span class="mdl-list__item-secondary-content">
                        <div class="mdl-list__item-secondary-action">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="race-action-{{ $race->id }}">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="race-action-{{ $race->id }}">
                                @if(!$race->locked)
                                    @if($race->isTypeHours())
                                        <li class="mdl-menu__item">
                                            <a class="mdl-navigation__link" href="{{ route('selectGroups', [$race->id]) }}">
                                                <i class="material-icons">flag</i>
                                                @lang('races.link.selectGroups')
                                            </a>
                                        </li>
                                        <li class="mdl-menu__item">
                                            <a class="mdl-navigation__link" href="{{ route('startRaceGroups', [$race->id]) }}">
                                                <i class="material-icons">flag</i>
                                                @lang('races.link.startRaceGroups')
                                            </a>
                                        </li>
                                    @else
                                        <li class="mdl-menu__item">
                                            <a class="mdl-navigation__link" href="{{ route('selectRacers', [$race->id]) }}">
                                                <i class="material-icons">flag</i>
                                                @lang('races.link.selectRacers')
                                            </a>
                                        </li>
                                    @endif    
                                @endif 
                                <li class="mdl-menu__item">
                                    <a class="mdl-navigation__link" href="{{ route('races.edit', [$race->id]) }}">
                                        <i class="material-icons">edit</i>
                                        @lang('races.link.edit')
                                    </a>
                                </li>
                                <li class="mdl-menu__item">
                                    {!! Form::open(array('class' => 'inline-block', 'id' => 'delete_'.$race->id, 'method' => 'DELETE', 'route' => array('races.destroy', $race->id))) !!}
                                        {{ method_field('DELETE') }}
                                        <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$race->id}} mdl-navigation__link" data-raceid="{{$race->id}}">
                                            <i class="material-icons">delete_forever</i>
                                            @lang('races.link.delete')
                                        </a>
                                    {!! Form::close() !!}
                                </li>
                            </ul>
                        </div>
                    </span>
                @endauth
            </div>
        @endforeach
    </div>
    <dialog class="mdl-dialog">
        <h4 class="mdl-dialog__title">Are you sure?</h4>
        <div class="mdl-dialog__content">
            <p>
            Deleting the race all the ranking will be modified.
            </p>
        </div>
        <div class="mdl-dialog__actions">
            <button type="button" class="mdl-button delete">Delete</button>
            <button type="button" class="mdl-button close">Cancel</button>
        </div>
    </dialog>
@endsection

@section('javascript')
<script>
    var dialog = document.querySelector('dialog');
    if (!dialog.showModal) {
        dialogPolyfill.registerDialog(dialog);
    }

    let raceIdDelete;
    $('.dialiog-trigger-delete').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        raceIdDelete = $(this).data('raceid');
        dialog.showModal();
    });

    dialog.querySelector('.delete').addEventListener('click', function() {
        $('#delete_' + raceIdDelete).submit();
    });

    dialog.querySelector('.close').addEventListener('click', function() {
        dialog.close();
    });
</script>
@endsection