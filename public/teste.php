<?php
echo json_encode([
    'memory_current' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
    'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
]);
?>