<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];
function getFullnameFromParts($surname, $name, $patronymic) {
    return "$surname $name $patronymic";
}
foreach ($example_persons_array as $person) {
    $fullname = $person['fullname'];
    $parts = explode(' ', $fullname);
    $surname = array_shift($parts);
    $name = array_shift($parts);
    $patronymic = implode(' ', $parts);
    $fullname_from_parts = getFullnameFromParts($surname, $name, $patronymic);
    echo "$fullname_from_parts\n";
}

function getPartsFromFullname($fullname) {
    $parts = explode(' ', $fullname);
    $surname = array_shift($parts);
    $name = array_shift($parts);
    $patronymic = implode(' ', $parts);
    return [
        'name' => $name,
        'surname' => $surname,
        'patronymic' => $patronymic
    ];
}

foreach ($example_persons_array as $person) {
    $fullname = $person['fullname'];
    $parts = getPartsFromFullname($fullname);
    $surname = $parts['surname'];
    $name = $parts['name'];
    $patronymic = $parts['patronymic'];
    echo "Surname: $surname, Name: $name, Patronymic: $patronymic\n";
}

function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $short_name = $parts['name'] . ' ' . substr($parts['surname'], 0, 1) . '.';
    return $short_name;
}
foreach ($example_persons_array as $person) {
    $fullname = $person['fullname'];
    $short_name = getShortName($fullname);
    echo "$short_name\n";
}
function getGenderFromName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $sum = 0;

    if (preg_match('/ич$/', $parts['patronymic'])) {
        $sum++;
    }
    if (preg_match('/(а|ей)$/', $parts['name'])) {
        $sum--;
    }
    if (preg_match('/(ина|овна)$/', $parts['patronymic'])) {
        $sum--;
    }
    if (preg_match('/(ая|евна|ый|ов)$/', $parts['surname'])) {
        $sum++;
    }

    if ($sum > 0) {
        return 1;
    } elseif ($sum < 0) {
        return -1;
    } else {
        return 0;
    }
}
function getGenderDescription($audience_array) {
    $genders = array_filter($audience_array, function($person) {
        return getGenderFromName($person['fullname']) !== 0;
    });

    $men = array_filter($genders, function($person) {
        return getGenderFromName($person['fullname']) === 1;
    });

    $women = array_filter($genders, function($person) {
        return getGenderFromName($person['fullname']) === -1;
    });

    $unknown = array_filter($audience_array, function($person) {
        return getGenderFromName($person['fullname']) === 0;
    });

    $total = count($audience_array);
    $men_percent = round(count($men) / $total * 100, 1);
    $women_percent = round(count($women) / $total * 100, 1);
    $unknown_percent = round(count($unknown) / $total * 100, 1);

    $result = "Гендерный состав аудитории:\n";
    $result .= "---------------------------\n";
    $result .= "Мужчины - $men_percent%\n";
    $result .= "Женщины - $women_percent%\n";
    $result .= "Не удалось определить - $unknown_percent%\n";

    return $result;
}
function getPerfectPartner($surname, $name, $patronymic, $audience_array) {
    $surname = mb_strtolower(trim($surname));
    $name = mb_strtolower(trim($name));
    $patronymic = mb_strtolower(trim($patronymic));

    $fullname = getFullnameFromParts($surname, $name, $patronymic);
    $gender = getGenderFromName($fullname);

    $random_person = $audience_array[array_rand($audience_array)];

    do {
        $random_fullname = $random_person['fullname'];
        $random_gender = getGenderFromName($random_fullname);
    } while ($random_gender === $gender);

    $compatibility = rand(5000, 10000) / 100;
    return "{$fullname} + {$random_fullname} = \n♡ Идеально на {$compatibility}% ♡";
}
echo getGenderDescription($example_persons_array);
echo getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);
?>