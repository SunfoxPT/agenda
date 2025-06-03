<div>
    <x-card Title="Appointment Calendar" subtitle="Manage your appointments with ease" separator progress-indicator>
        <div id="calendar"></div>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = $('#calendar');

            if (calendarEl.length) {
                const calendar = new Calendar(calendarEl[0], {
                    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,today,next',
                        center: 'title',
                        right: 'createEventButton dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    customButtons: {
                        createEventButton: {
                            text: 'Novo Evento',
                            click: function () {
                                const url = '{{ route("admin.appointments.create") }}';
                                window.open(url, '_blank');
                            }
                        }
                    },
                    nowIndicator: true,
                    events: @json($events),
                    editable: true,
                    eventClick: function(info) {
                        const url = '{{ route("admin.appointments.edit", ":id") }}';
                        const finalUrl = url.replace(':id', info.event.id);
                        window.open(finalUrl, '_blank');
                    },
                    eventDrop: function(info) {
                       alert("Event dropped on " + info.event.start.toISOString());
                    },
                });

                calendar.render();
            }
        });
    </script>
</div>
