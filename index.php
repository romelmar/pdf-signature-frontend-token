<?php
$responseMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = "http://127.0.0.1:8000/api/sign-pdf";

    $zipFilePath = $_FILES['zipfile']['tmp_name'];
    $pdfFilePath = $_FILES['pdf']['tmp_name'];
    // $password = $_POST['password'];
    $password = '0RKrlcxQbpmp';
   

    $storagePath = $_POST['storage_path'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'zipfile' => new CURLFile($zipFilePath, 'application/zip', $_FILES['zipfile']['name']),
        'zipFilePath' => $zipFilePath,

        'pdf' => new CURLFile($pdfFilePath, 'application/pdf', $_FILES['pdf']['name']),
        'password' => $password,
        'storage_path' => $storagePath,
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $responseMessage = 'Error: ' . curl_error($ch);
    } else {
        $responseMessage = 'Response from API: ' . $response;
    }

    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Signature Request</title>
</head>
<body>
    <h1>Sign PDF Document</h1>

    <?php if ($responseMessage): ?>
        <p><strong><?php echo htmlspecialchars($responseMessage); ?></strong></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="zipfile">Upload ZIP File (containing .p12 and .png):</label>
        <input type="file" name="zipfile" id="zipfile" accept=".zip" required><br><br>

        <label for="pdf">Upload PDF File to be Signed:</label>
        <input type="file" name="pdf" id="pdf" accept=".pdf" required><br><br>

        <label for="password">Enter .p12 Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="storage_path">Specify Storage Path:</label>
        <input type="text" name="storage_path" id="storage_path" required><br><br>

        <button type="submit">Sign PDF</button>
    </form>
</body>
</html>
