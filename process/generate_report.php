<?php
require_once '../classes/Sales.php';
require_once '../classes/Filetorage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $period = isset($_POST['reportPeriod']) ? htmlspecialchars($_POST['reportPeriod']) : null;
    $comments = isset($_POST['comments']) ? htmlspecialchars($_POST['comments']) : '';

    // Validate inputs (Optional but recommended)
    if ($period === null || empty($period)) {
        echo json_encode(['error' => 'Invalid report period']);
        exit;
    }

    // Create sales object and get report
    $sales = new Sales();

    // Assuming `getSalesReport()` method accepts the period and returns data
    try {
        $report = $sales->getSalesReport($period); // Pass the period as an argument if necessary

        // Check if the report was successfully fetched
        if ($report) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'report' => $report]);
        } else {
            echo json_encode(['error' => 'No data found for the specified period']);
        }
    } catch (Exception $e) {
        // Handle any errors with fetching the report
        echo json_encode(['error' => 'An error occurred while fetching the report: ' . $e->getMessage()]);
    }
    exit;
}
?>
