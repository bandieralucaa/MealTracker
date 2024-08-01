<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealTracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/lib/jquery.min.js'></script>
    <script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/lib/moment.min.js'></script>
    <script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.js'></script>
    <script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/locale/it.js'></script> <!-- Aggiungi questo script per la localizzazione in italiano -->
    <link rel="icon" href="../src/logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Calendario</h2>
        <div id='calendar'></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                locale: 'it', // Imposta la localizzazione in italiano
                dayClick: function(date, jsEvent, view) {
                    window.location.href = 'day.php?date=' + date.format();
                },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'Oggi',
                    month: 'Mese',
                    week: 'Settimana',
                    day: 'Giorno'
                }
            });
        });
    </script>
</body>
</html>
