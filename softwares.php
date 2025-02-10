<?php
$SoftwareDir = 'SoftwareFile/';
if (!is_dir($SoftwareDir)) {
    mkdir($SoftwareDir, 0777, true);
}

// Handle file upload
if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetFilePath = $SoftwareDir . DIRECTORY_SEPARATOR . $fileName;

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

    if (file_exists($oldName)) {
        if (rename($oldName, $newName)) {
            echo "File renamed successfully!";
        } else {
            echo "File rename failed.";
        }
    } else {
        echo "File not found.";
    }
}

// Handle file delete
if (isset($_POST['delete'])) {
    $fileToDelete = $SoftwareDir . $_POST['file_name'];
    
    if (file_exists($fileToDelete)) {
        if (unlink($fileToDelete)) {
            echo "File deleted successfully!";
        } else {
            echo "File delete failed.";
        }
    } else {
        echo "File not found.";
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
    <title>Softwares</title>
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
    <ul class="nav nav-pills nav-fill nav-tabs mb-3 bg-dark p-2">
        <li class="nav-item">
            <a class="nav-link active btn btn-secondary" href="softwares.php">Softwares</a>
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
        <button type="submit" name="upload" class="btn btn-primary mt-2 bi bi-cloud-upload"> Upload </button>
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
                <?php 
                $filePath = $SoftwareDir . $file;
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                ?>
                <tr>
                    <td><a href="<?php echo $filePath; ?>" target="_blank" class="text-primary"> <?php echo $file; ?> </a></td>
                    <td>
                        <?php if (strtolower($fileExtension) == 'mp4'): ?>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal<?php echo md5($file); ?>">
                                View
                            </button>

                            <div class="modal fade" id="videoModal<?php echo md5($file); ?>" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Playing: <?php echo $file; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <?php if (file_exists($filePath)): ?>
                                                <video controls width="100%">
                                                    <source src="<?php echo $filePath; ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            <?php else: ?>
                                                <p class="text-danger">Error: Video file not found.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-success btn-sm">View</a>
                        <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
