<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/search_products.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body class="container mt-3">
    <?php 
    require_once '../navbar.php';
    require_once '../classes/FileStorage.php';
    require_once '../classes/Inventory.php';

    $inventory = new Inventory();
    $products = $inventory->listProducts();

    $searchTerm = filter_input(INPUT_GET, 'searchTerm', FILTER_SANITIZE_STRING) ?? '';
    $filters = filter_input(INPUT_GET, 'filters', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? ['name'];
    $minPrice = filter_input(INPUT_GET, 'minPrice', FILTER_VALIDATE_FLOAT) ?? 0;
    $maxPrice = filter_input(INPUT_GET, 'maxPrice', FILTER_VALIDATE_FLOAT) ?? PHP_FLOAT_MAX;

    if (!is_array($filters)) {
        $filters = ['name'];
    }
    ?>

    <div class="container py-4">
        <h2 class="text-center mb-4">Search Products</h2>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form id="searchForm" method="GET" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="searchTerm" class="form-label">Search Term:</label>
                        <input type="text" class="form-control" id="searchTerm" name="searchTerm" 
                            value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">Filter by:</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="filters[]" value="name" 
                                id="nameFilter" <?php echo in_array('name', $filters) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="nameFilter">Product Name</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="filters[]" value="id" 
                                id="idFilter" <?php echo in_array('id', $filters) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="idFilter">Product ID</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="filters[]" value="price" 
                                id="priceFilter" <?php echo in_array('price', $filters) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="priceFilter">Price Range</label>
                        </div>
                    </div>
                    
                    <div class="mb-3 collapse" id="priceRangeDiv">
                        <label class="form-label">Price Range:</label>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <input type="number" class="form-control" id="minPrice" name="minPrice" 
                                    placeholder="Min Price" step="0.01" value="<?php echo $minPrice; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" id="maxPrice" name="maxPrice" 
                                    placeholder="Max Price" step="0.01" value="<?php echo $maxPrice; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </form>
            </div>
        </div>

        <div id="searchResults">
            <h3 class="text-center mb-4">Search Results</h3>
            <div class="row g-4">
                <?php 
                $filteredProducts = [];
                
                if (!empty($products)) {
                    $filteredProducts = array_filter($products, function($product, $id) use ($searchTerm, $filters, $minPrice, $maxPrice) {
                        foreach ($filters as $filter) {
                            switch ($filter) {
                                case 'name':
                                    if (!empty($searchTerm) && 
                                        stripos($product['name'], $searchTerm) !== false) {
                                        return true;
                                    }
                                    break;
                                  
                                case 'id':
                                    if (!empty($searchTerm) && 
                                        preg_match('/^[0-9]+$/', $searchTerm) && 
                                        $searchTerm === (string)$id) {
                                        return true;
                                    }
                                    break;
                                  
                                case 'price':
                                    $price = floatval($product['price']);
                                    if ($price >= $minPrice && 
                                        ($maxPrice === PHP_FLOAT_MAX || $price <= $maxPrice)) {
                                        return true;
                                    }
                                    break;
                            }
                        }
                        return false;
                    }, ARRAY_FILTER_USE_BOTH);
                }

                if (!empty($filteredProducts)): 
                    foreach ($filteredProducts as $id => $product): 
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card product-card shadow-sm h-100">
                            <div class="card-header custom-header">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <p class="mb-2"><strong>ID:</strong> <?php echo htmlspecialchars($id); ?></p>
                                    <p class="mb-2"><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                                    <p class="mb-2"><strong>Quantity:</strong> <?php echo intval($product['quantity']); ?></p>
                                </div>
                                <?php if (intval($product['quantity']) > 0): ?>
                                    <span class="badge bg-success status-badge">In Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-danger status-badge">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">No products found matching your criteria.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceFilter = document.getElementById('priceFilter');
        const priceRangeDiv = new bootstrap.Collapse(document.getElementById('priceRangeDiv'), {
            toggle: false
        });
        
        function togglePriceRange() {
            if (priceFilter.checked) {
                priceRangeDiv.show();
            } else {
                priceRangeDiv.hide();
            }
        }
        
        priceFilter.addEventListener('change', togglePriceRange);
        togglePriceRange();
        
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const searchTerm = document.getElementById('searchTerm').value;
            if (priceFilter.checked) {
                const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
                const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Number.MAX_SAFE_INTEGER;
                
                if (maxPrice < minPrice) {
                    e.preventDefault();
                    alert('Maximum price cannot be less than minimum price');
                }
            } else if (!searchTerm.trim() && !priceFilter.checked) {
                e.preventDefault();
                alert('Please enter a search term');
            }
        });
    });
    </script>
</body>

</html>
