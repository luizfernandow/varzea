@extends('layouts.app')

@section('title', __('races.startRace.header'))

@section('content')

<div class="race-card-wide mdl-card mdl-shadow--2dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">{!! $race->name !!}</h2>
    </div>
    <div class="mdl-card__supporting-text">
        {!! $race->date_start !!} - {!! $race->time_start !!}
        <div class="mdl-grid ">
            <div class="mdl-cell  mdl-cell--12-col">
                <button id="startTimer" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">Start</button>
                <button id="stopTimer" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" disabled >Stop</button>
                <button id="saveLaps" type="button" class="float-right mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--success" disabled>Save</button>
            </div>
        </div>
        <div class="mdl-grid">
            <div id="timer" class="mdl-cell  mdl-cell--12-col text-center" role="alert">
              -
            </div>
        </div>
    </div>
</div>

<div class="mdl-grid">
    <div id="racers-list" class="mdl-cell  mdl-cell--12-col">                  
        @foreach ($racers as $racerId => $racer)
            <div class="mdl-grid racer-wrappper"
                data-id="{{$racerId}}"
                data-lap=0
                data-total-seconds=86400
            >
                <button type="button" class="mdl-cell racer mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored text-left" role="button"                    
                >
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-tablet mdl-cell--2-col-phone text-truncate">
                            {{$racer}}
                        </div>
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-tablet mdl-cell--2-col-phone current-time">
                            
                        </div>
                    </div>     
                    <div class="laps">
                        
                    </div>               
                </button>
                <button type="button" class="mdl-cell revert mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent text-center" role="button"
                >
                    <i class="fa fa-undo"></i>            
                </button>
            </div>
        @endforeach                    
    </div>
</div>

<form method="POST" action="{{ route('saveLaps', $id) }}" id="saveLapsForm">
    @csrf
</form>
@endsection

@section('javascript')
    @parent('javascript')
<script type="text/javascript">
String.prototype.toHHMMSS = function () {
    var sec_num = parseInt(this, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes+':'+seconds;
}

function sortElements(elements) {
    var elems = [];
    for( var i = 0; i < elements.length; ++i ) {
        var el = elements[i];
        elems.push( el );
    }
    var sorted = elems.sort( sortLaps );
    return sorted;  
}

function sortLaps( a, b ) {
    var aTotal = parseInt( $(a).data('total-seconds'), 10 );
    var bTotal = parseInt( $(b).data('total-seconds'), 10 );
    var aLap = parseInt( $(a).data('lap'), 10 );
    var bLap = parseInt( $(b).data('lap'), 10 );
    if (aLap == bLap) {
        return aTotal > bTotal;         
    }
    return aLap < bLap;
}

$(function() {
    var timer = new Timer;
    var raceStarted = false;

    timer.addEventListener('secondsUpdated', function (e) {
        $('#timer').html(timer.getTimeValues().toString());
    });

    $('#startTimer').click(function(e) {
        $(this).attr('disabled', true);
        timer.start();
        raceStarted = true;
        $('#stopTimer').attr('disabled', false);
        $('#saveLaps').attr('disabled', true);
    });

    $('#stopTimer').click(function(e) {
        $(this).attr('disabled', true);
        timer.pause();
        $('#startTimer').attr('disabled', false);
        $('#saveLaps').attr('disabled', false);
    });

    $('#saveLaps').click(function(e){
        if ($('.racer.disabled').length == $('.racer').length) {
            window.onbeforeunload = null;
        }
        $('.racer').each(function( index ) {
            var obj = $(this);
            var wrapper = obj.parent();
            $('#saveLapsForm').append('<input type="hidden" name="' + wrapper.data('id') + '" value="' + JSON.stringify(wrapper.data('laps')) +'">');
        });
        $('#saveLapsForm').submit();
    });

    $('.racer').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (timer.isRunning()) {
            var obj = $(this);
            var wrapper = obj.parent();
            var currentTime = timer.getTimeValues().toString();
            var totalSeconds = timer.getTotalTimeValues().seconds;            
            obj.find('.current-time').html(currentTime); 
            var lap = wrapper.data('lap'); 
            var laps = wrapper.data('laps'); 
            var lapTime = 0;
            var time = '';
            if (!lap) {
                time = currentTime;  
                laps = [];
                lapTime = totalSeconds;
            } else {
                lapTime = totalSeconds;
                laps.forEach(function(item) {
                  lapTime -= item;
                });
                time = lapTime.toString().toHHMMSS();
            }
            laps.push(lapTime);
            lap++;
            obj.find('.laps').append('<span class="badge badge-success"> ' + lap + ' - ' + time + '</span>'); 
            wrapper.data('lap', lap);
            wrapper.data('laps', laps);
            wrapper.data('total-seconds', totalSeconds);
            if (lap == {{$race->laps}}) {
                obj.removeClass('mdl-button--colored').addClass('mdl-button--success disabled').attr('disabled', true);
            }

            if ($('.racer.disabled').length == $('.racer').length) {
                timer.pause();
                $('#saveLaps').attr('disabled', false);
                $('#stopTimer').attr('disabled', true);
            }

            sortRacers();
        }
    });

    $('.revert').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var obj = $(this);
        var wrapper = obj.parent();
        var lap = wrapper.data('lap'); 
        if (lap) {
            var racer = wrapper.find('.racer');
            var totalSeconds = wrapper.data('totalSeconds'); 
            var laps = wrapper.data('laps'); 
            var timeLap = laps.pop();
            lap--;
            wrapper.data('lap', lap);
            wrapper.data('laps', laps);
            wrapper.data('total-seconds', totalSeconds - timeLap);

            if (racer.hasClass('disabled')) {
                racer.removeClass('mdl-button--success disabled').addClass('mdl-button--colored').attr('disabled', false);
            }

            racer.find('.current-time').html( (totalSeconds - timeLap).toString().toHHMMSS() ); 
            racer.find('.laps .badge-success').last().remove();
            sortRacers();
            if (!timer.isRunning()) {
                timer.start();
                $('#saveLaps').attr('disabled', true);
                $('#stopTimer').attr('disabled', false);
            }
        }        
    });

    function sortRacers() {
        var sortedElements = sortElements( $('.racer-wrappper') );
        var racersList = $('#racers-list');
        for( var i = 0; i < sortedElements.length; ++i ) {
            racersList.append(sortedElements[i]);
        }
    }

    window.onbeforeunload = function (e) {
      if(raceStarted) {
        var message = "Are you sure you want leave?";
        e.returnValue = message;
        return message;
      }
      return;
    };
});
</script>
@endsection