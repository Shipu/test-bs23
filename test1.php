<?php

$connectionParams = array(
    'dbname'   => 'brain-station-23',
    'user'     => 'root',
    'password' => 'root',
    'host'     => '0.0.0.0', // docker host
    'driver'   => 'pdo_mysql',
    'port'     => '33069' // docker port
);

try {

    $conn         = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
    $queryBuilder = $conn->createQueryBuilder();


    $categoryQuery = $queryBuilder->select('c.Name as name', 'COUNt(*) as count')
        ->from('category', 'c')
        ->leftJoin('c', 'Item_category_relations', 'icr', 'c.Id = icr.categoryId')
        ->groupBy('c.Id')
        ->orderBy('count', 'desc');

    $stmt       = $conn->query($categoryQuery->getSQL());
    $categories = $stmt->fetchAll();

} catch ( Exception $exception ) {
    var_dump($exception->getMessage());
}
?>

<table border="1" width="40%">
    <tr>
        <th>Category Name</th>
        <th>Total Items</th>
    </tr>
    <?php
    foreach ( $categories as $category ) {
        echo "<tr>";
        echo "<td>" . $category[ 'name' ] . "</td>";
        echo "<td>" . $category[ 'count' ] . "</td>";
        echo "</tr>";
    }
    ?>
</table>


