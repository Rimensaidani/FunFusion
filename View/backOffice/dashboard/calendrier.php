<?php
require_once '../../../Model/ActiviteVirtuelle.php';
require_once '../../../Controller/ActiviteVirtuelleController.php';
require_once '../../../Controller/ParticipationController.php';

$controller = new ActiviteVirtuelleController();
$participationController = new ParticipationController();
$activites = $controller->listActiviteVirtuelle();

$events = [];

foreach ($activites as $a) {
    // Récupération du nombre réel de participants
    $participantsCount = $participationController->countParticipations($a['id_activite']);
    $hasParticipants = $participantsCount > 0;
    
    $now = new DateTime();
    $eventDate = new DateTime($a['date']);
    
    if ($eventDate < $now) {
        $className = 'red'; // Activité passée
    } elseif ($hasParticipants) {
        $className = 'green'; // Activité future avec participants
    } else {
        $className = 'yellow'; // Activité future sans participants
    }
    
    $events[] = [
        'title' => $a['titre'] . ' (' . $participantsCount . ')',
        'start' => $a['date'],
        'className' => $className,
        'extendedProps' => [
            'participants' => $participantsCount,
            'id_activite' => $a['id_activite']
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier des Activités Virtuelles - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00ffcc;
            --secondary: #1a1a1a;
            --dark: #0e0e0e;
            --light: #ffffff;
            --red: #e74c3c;
            --green: #2ecc71;
            --yellow: #f1c40f;
        }
        
        body {
            background-color: var(--dark);
            font-family: 'Roboto', sans-serif;
            color: var(--light);
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary);
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(0, 255, 204, 0.5);
        }
        
        #calendar {
            background-color: var(--secondary);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 30px rgba(0, 255, 204, 0.2);
            border: 1px solid rgba(0, 255, 204, 0.1);
        }
        
        .fc-event {
            color: var(--light) !important;
            font-weight: bold;
            border: none !important;
            border-radius: 5px !important;
            padding: 5px !important;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .fc-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .fc-event.red {
            background-color: var(--red);
        }
        
        .fc-event.green {
            background-color: var(--green);
        }
        
        .fc-event.yellow {
            background-color: var(--yellow);
            color: #000 !important;
        }
        
        .fc-toolbar-title {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary) !important;
            font-size: 1.5em !important;
        }
        
        .fc-button {
            background-color: var(--secondary) !important;
            border: 1px solid var(--primary) !important;
            color: var(--primary) !important;
            transition: all 0.3s ease;
        }
        
        .fc-button:hover {
            background-color: var(--primary) !important;
            color: var(--dark) !important;
        }
        
        .fc-daygrid-day-number, .fc-col-header-cell-cushion {
            color: var(--light) !important;
            text-decoration: none !important;
        }
        
        .fc-daygrid-day.fc-day-today {
            background-color: rgba(0, 255, 204, 0.1) !important;
        }
        
        .event-tooltip {
            position: absolute;
            background: var(--secondary);
            border: 1px solid var(--primary);
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
            max-width: 300px;
            color: var(--light);
            box-shadow: 0 0 15px rgba(0, 255, 204, 0.3);
            font-size: 14px;
        }
        
        .event-tooltip h3 {
            color: var(--primary);
            margin-top: 0;
            border-bottom: 1px solid var(--primary);
            padding-bottom: 5px;
        }
        
        .legend {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 20px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }
        
        .legend-text {
            font-size: 14px;
        }
        
        .fc-event-title {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CALENDRIER DES ACTIVITÉS VIRTUELLES - BACKOFFICE</h1>
        
        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background-color: var(--red);"></div>
                <span class="legend-text">Activité passée</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: var(--green);"></div>
                <span class="legend-text">Avec participants</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: var(--yellow);"></div>
                <span class="legend-text">Sans participants</span>
            </div>
        </div>
        
        <div id="calendar"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
                },
                events: <?php echo json_encode($events); ?>,
                eventDidMount: function(info) {
                    var tooltip = document.createElement('div');
                    tooltip.className = 'event-tooltip';
                    tooltip.innerHTML = `
                        <h3>${info.event.title.split(' (')[0]}</h3>
                        <p><strong>Date:</strong> ${info.event.start.toLocaleString('fr-FR')}</p>
                        <p><strong>Nombre de participants:</strong> ${info.event.extendedProps.participants}</p>
                        <p><strong>ID Activité:</strong> ${info.event.extendedProps.id_activite}</p>
                        <p><strong>Statut:</strong> ${info.event.classNames.includes('red') ? 'Passée' : 
                           info.event.classNames.includes('green') ? 'À venir (avec participants)' : 'À venir (sans participants)'}</p>
                    `;
                    
                    info.el.addEventListener('mouseover', function(e) {
                        document.body.appendChild(tooltip);
                        tooltip.style.display = 'block';
                    });
                    
                    info.el.addEventListener('mouseout', function(e) {
                        document.body.removeChild(tooltip);
                    });
                    
                    info.el.addEventListener('mousemove', function(e) {
                        tooltip.style.top = (e.clientY + 10) + 'px';
                        tooltip.style.left = (e.clientX + 10) + 'px';
                    });
                }
            });
            
            calendar.render();
        });
    </script>
</body>
</html>