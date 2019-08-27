@extends('layouts.app')

@section('title', __('championships.title'))

@section('content')
    @auth
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <a href="{{ route('championships.create') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    @lang('championships.link.create')
                </a>              
            </div>
        </div>  
    @endauth

    <div class="mdl-list list-resource">
        @foreach($championships as $championship)
            <div class="mdl-list__item mdl-list__item--three-line">
                <span class="mdl-list__item-primary-content">
                    <span>{!! $championship->name !!} </span>
                </span>
                @auth
                    <span class="mdl-list__item-secondary-content">
                        <div class="mdl-list__item-secondary-action">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="championship-action-{{ $championship->id }}">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="championship-action-{{ $championship->id }}">
                                <li class="mdl-menu__item">
                                    <a class="mdl-navigation__link" href="{{ route('championships.edit', [$championship->id]) }}">
                                        <i class="material-icons">edit</i>
                                        @lang('championships.link.edit')
                                    </a>
                                </li>
                                <li class="mdl-menu__item">
                                    {!! Form::open(array('class' => 'inline-block', 'id' => 'delete_'.$championship->id, 'method' => 'DELETE', 'route' => array('championships.destroy', $championship->id))) !!}
                                        {{ method_field('DELETE') }}
                                        <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$championship->id}} mdl-navigation__link" data-championshipid="{{$championship->id}}">
                                            <i class="material-icons">delete_forever</i>
                                            @lang('championships.link.delete')
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
            Deleting the championship all the ranking will be modified.
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

    let championshipIdDelete;
    $('.dialiog-trigger-delete').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        championshipIdDelete = $(this).data('championshipid');
        dialog.showModal();
    });

    dialog.querySelector('.delete').addEventListener('click', function() {
        $('#delete_' + championshipIdDelete).submit();
    });

    dialog.querySelector('.close').addEventListener('click', function() {
        dialog.close();
    });
</script>
@endsection