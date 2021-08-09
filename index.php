<?php
    setlocale(LC_ALL, 'ru_RU.UTF-8');    

    function conjugation ($datetime, $type = 'hours') {
        switch ($type) {
            case 'hours':
                if ($datetime >= 11 && $datetime <= 14) {
                    return "$datetime часов";
                } elseif (substr($datetime, -1) == 0 ||
                        (substr($datetime, -1) >=5 && substr($datetime, -1) <= 9)) {
                    return "$datetime часов";
                } elseif (substr($datetime, -1) >= 2 && substr($datetime, -1) <= 4) {
                    return "$datetime часа";
                }
                return "$datetime час";
            case 'minutes':
                if ($datetime >= 11 && $datetime <= 14) {
                    return "$datetime минут";
                } elseif (substr($datetime, -1) == 0 ||
                        (substr($datetime, -1) >=5 && substr($datetime, -1) <= 9)) {
                    return "$datetime минут";
                } elseif (substr($datetime, -1) >= 2 && substr($datetime, -1) <= 4) {
                    return "$datetime минуты";
                }
                return "$datetime минута";
            case 'days':
                if ($datetime >= 11 && $datetime <= 14) {
                    return "$datetime дней";
                } elseif (substr($datetime, -1) == 0 ||
                        (substr($datetime, -1) >=5 && substr($datetime, -1) <= 9)) {
                    return "$datetime дней";
                } elseif (substr($datetime, -1) >= 2 && substr($datetime, -1) <= 4) {
                    return "$datetime дня";
                }
                return "$datetime день";
                
        }
    }

    function hours_to_minutes ($hours) {
        return $hours * 60;
    }

    function minutes_to_hours ($minutes) {
        $hours = intdiv($minutes, 60);
        $minutes = $minutes % 60;
        return ['hours' => $hours, 'minutes' => $minutes];
    }

    function remain_minutes($worked_hours = 0, $worked_minutes = 0, $total_hours = 0) {
        $remain_minutes = hours_to_minutes($total_hours) - (hours_to_minutes($worked_hours) + $worked_minutes);
        return $remain_minutes; 
    }

    $month_info = explode(' ', date('n j t'));
    ///print_r($month_info);
    $count_weekends = 0;
    for ($i = $month_info[1] + 1; $i <= $month_info[2]; $i++) {
        $day = date('D', mktime(0, 0, 0, $month_info[0], $i));
        if ($day == "Sat" || $day == "Sun") {
            $count_weekends++;
        }
    }
    $count_work_days = $month_info[2] - $month_info[1] - $count_weekends;
    
    $worked_hours = isset($_GET['worked_hours']) ? $_GET['worked_hours'] : '';
    $worked_minutes = isset($_GET['worked_minutes']) ? $_GET['worked_minutes'] : '';
    $total_hours = isset($_GET['total_hours']) ? $_GET['total_hours'] : '';
    if (isset($_GET['submit']) && $_GET['submit'] == 'yes') {
        $remain_minutes = remain_minutes($worked_hours, $worked_minutes, $total_hours);
        $remain_minutes_per_day = $remain_minutes / $count_work_days;
        list('hours' => $hours_per_day, 'minutes' => $minutes_per_day) = minutes_to_hours($remain_minutes_per_day);
    }
        
?>
<!DOCTYPE html>
<html>
<head>
    <title>Timemanagement</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="/sources/css/style.css" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
    <div class="form-group">
        <form action="">
        <input title="Отработано часов" required type="number" step="1" name="worked_hours" value="<?=$worked_hours?>" placeholder="Отработано часов">
        <input title="Отработано минут" required type="number" step="1" name="worked_minutes" value="<?=$worked_minutes?>" placeholder="Отработано минут">
        <input title="Нужно отработать в месяц" required type="number" step="1" name="total_hours" value="<?=$total_hours?>" placeholder="Нужно отработать в месяц часов">
        <button name="submit" value="yes">Рассчитать</button>
        </form>
    </div>
    <?php if (isset($hours_per_day) && isset($minutes_per_day)):?>
    <div class="content">
        <p>Осталось работать <?=conjugation($count_work_days, 'days')?></p>
        <p><?=conjugation($hours_per_day)?> <?=conjugation($minutes_per_day, 'minutes')?> в день</p>
    </div>
    <?php endif ?>
    <div class="footer"></div>
        

</body>
</html>
