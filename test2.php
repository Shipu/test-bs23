<?php

function menuTree($dataset) {
    $tree = array();
    foreach ($dataset as $id=>&$node) {

        if (is_null($node['parent'])) {
            $tree[$id]=&$node;
        } else {
            if (!isset($dataset[$node['parent']]['child'])) {
                $dataset[$node['parent']]['child'] = [];
            }

            if (!isset($dataset[$node['parent']]['count'])) {
                $dataset[$node['parent']]['count'] = 0;
            }

            $dataset[$node['parent']]['child'][$id] = &$node;
            $dataset[$node['parent']]['count'] += $node['count'];
        }

    }

    return $tree;
}

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

    $categoryQuery = "SELECT c.Id as id, c.Name as name, ci.count, cr.ParentCategoryId as parent
FROM category c
LEFT JOIN catetory_relations cr ON c.Id = cr.categoryId
LEFT JOIN (
SELECT c.Id as id, COUNT(*) as count
FROM category c
LEFT JOIN Item_category_relations icr ON c.Id = icr.categoryId
group by c.id order by count desc
) ci on ci.id = c.id";

    $stmt       = $conn->query($categoryQuery);
    $categories = $stmt->fetchAll();

    $dataset = [];
    foreach ($categories as $category) {
        $dataset[$category['id']] = $category;
    }

} catch ( Exception $exception ) {
    var_dump($exception->getMessage());
}

$tree = menuTree($dataset);

echo '<br><br>';
display_menu($tree);

function display_menu($nodes, $indent=0) {
    foreach ($nodes as $node) {
        print str_repeat('&nbsp;',$indent*4);
        print $node['name']." (".$node['count'].")";
        print '<br/>';
        if (isset($node['child']))
            display_menu($node['child'],$indent+1);
    }
}
