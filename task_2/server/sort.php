<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quicksort</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<h1>Сортировка массива</h1>
<?php

function ShellSort($elements)
{
    $length = count($elements);
    $k = 0;
    $gap[0] = (int)($length / 2);

    while ($gap[$k] > 1) {
        $k++;
        $gap[$k] = (int)($gap[$k - 1] / 2);
    }

    for ($i = 0; $i <= $k; $i++) {
        $step = $gap[$i];

        for ($j = $step; $j < $length; $j++) {
            $temp = $elements[$j];
            $p = $j - $step;
            while ($p >= 0 && $temp < $elements[$p]) {
                $elements[$p + $step] = $elements[$p];
                $p = $p - $step;
            }
            $elements[$p + $step] = $temp;
        }
    }

    return $elements;
}

function print_array($array): void
{
    echo "<pre>[";
    echo implode(", ", $array);
    echo "]</pre>";
}

if (isset($_GET['array'])) {
    $array = explode(",", $_GET["array"]);
    echo "<p>Считанный массив</p>";
    print_array($array);
    $array = ShellSort($array);
    echo "<p>Отсортированный массив</p>";
    print_array($array);
} else {
    echo "<p>Задайте числа, разделённые запятыми в параметре array, чтобы отсортировать их</p>
<pre>
    http:localhost:8080/sort.php?array=4,2,5,...
</pre>";
}
?>
</body>
</html>

