<?php
// Spara event-data i en JSON-fil
$eventsFile = 'events.json';
if (!file_exists($eventsFile)) {
    file_put_contents($eventsFile, '{}');
}
$events = json_decode(file_get_contents($eventsFile), true);

// Aktuell månad och år
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$selectedDay = isset($_GET['day']) ? intval($_GET['day']) : null;

// Lägga till event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_text'], $_POST['day'])) {
    $day = intval($_POST['day']);
    $eventKey = "$year-$month-$day";
    $eventText = trim($_POST['event_text']);
    if ($eventText !== '') {
        if (!isset($events[$eventKey])) {
            $events[$eventKey] = [];
        }
        $events[$eventKey][] = $eventText;
        file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
    }
    header("Location: ?year=$year&month=$month&day=$day");
    exit;
}

// Generera dagar för månaden
function generateDays($year, $month)
{
    $days = [];
    $firstDayOfMonth = date('w', strtotime("$year-$month-01"));
    $lastDateOfMonth = date('t', strtotime("$year-$month-01"));

    for ($i = 0; $i < $firstDayOfMonth; $i++) {
        $days[] = null;
    }

    for ($day = 1; $day <= $lastDateOfMonth; $day++) {
        $days[] = $day;
    }

    return $days;
}

$daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$days = generateDays($year, $month);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Calendar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; }
        .day { padding: 10px; border: 1px solid #ccc; cursor: pointer; }
        .empty { background-color: #f9f9f9; }
        .selected { background-color: #e0e0e0; }
        .has-event { font-weight: bold; color: #007BFF; }
    </style>
</head>
<body>

<h2>
    <?= date('F Y', strtotime("$year-$month-01")) ?>
</h2>
<a href="?year=<?= $month == 1 ? $year - 1 : $year ?>&month=<?= $month == 1 ? 12 : $month - 1 ?>">◀ Prev</a> |
<a href="?year=<?= $month == 12 ? $year + 1 : $year ?>&month=<?= $month == 12 ? 1 : $month + 1 ?>">Next ▶</a>

<div class="calendar">
    <?php foreach ($daysOfWeek as $dayName): ?>
        <div><strong><?= $dayName ?></strong></div>
    <?php endforeach; ?>

    <?php foreach ($days as $day): ?>
        <?php if ($day === null): ?>
            <div class="empty"></div>
        <?php else:
            $key = "$year-$month-$day";
            $hasEvent = isset($events[$key]);
            $classes = "day";
            if ($hasEvent) $classes .= " has-event";
            if ($day == $selectedDay) $classes .= " selected";
            ?>
            <a href="?year=<?= $year ?>&month=<?= $month ?>&day=<?= $day ?>" class="<?= $classes ?>"><?= $day ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php if ($selectedDay): ?>
    <h3>Events for <?= $selectedDay ?>/<?= $month ?>/<?= $year ?></h3>
    <ul>
        <?php
        $eventKey = "$year-$month-$selectedDay";
        if (!empty($events[$eventKey])):
            foreach ($events[$eventKey] as $event):
                ?>
                <li><?= htmlspecialchars($event) ?></li>
            <?php
            endforeach;
        else:
            echo "<li>No events yet.</li>";
        endif;
        ?>
    </ul>

    <form method="post">
        <input type="hidden" name="day" value="<?= $selectedDay ?>">
        <input type="text" name="event_text" placeholder="Add new event" required>
        <button type="submit">Add</button>
    </form>
<?php endif; ?>

</body>
</html>
