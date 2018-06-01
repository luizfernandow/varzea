@extends('layouts.app')

@section('content')
<div class="container">  
    <div class="card mb-2">
      <div class="card-body">
        <h5 class="card-title">{!! $race->name !!}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{!! $race->date_start !!} {!! $race->time_start !!}</h6>
        <button id="startTimer" type="button" class="btn btn-primary btn-lg">Start</button>
        <div id="timer" class="alert alert-info mt-2 text-center" role="alert">
          -
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-body">                  
            @foreach ($racers as $racerId => $racer)
                <a href="#" class="racer btn btn-primary btn-lg btn-block mb-2 text-left" role="button"
                    data-value="{{$racerId}}"
                    data-lap=0
                >
                    <div class="row">
                        <div class="col-6 text-truncate">
                            {{$racer}}
                        </div>
                        <div class="col-6 current-time">
                            
                        </div>
                    </div>     
                    <div class="laps">
                        
                    </div>               
                </a>
            @endforeach                    
        </div>
    </div>
</div>
@endsection

@section('javascript')
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
$(function() {
    var timer = new Timer;
    
    timer.addEventListener('secondsUpdated', function (e) {
        $('#timer').html(timer.getTimeValues().toString());
    });

    $('#startTimer').click(function(e) {
        $(this).addClass('disabled');
        timer.start();
    });

    $('.racer').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (timer.isRunning()) {
            var obj = $(this);
            var currentTime = timer.getTimeValues().toString();
            var totalSeconds = timer.getTotalTimeValues().seconds;            
            obj.find('.current-time').html(currentTime); 
            var lap = obj.data('lap'); 
            var laps = obj.data('laps'); 
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
            obj.find('.laps').append('<span> ' + lap + ' - ' + time + '</span><br>'); 
            obj.data('lap', lap);
            obj.data('laps', laps);
            if (lap == {{$race->laps}}) {
                obj.removeClass('btn-primary').addClass('btn-success disabled');
            }
        }
    });
});
</script>
@endsection