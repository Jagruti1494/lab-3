<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Grocery Store</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body class="container mt-4 mb-5">
    <?php
    require_once '../navbar.php';
    require_once '../classes/Sales.php';
    require_once '../classes/FileStorage.php';
    $sales = new Sales();
    $salesData = $sales->getSalesReport();
    ?>

    <div class="container py-4">

        <h2 class="mb-4">Sales Report</h2>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Report Settings</h5>
                <form action="../process/generate_report.php" method="POST" id="reportForm">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="reportPeriod" class="form-label">Report Period:</label>
                            <select class="form-select" id="reportPeriod" name="reportPeriod" required>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="comments" class="form-label">Additional Notes:</label>
                            <textarea class="form-control" id="comments" name="comments" rows="1"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-export me-2"></i> Generate Report
                    </button>
                </form>
            </div>
        </div>

        <div id="reportResults" class="card shadow-sm" style="display: none;">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Report Results</h5>
                    <button type="button" class="btn-close" aria-label="Close" id="closeReport"></button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-primary mb-4">
                    <h6 class="alert-heading">Total Sales</h6>
                    <h3 class="mb-0">$<?php echo number_format($sales->getTotalSales(), 2); ?></h3>
                </div>

                <?php if (empty($salesData)): ?>
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>No sales data available yet. Start by processing some orders!</div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($salesData as $orderId => $order): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($orderId); ?></span></td>
                                        <td><?php echo htmlspecialchars($order['customerName']); ?></td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($order['orderDate']); ?></small></td>
                                        <td class="text-end">$<?php echo number_format($order['totalPrice'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportForm = document.getElementById('reportForm');
            const reportResults = document.getElementById('reportResults');
            const closeReport = document.getElementById('closeReport');

            reportResults.style.display = 'none';

            reportForm.addEventListener('submit', function (e) {
                e.preventDefault();
                reportResults.style.display = 'block';

                reportResults.scrollIntoView({ behavior: 'smooth' });
            });

            closeReport.addEventListener('click', function () {
                reportResults.style.display = 'none';
            });
        });
    </script>
</body>

</html>
