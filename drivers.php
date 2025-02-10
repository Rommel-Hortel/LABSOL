<?php
$driversfileDir = 'DriversFile/';
if (!is_dir($driversfileDir)) {
    mkdir($driversfileDir, 0777, true);
}

// Handle file upload
if (isset($_POST['upload'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetFilePath = $driversfileDir . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        echo "File uploaded successfully!";
    } else {
        echo "File upload failed.";
    }
}

// Handle file rename
if (isset($_POST['rename'])) {
    $oldName = $driversfileDir . $_POST['old_name'];
    $newName = $driversfileDir . $_POST['new_name'];
    if (file_exists($oldName) && rename($oldName, $newName)) {
        echo "File renamed successfully!";
    } else {
        echo "File rename failed.";
    }
}

// Handle file delete
if (isset($_POST['delete'])) {
    $fileToDelete = $driversfileDir . $_POST['file_name'];
    if (file_exists($fileToDelete) && unlink($fileToDelete)) {
        echo "File deleted successfully!";
    } else {
        echo "File delete failed.";
    }
}

// List uploaded files
$files = array_diff(scandir($driversfileDir), array('.', '..'));

// Handle search
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
$filteredFiles = array_filter($files, function($file) use ($searchQuery) {
    return stripos($file, $searchQuery) !== false;
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>Drivers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center">INSTALLER FILES</h1>
        <div class="text-center my-3">
            <a href="home.php" class="btn btn-primary">
                <i class="bi bi-house-door"></i> Home
            </a>
        </div>
            <ul class="nav nav-pills nav-fill nav-tabs id = myTab role= tablist mb-3 bg-dark p-2">
                <li class="nav-item">
                    <a class="nav-link active btn btn-secondary" aria-current="page" href="drivers.php">Drivers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="Opsoftware.php">Operating Softwares</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="softwares.php">Softwares</a>
                </li>
            </ul>

    <h2 class="mt-4 text-center">Upload a File</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-3 text-center">
        <input type="file" name="file" class="form-control d-inline w-50" required>
        <button type="submit" name="upload" class="btn btn-primary mt-2 bi bi-cloud-upload"> Upload </button>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
       
    </form>

    <h2 class="text-center">Search Files</h2>
    <form method="POST" class="mb-3 text-center">
        <input type="text" name="search" class="form-control d-inline w-50" placeholder="Search files..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="btn btn-primary mt-2 ">Search</button>
        

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
                    <td><a href="<?php echo $driversfileDir . $file; ?>" target="_blank" class="text-primary"> <?php echo $file; ?> </a></td>
                    <td>
                        <a href="<?php echo $driversfileDir . $file; ?>" target="_blank" class="btn btn-success btn-sm">View</a>
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
