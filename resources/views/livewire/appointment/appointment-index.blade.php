<div>
    <div id="calendar-alert" role="alert" class="alert hidden mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span id="calendar-alert-message">Mensagem</span>
    </div>

    <x-card Title="Appointment Calendar" subtitle="Manage your appointments with ease" separator progress-indicator>
        <div id="calendar"></div>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = $('#calendar');

            if (calendarEl.length) {
                const calendar = new Calendar(calendarEl[0], {
                    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    timeZone: 'Europe/Lisbon',
                    businessHours: @json($businessHours),
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
                    eventClick: function (info) {
                        const url = '{{ route("admin.appointments.edit", ":id") }}';
                        const finalUrl = url.replace(':id', info.event.id);
                        window.open(finalUrl, '_blank');
                    },
                    eventResize: function (info) {
                        $("#progress").removeClass("hidden");

                        const url = '{{ route("admin.appointments.UpdateDragAndDrop", ":id") }}';
                        const finalUrl = url.replace(':id', info.event.id);

                        $.ajax({
                            url: finalUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                start: info.event.start.toISOString(),
                                end: info.event.end ? info.event.end.toISOString() : null,
                            },
                            success: function (response) {
                                showCalendarAlert(response.success, true);
                            },
                            error: function (xhr) {
                                const msg = xhr.responseJSON?.error ?? 'Unexpected error.';
                                showCalendarAlert(msg, false);
                            },
                            complete: function () {
                                $("#progress").addClass("hidden");
                            }
                        });
                    },
                    eventDrop: function (info) {
                        $("#progress").removeClass("hidden");

                        const url = '{{ route("admin.appointments.UpdateDragAndDrop", ":id") }}';
                        const finalUrl = url.replace(':id', info.event.id);

                        $.ajax({
                            url: finalUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                start: info.event.start.toISOString(),
                                end: info.event.end ? info.event.end.toISOString() : null,
                            },
                            success: function (response) {
                                showCalendarAlert(response.success, true);
                            },
                            error: function (xhr) {
                                const msg = xhr.responseJSON?.error ?? 'Unexpected error.';
                                showCalendarAlert(msg, false);
                            },
                            complete: function () {
                                $("#progress").addClass("hidden");
                            }
                        });
                    }
                });

                calendar.render();
            }
        });

        function showCalendarAlert(message, isSuccess) {
            const alertBox = $('#calendar-alert');
            const messageSpan = $('#calendar-alert-message');

            messageSpan.text(message);

            alertBox
                .removeClass('alert-success alert-danger hidden')
                .addClass(isSuccess ? 'alert-success' : 'alert-error')
                .fadeIn();

            $("html, body").animate({ scrollTop: 0 }, "slow");

            setTimeout(() => {
                alertBox.fadeOut();
            }, 4000);
        }

    </script>
</div>
