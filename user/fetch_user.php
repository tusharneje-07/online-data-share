<?php
require_once './db.php'; // Ensure this file contains the correct database connection setup
header('Content-Type: application/json');

// Read the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if 'userId' and 'name' are provided in the request
if (empty($data['userId']) || empty($data['name'])) {
    echo json_encode([
        "status" => false,
        "message" => "Required parameters 'userId' and 'name' are missing."
    ]);
    exit();
}

$userId = $data['userId'];
$queryName = $data['name'];

// If the name is empty, return an error
if ($queryName == '') {
    echo json_encode([
        "status" => false,
        "message" => "Name cannot be empty."
    ]);
    exit();
}

try {
    // Prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT uid, fullname, dob, education FROM user_information WHERE fullname LIKE ? AND globalsearch = ? AND maritalstatus = ? LIMIT 30");
    $stmt->execute([
        '%'.$queryName.'%',
        1,                   // Assuming 'globalsearch' is a boolean flag (1 means active)
        'UNMARRIED'          // Assuming you're only interested in unmarried users
    ]);

    // Initialize an array to hold the results
    $users = [];

    // Fetch all rows and store them in the array
    while ($row = $stmt->fetch()) {
        $users[] = [
            "uid" => $row['uid'],
            "fullname" => $row['fullname'],
            "dob" => $row['dob'],
            "education" => $row['education']
        ];
    }

    // Check if any users were found
    if (count($users) > 0) {
        $userData = [
            "status" => true,
            "hasrow" => true,
            "users" => $users
        ];
    } else {
        $userData = [
            "status" => true,
            "hasrow" => false,
            "message" => "No matching user found."
        ];
    }

    // Return the user data as JSON response
    echo json_encode($userData);

} catch (PDOException $e) {
    // Handle database connection or query errors
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
