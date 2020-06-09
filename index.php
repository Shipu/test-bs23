<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brain Station 23 Test</title>
    <style>
        .red {
            color: red;
        }
    </style>
</head>
<body>
    <a href="test1.php" class="<?=checkUrl('test1.php') ? 'red' : '' ?>">Test 1</a>
    <a href="test2.php" class="<?=checkUrl('test2.php') ? 'red' : '' ?>">Test 2</a>
    <br>
    <?php

    require_once __DIR__.'/vendor/autoload.php';

    function checkUrl($string) {
        return $_SERVER['REQUEST_URI'] == '/'.$string;
    }

    if(checkUrl('test1.php')) {
        require __DIR__.'/test1.php';
    } elseif(checkUrl('test2.php')) {
        require __DIR__.'/test2.php';
    }

    ?>
</body>
</html>
