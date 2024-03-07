document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: events,
        eventClick: function(info) {
            if (info.event.extendedProps.url) {
                window.location.href = info.event.extendedProps.url; // Reindirizza alla URL dell'evento
            }
        }
    });

    calendar.render();
});


/**
 * Sezione Relativa alla visualizzazione dei dati corretti per il docente.
 *
 * 
 */

function showExams(){

    practices = document.getElementsByClassName("practice");;
    for (var i = 0; i < practices.length; i++) {

        practices[i].style.display = 'none';
    }
    exame = document.getElementsByClassName("exame");;
    for (var i = 0; i < exame.length; i++) {

        exame[i].style.display = 'table-row';
    }
    
}

function showPractices(){

    exams = document.getElementsByClassName("exame");;
    for (var i = 0; i < exams.length; i++) {

        exams[i].style.display = 'none';
    }
    practices = document.getElementsByClassName("practice");;
    for (var i = 0; i < practices.length; i++) {
    
        practices[i].style.display = 'table-row';
    }

}   