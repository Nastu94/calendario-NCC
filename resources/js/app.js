import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import itLocale from '@fullcalendar/core/locales/it';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {  // Verifica che l'elemento calendar esista nella pagina
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            locale: itLocale,
            firstDay: 1,
            initialView: 'dayGridMonth',
            events: window.calendarEvents || [],
            eventContent: function(arg) {
                let badgeEl = document.createElement('div');
                badgeEl.className = 'event-badge';
                badgeEl.textContent = arg.event.title;
                return { domNodes: [badgeEl] };
            },
            eventClick: function(info) {
                window.location.href = info.event.url;
            }
        });

        calendar.render();
    }
});
