<?php
// Ensure the folder exists
$uploadDir = "imgs/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$uid = isset($_POST["uid"]) ? $_POST["uid"] : "unknown";

// Check if file is uploaded
if (isset($_FILES["cropped_image"]) && $_FILES["cropped_image"]["error"] == 0) {
    $fileName = $uid  . ".png"; // Generate unique file name
    $filePath = $uploadDir . $fileName;

    // Move uploaded file to the destination folder
    if (move_uploaded_file($_FILES["cropped_image"]["tmp_name"], $filePath)) {
        echo "Profile Photo Updated Sucessfully.";
    } else {
        echo "Error moving file!";
    }
} else {
    echo "No image uploaded or an error occurred.";
}
?>
