<?php
$OpsoftwarefileDir = 'OpsoftwareFiles/';
if (!is_dir($OpsoftwarefileDir)) {
    mkdir($OpsoftwarefileDir, 0777, true);
}

// Handle folder and file upload
if (isset($_POST['upload'])) {
    // Check if files are uploaded
    if (!empty($_FILES['file']['name'][0])) {
        $files = $_FILES['file'];
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = basename($files['name'][$i]);
            $targetFilePath = $OpsoftwarefileDir . $fileName;

            // Check if it's a folder or a file
            if (is_dir($files['tmp_name'][$i])) {
                // If it's a folder, we create a directory for it
                $folderName = $OpsoftwarefileDir . $fileName;
                if (!is_dir($folderName)) {
                    mkdir($folderName, 0777, true);
                }
                echo "Folder {$fileName} created successfully!<br>";
            } else {
                // If it's a file, upload it
                if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                    echo "File {$fileName} uploaded successfully!<br>";
                } else {
                    echo "File {$fileName} upload failed.<br>";
                }
            }
        }
    }
}

// List uploaded files and folders
$filesAndFolders = array_diff(scandir($OpsoftwarefileDir), array('.', '..'));

// Handle search
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
$filteredFilesAndFolders = array_filter($filesAndFolders, function($file) use ($searchQuery) {
    return stripos($file, $searchQuery) !== false;
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>Operating Softwares</title>
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
            <a class="nav-link active btn btn-secondary" aria-current="page" href="Opsoftware.php">Operating Softwares</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="drivers.php">Drivers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="softwares.php">Softwares</a>
        </li>
    </ul>

    <h2 class="mt-4 text-center">Upload Files/Folders</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-3 text-center">
        <input type="file" name="file[]" class="form-control d-inline w-50" multiple webkitdirectory required>
        <button type="submit" name="upload" class="btn btn-primary mt-2 bi bi-cloud-upload"> Upload </button>
    </form>

    <h2 class="text-center">Search Files</h2>
    <form method="POST" class="mb-3 text-center">
        <input type="text" name="search" class="form-control d-inline w-50" placeholder="Search files..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="btn btn-primary mt-2 ">Search</button>
    </form>

    <h2 class="text-center">Uploaded Files/Folders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>File/Folder Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredFilesAndFolders as $item): ?>
                <tr>
                    <td>
                        <?php
                        $itemPath = $OpsoftwarefileDir . $item;
                        if (is_dir($itemPath)) {
                            echo "<strong class='text-success'>{$item} (Folder)</strong>";
                        } else {
                            echo "<a href='{$itemPath}' target='_blank' class='text-primary'>{$item}</a>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php if (!is_dir($itemPath)): ?>
                            <a href="<?php echo $itemPath; ?>" target="_blank" class="btn btn-success btn-sm">View</a>
                        <?php endif; ?>
                        <form method="POST" style="display:inline;" class="d-inline-block">
                            <input type="hidden" name="file_name" value="<?php echo $item; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <form method="POST" style="display:inline;" class="d-inline-block">
                            <input type="hidden" name="old_name" value="<?php echo $item; ?>">
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
