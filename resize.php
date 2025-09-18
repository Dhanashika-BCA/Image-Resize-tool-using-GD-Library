<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $width = intval($_POST['width']);
    $height = intval($_POST['height']);
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Create image resource from upload
    switch ($fileExt) {
        case 'jpg':
        case 'jpeg':
            $srcImage = imagecreatefromjpeg($fileTmpPath);
            break;
        case 'png':
            $srcImage = imagecreatefrompng($fileTmpPath);
            break;
        case 'gif':
            $srcImage = imagecreatefromgif($fileTmpPath);
            break;
        default:
            echo json_encode([
                "status" => "error",
                "message" =>  "Unsupported image type. Please upload JPG, PNG, or GIF."
            ]);
            exit;
    }

    // Get original size
    $origWidth = imagesx($srcImage);
    $origHeight = imagesy($srcImage);

    // Create new resized image
    $dstImage = imagecreatetruecolor($width, $height);

    // Preserve transparency for PNG/GIF
    if ($fileExt === 'png' || $fileExt === 'gif') {
        imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
    }

    // Resize
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    // Save resized image to a new file
    $outputFile = "resized_" . time() . "." . $fileExt;
    $outputPath = __DIR__ . "/" . $outputFile;

    switch ($fileExt) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($dstImage, $outputPath, 90);
            break;
        case 'png':
            imagepng($dstImage, $outputPath);
            break;
        case 'gif':
            imagegif($dstImage, $outputPath);
            break;
    }

    // Clean up
    imagedestroy($srcImage);
    imagedestroy($dstImage);

    // Return the new image path (for AJAX)
    echo json_encode([
        "status" => "success",
        "file" => $outputFile
    ]);
    exit;
}
?>
