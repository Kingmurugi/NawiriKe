<?php
/**
 * NawiriKe CRM Report Exporter
 * Streams any admin report as a CSV download.
 * Usage: export_report.php?report=donations
 */

require_once 'authController.php';

requireAdmin();

$slug = $_GET['report'] ?? '';
$report = getReport($conn, $slug);

if ($report === null) {
    http_response_code(404);
    header('Content-Type: text/plain');
    echo 'Unknown report. Available: ' . implode(', ', array_keys(getAvailableReports()));
    exit();
}

$filename = 'nawirike_' . $slug . '_' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store');

$output = fopen('php://output', 'w');

fputcsv($output, array_values($report['columns']));

foreach ($report['rows'] as $row) {
    $line = [];
    foreach (array_keys($report['columns']) as $key) {
        $line[] = $row[$key] ?? '';
    }
    fputcsv($output, $line);
}

fclose($output);
exit();
