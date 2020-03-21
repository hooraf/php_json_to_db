<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
if (isset($_GET['add'])) {
    define("DB_NAME", "file_store");
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    $DSN = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    try {
        $pdo = new PDO($DSN, DB_USER, DB_PASS);
    } catch (PDOException $exception) {
        echo $exception->getMessage() . "<br>";
        die("<p style='color: red'>خطا در اتصال به پایگاه داده</p>");
    }
    $file = file_get_contents("Province.json");
    $ostan = json_decode($file, true);
    function pr_arr($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre><br>";
        echo "________________";
    }

    $ostan_count = 0;
    $shahr_count = 0;
    foreach ($ostan as $item_ostan) {
        $ostan_name = $item_ostan['name'];
        $sql_ostan = "INSERT INTO `fs_ostan`(`o_name`) VALUES (:_ostan)";
        $prepare1 = $pdo->prepare($sql_ostan);
        $prepare1->bindParam("_ostan", $ostan_name);
        echo "<h1>$ostan_name</h1>";
        $ostan_count++;
        if ($prepare1->execute()) {
            $ostan_id = $pdo->lastInsertId();
            echo $ostan_id . "<br>";
            $sql_shahr = "INSERT INTO `fs_shahr`(`ostan_id`, `sh_name`) VALUES (:_ostanid,:_shahr_name)";
            foreach ($item_ostan as $index => $item_shahr) {
                if (is_array($item_shahr)) {
                    for ($i = 0; $i < count($item_shahr); $i++) {
                        $shahr_name = $item_shahr[$i]['name'];
                        echo $shahr_name . "<br>";
                        $shahr_count++;
                        $prepare2 = $pdo->prepare($sql_shahr);
                        $prepare2->bindParam("_ostanid", $ostan_id);
                        $prepare2->bindParam("_shahr_name", $shahr_name);
                        if ($prepare2->execute()) {
                            echo "ok<br>";
                        }
                    }
                } else {
                    $shahr_name = $item_shahr;
                    echo $shahr_name . "<br>";
                    $shahr_count++;
                    $prepare2 = $pdo->prepare($sql_shahr);
                    $prepare2->bindParam("_ostanid", $ostan_id);
                    $prepare2->bindParam("_shahr_name", $shahr_name);
                    if ($prepare2->execute()) {
                        echo "ok<br>";
                    }
//                echo "<p>===========================</p>";
//                echo $item_shahr."<br>";
//                echo "<p>===========================</p>";
                }
            }
        }

    }
    echo $ostan_count . "<br>";
    echo $shahr_count . "<br>";

}
?>
<a href="?add=132">شروع</a>
</body>
</html>
