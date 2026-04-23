@extends('layouts.app')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' />
<style>
    .fc-day-grid-event .fc-content { white-space: normal; }
    .fc-event { cursor: pointer; }
</style>
@endpush

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Agenda</h1>
        <p>Planning unifié des actions, examens, formations et événements</p>
    </div>
    <div class="dr-card">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            events: '{{ route("agenda.index") }}?json=1',
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            }
        });
        calendar.render();
    });
</script>
@endpush