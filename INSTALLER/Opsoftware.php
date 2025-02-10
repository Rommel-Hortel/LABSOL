<?php
include("../connection.php");

$opSoftwareDir = '../opSoftwares/';
if (!is_dir($opSoftwareDir)) {
    mkdir($opSoftwareDir, 0777, true);
}

    if (isset($_POST['upload'])) {
    if (!empty($_FILES['file']['name'][0])) {
        $files = $_FILES['file'];
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = basename($files['name'][$i]);
            $targetFilePath = $opSoftwareDir . $fileName;

            if (strpos($fileName, '..') !== false) {
                echo "Invalid file/folder name detected: $fileName <br>";
                continue;
            }

            if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                $stmt = $conn->prepare("INSERT INTO files (file_name, file_path) VALUES (?, ?)");
                $stmt->bind_param("ss", $fileName, $targetFilePath);
                if ($stmt->execute()) {
                    echo "File '{$fileName}' uploaded successfully!<br>";
                } else {
                    echo "Database error: " . $stmt->error . "<br>";
                }
                $stmt->close();
            } else {
                echo "File '{$fileName}' upload failed.<br>";
            }
        }
    }
}

if (isset($_POST['delete']) && isset($_POST['file_name'])) {
    $fileToDelete = $opSoftwareDir . $_POST['file_name'];

    // Database deletion
    $stmt = $conn->prepare("DELETE FROM files WHERE file_name = ?");
    $stmt->bind_param("s", $_POST['file_name']);
    $stmt->execute();
    $stmt->close();

    // Check and delete the file/folder from the server
    if (is_dir($fileToDelete)) {
        array_map('unlink', glob("$fileToDelete/*.*"));
        rmdir($fileToDelete);
        echo "Folder deleted successfully!";
    } elseif (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        echo "File deleted successfully!";
    } else {
        echo "Error: File/Folder not found!";
    }
}

// Handle file/folder renaming
if (isset($_POST['rename']) && isset($_POST['old_name']) && isset($_POST['new_name'])) {
    $oldPath = $opSoftwareDir . $_POST['old_name'];
    $newPath = $opSoftwareDir . $_POST['new_name'];

    if (!file_exists($newPath)) {
        rename($oldPath, $newPath);
        echo "Renamed successfully!";
    } else {
        echo "Error: A file/folder with this name already exists.";
    }
}

// List uploaded files and folders
$filesAndFolders = array_diff(scandir($opSoftwareDir), array('.', '..'));

// Handle search
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
$filteredFilesAndFolders = array_filter($filesAndFolders, function($file) use ($searchQuery) {
    return stripos($file, $searchQuery) !== false;
});



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operating Softwares Files</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FONT ICONS --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    
    <!-- LOGO ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body { 
            background: linear-gradient(to bottom, #1f95dd, #92d6fd, #e4f4fd); color: white; font-family: 'Montserrat', 'Inter'; 
        }
        
        .h2, h3 { 
            font-family: 'Montserrat', 'Inter'; 
        }
        
        .navbar { 
            background: #1f95dd; 
        }

        .container-box { 
            background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
        }
        .btn-custom { 
            border-radius: 8px; transition: 0.3s; 
        }
        
        .btn-custom:hover {
            opacity: 0.8; 
        }
        .table { 
            color: black; 
        }
        .table th { 
            background: #1f95dd; color: white; 
        }

        .footer { 
            background: #1f95dd; color: white; padding: 10px 0; text-align: center; margin-top: 40px; 
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-light" href="../home.php"><i class="bi bi-house-door"></i> Home</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="container-box">
        <h2 class="text-center text-dark">INSTALLER FILES</h2>
        <div class="text-center my-3">
        </div>

        <ul class="nav nav-pills nav-fill bg-success p-2 rounded">
            <li class="nav-item">
                <a class="nav-link text-light" href="softwares.php">Softwares</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active btn btn-secondary shadow-lg" href="Opsoftware.php">Operating Softwares</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="drivers.php">Drivers</a>
            </li>
        </ul>

        <h3 class="mt-5 text-dark text-center">Upload Files</h3>
        <form method="POST" enctype="multipart/form-data" class="mb-3 text-center">
            <input type="file" name="file[]" class="form-control d-inline w-50" multiple required>
            <button type="submit" name="upload" class="btn btn-primary btn-custom">
                <i class="bi bi-cloud-upload"></i> Upload
            </button>
        </form>

        <h3 class="text-dark text-center">Search Files</h3>
        <form method="POST" class="mb-3 text-center">
            <input type="text" name="search" class="form-control d-inline w-50" placeholder="Search files..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="btn btn-primary btn-custom"><i class="bi bi-search"></i> Search</button>
        </form>

        <h3 class="text-dark text-center">Uploaded Files/Folders</h3>
        <table class="table table-striped table-hover">
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
                            $itemPath = $opSoftwareDir . $item;
                            if (is_dir($itemPath)) {
                                echo "<strong class='text-success'>{$item} (Folder)</strong>";
                            } else {
                                echo "<a href='{$itemPath}' target='_blank' class='text-primary'>{$item}</a>";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo $itemPath; ?>" target="_blank" class="btn btn-success btn-sm btn-custom">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="file_name" value="<?php echo $item; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm btn-custom">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
        <p class="mb-0 ">LABSOLUTION INC. &copy; 2025</p>
        <div class="social-icons">
            <a href="https://www.facebook.com/labsolutiontechnologiesinc" target="_blank"><i class="fab fa-facebook text-white mx-2 fa-lg"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter text-white mx-2 fa-lg"></i></a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram text-white mx-2 fa-lg"></i></a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>

