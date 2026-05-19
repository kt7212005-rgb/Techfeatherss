<?php
$db = new PDO('sqlite:' . __DIR__ . '/data/poultry.db');
foreach (['users','products','orders'] as $t) {
    $cols = $db->query("PRAGMA table_info($t)")->fetchAll(PDO::FETCH_ASSOC);
    echo "TABLE $t:\n";
    foreach ($cols as $c) {
        echo $c['cid'] . ' ' . $c['name'] . ' ' . $c['type'] . ' default=' . $c['dflt_value'] . ' notnull=' . $c['notnull'] . ' pk=' . $c['pk'] . "\n";
    }
    echo "\n";
}
