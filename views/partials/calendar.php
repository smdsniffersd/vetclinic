<?php


$today = new DateTime();
$day = date('j');
$days = [];
$daysInMonth = date('Y-m-t');


$startDate = new DateTime();
if ($startDate->format('w') != 0) {
    $startDate->modify('last sunday');
}
$dates = [];
for ($i = 0; $i < 35; $i++) {
    $date = clone $startDate;
    $date->modify("+$i days");
    $dates[] = $date;
}
$daysNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
?>


<div class="calendar">
    <div class="calendar-cal">
        <div class="calendar-header">
            <span>Select a Date & Time</span>
        </div>
        <div class="calendar-element">
            <div class="calendar-element-header">
                <span class="calendar-mounts-span"><?= htmlspecialchars($today->format('F, Y')) ?></span>
                <div class="calendar-buttons">
                    <button><img src="image/calendar-button-img-left.png" alt="calendar-button-img-left" class="calendar-button-img-left"></button>
                    <button><img src="image/calendar-button-img-rigth.png" alt="calendar-button-img-rigth" class="calendar-button-img-rigth"></button>
                </div>
            </div>
            <div class="calendar-grid">
                <?php foreach ($daysNames as $dayName): ?>
                    <div class="calendarDayName"><?= htmlspecialchars($dayName) ?></div>
                <?php endforeach; ?>
                <?php foreach ($dates as $date): ?>
                    <div class="calendar-day 
                    <?=
                    (($date->format('Y-m-d') == $today->format('Y-m-d')) ? 'current' : ((($daysInMonth < $date->format('Y-m-d')) || ($date->format('Y-m-d') < $today->format('Y-m-d'))) ? 'next-month' : ''))
                    ?>"
                    onclick=" selectDay(this)"
                        data-full-date="<?= $date->format('Y-m-d') ?>">
                        <?= $date->format('j') ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="calendar-footer">
            <div class="calendar-footer-div">
                <img src="image/calendar-planet-img.png" alt="calendar-planet-img" class="calendar-planet-img">
                <span>All Times Are in Central Time</span>
                <img src="image/calendar-bottom-arrow-img.png" alt="calendar-bottom-arrow-img" class="calendar-bottom-arrow-img">
            </div>
            

        </div>
        <button id="sumbit" class="calendar-sumbit-button" type="submit" form="bookForm">Booking Appointment</button>
    </div>
    <div class="calendar-time">
        <span class="calendar-time-BIG-text-span"><?= htmlspecialchars($today->format('l, F d')) ?></span>
        <ul class="calendar-time-ul" id="calendarTimeUl">
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="10:00">10.00 AM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="10:30">10.30 AM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="11:00">11.00 AM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="11:30">11.30 AM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="12:00">12.00 PM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="12:30">12.30 PM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="13:00">13.00 PM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="13:30">13.30 PM</li>
            <li class="calendar-time-li" onclick="timeBooking(this)" data-time="14:00">14.00 PM</li>
        </ul>
    </div>

</div>