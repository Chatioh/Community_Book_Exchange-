<?php
/**
 * Open Library API Integration
 * Module 5: Asynchronous Interactions & Client-Side Dynamics
 * Fetches book cover images from Open Library API
 */

header('Content-Type: application/json');

// Get ISBN from request
$isbn = isset($_GET['isbn']) ? trim($_GET['isbn']) : '';

if (empty($isbn)) {
    echo json_encode([
        'success' => false,
        'error' => 'ISBN is required'
    ]);
    exit();
}

try {
    // Open Library API endpoint
    $apiUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
    
    // Make API request
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'user_agent' => 'BookExchange/1.0'
        ]
    ]);
    
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response === false) {
        throw new Exception('Failed to fetch data from Open Library API');
    }
    
    $data = json_decode($response, true);
    
    // Extract book info
    $bookKey = "ISBN:{$isbn}";
    
    if (isset($data[$bookKey])) {
        $bookInfo = $data[$bookKey];
        
        // Build response
        $result = [
            'success' => true,
            'isbn' => $isbn,
            'title' => $bookInfo['title'] ?? null,
            'authors' => $bookInfo['authors'] ?? [],
            'publish_date' => $bookInfo['publish_date'] ?? null,
            'publishers' => $bookInfo['publishers'] ?? [],
            'cover' => [
                'small' => $bookInfo['cover']['small'] ?? null,
                'medium' => $bookInfo['cover']['medium'] ?? null,
                'large' => $bookInfo['cover']['large'] ?? null
            ],
            'url' => $bookInfo['url'] ?? null
        ];
        
        echo json_encode($result);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Book not found in Open Library',
            'isbn' => $isbn
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'API request failed',
        'message' => $e->getMessage()
    ]);
}
?>
