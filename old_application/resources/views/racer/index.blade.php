@extends('layouts.app')

@section('title', __('racers.title'))

@section('content')
    @auth
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <a href="{{ route('racers.create') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    @lang('racers.link.create')
                </a>              
            </div>
        </div>  
    @endauth
    <div class="mdl-grid ">
        <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height">
                <input type="text" value="" class="mdl-textfield__input" id="selected-championship_id" readonly>
                <input type="hidden" value="" name="selected-championship_id">
                <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                <label for="selected-championship_id" class="mdl-textfield__label">@lang('racers.select_championship_id')</label>
                <ul for="selected-championship_id" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                    @foreach($championships as $championship)   
                        <li class="mdl-menu__item" data-val="{{ $championship->id }}"
                            @if($currentChampionship == $championship->id)
                                data-selected="true"
                            @endif
                        >
                            {{ $championship->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class='mdl-list list-resource'>
        @foreach($racers as $index => $racer)
            <div class="mdl-list__item mdl-list__item--two-line">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons  mdl-badge mdl-badge--overlap mdl-list__item-avatar" data-badge="{{ ($index + 1) }}">person</i>
                    <span>{{ $racer->name }}</span>
                    <span class="mdl-list__item-sub-title">{{ $racer->points }} @lang('racers.index.points')</span>
                </span>
                @auth
                <div class="mdl-list__item-secondary-action">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="racer-action-{{ ($index + 1) }}">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="racer-action-{{ ($index + 1) }}">
                        <li class="mdl-menu__item">
                            <a class="mdl-navigation__link" href="{{ route('racers.edit', [$racer->id]) }}">
                                <i class="material-icons">edit</i>
                                @lang('racers.link.edit')
                            </a>
                        </li>
                        <li class="mdl-menu__item">
                            {!! Form::open(array('class' => 'inline-block', 'id' => 'delete_'.$racer->id, 'method' => 'DELETE', 'route' => array('racers.destroy', $racer->id))) !!}
                                {{ method_field('DELETE') }}
                                <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$racer->id}} mdl-navigation__link" data-racerid="{{$racer->id}}">
                                    <i class="material-icons">delete_forever</i>
                                    @lang('racers.link.delete')
                                </a>
                            {!! Form::close() !!}
                        </li>
                    </ul>   
                </div>
                @endauth
            </div>
        @endforeach
    </div>
    <dialog class="mdl-dialog">
        <h4 class="mdl-dialog__title">Are you sure?</h4>
        <div class="mdl-dialog__content">
            <p>
            Deleting the racer all the ranking will be modified.
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

    let racerIdDelete;
    $('.dialiog-trigger-delete').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        racerIdDelete = $(this).data('racerid');
        dialog.showModal();
    });

    dialog.querySelector('.delete').addEventListener('click', function() {
        $('#delete_' + racerIdDelete).submit();
    });

    dialog.querySelector('.close').addEventListener('click', function() {
        dialog.close();
    });

    $('#selected-championship_id').on('change', function(){
        var value = $(this).next().val();
        if (value != {{ $currentChampionship }}) {
            window.location = '{{  url()->current() }}?championship_id=' + value;
        }
    });

</script>
@endsection
