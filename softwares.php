<?php
$SoftwareDir = 'Software/';
if (!is_dir($SoftwareDir)) {
    mkdir($SoftwareDir, 0777, true);
}

// Handle file upload
if (isset($_POST['upload'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetFilePath = $SoftwareDir . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        echo "File uploaded successfully!";
    } else {
        echo "File upload failed.";
    }
}

// Handle file rename
if (isset($_POST['rename'])) {
    $oldName = $SoftwareDir . $_POST['old_name'];
    $newName = $SoftwareDir . $_POST['new_name'];
    if (file_exists($oldName) && rename($oldName, $newName)) {
        echo "File renamed successfully!";
    } else {
        echo "File rename failed.";
    }
}

// Handle file delete
if (isset($_POST['delete'])) {
    $fileToDelete = $SoftwareDir . $_POST['file_name'];
    if (file_exists($fileToDelete) && unlink($fileToDelete)) {
        echo "File deleted successfully!";
    } else {
        echo "File delete failed.";
    }
}

// List uploaded files
$files = array_diff(scandir($SoftwareDir), array('.', '..'));

// Handle search
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
$filteredFiles = array_filter($files, function($file) use ($searchQuery) {
    return stripos($file, $searchQuery) !== false;
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>Operating Softwares</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center">INSTALLER FILES</h1>
    
    <ul class="nav nav-pills nav-fill mb-3 bg-dark p-2">
        <li class="nav-item">
            <a class="nav-link active btn btn-secondary" aria-current="page" href="softwares.php">Softwares</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="drivers.php">Drivers File</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="Opsoftware.php">Operating Softwares</a>
        </li>
    </ul>

    <h2 class="mt-4 text-center">Upload a File</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-3 text-center">
        <input type="file" name="file" class="form-control d-inline w-50" required>
        <button type="submit" name="upload" class="btn btn-primary mt-2">Upload</button>
    </form>

    <h2 class="text-center">Search Files</h2>
    <form method="POST" class="mb-3 text-center">
        <input type="text" name="search" class="form-control d-inline w-50" placeholder="Search files..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>

    <h2 class="text-center">Uploaded Files</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredFiles as $file): ?>
                <tr>
                    <td><a href="<?php echo $SoftwareDir . $file; ?>" target="_blank" class="text-primary"> <?php echo $file; ?> </a></td>
                    <td>
                        <a href="<?php echo $SoftwareDir . $file; ?>" target="_blank" class="btn btn-success btn-sm">View</a>
                        <form method="POST" style="display:inline;" class="d-inline-block">
                            <input type="hidden" name="file_name" value="<?php echo $file; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <form method="POST" style="display:inline;" class="d-inline-block">
                            <input type="hidden" name="old_name" value="<?php echo $file; ?>">
                            <input type="text" name="new_name" class="form-control d-inline-block w-auto" placeholder="New name" required>
                            <button type="submit" name="rename" class="btn btn-warning btn-sm">Rename</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
