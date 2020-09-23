<?php

function getPages($pdo, $offset)
{
    $stmt = $pdo->query('SELECT img_id FROM Photo');
    return ceil($stmt->rowCount() / $offset);
}

function getSortImg($pdo, $type, $limit, $offset)
{
    $sql = 'SELECT img_id, path FROM Photo';
    return $pdo->query($sql . $type . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
}
