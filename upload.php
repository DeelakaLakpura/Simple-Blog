
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for SweetAlert messages
$alertMessage = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $imageUploadDir = 'uploads/';
    $imagePaths = [];

    // Handle image uploads
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] == UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['images']['name'][$key]);
            $target_file = $imageUploadDir . uniqid() . '_' . $file_name; // Unique file name

            if (move_uploaded_file($tmp_name, $target_file)) {
                $imagePaths[] = $target_file; // Save the uploaded file path
            } else {
                $alertMessage = "Error uploading image: $file_name";
                $alertType = 'error';
            }
        } else {
            $alertMessage = "Error with file upload: " . $_FILES['images']['error'][$key];
            $alertType = 'error';
        }
    }

    // Convert image paths array to a string
    if (!empty($imagePaths)) {
        $imagePathsString = implode(',', $imagePaths);

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO posts (title, content, category, images) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $category, $imagePathsString);

        if ($stmt->execute()) {
            $alertMessage = "Post uploaded successfully!";
            $alertType = 'success';
        } else {
            $alertMessage = "Error: " . $stmt->error;
            $alertType = 'error';
        }

        $stmt->close();
    } else {
        $alertMessage = "No images were uploaded.";
        $alertType = 'warning';
    }
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Post</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom animations for buttons and inputs */
        .hover-animate {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-animate:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        /* File input customization */
        .custom-file-upload {
            display: inline-block;
            cursor: pointer;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .custom-file-upload:hover {
            background-color: #2563eb;
        }
        .file-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .file-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-gray-900 flex items-center">
                <img src="https://via.placeholder.com/40" class="w-10 h-10 mr-2" alt="Logo">
                Advanced Blog
            </a>
            <nav class="flex space-x-6">
                <a href="delete_post.php" class="text-gray-600 hover:text-gray-900">Delete</a>
               
            </nav>
        </div>
    </header>

    <!-- Upload Post Form -->
    <section class="container mx-auto px-6 py-16">
        <h2 class="text-4xl font-bold text-center mb-12">Upload News Post</h2>
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto">
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Post Title -->
                <div class="mb-6">
                    <label for="title" class="block text-lg font-semibold mb-2">Title</label>
                    <input type="text" id="title" name="title" class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter post title" required>
                </div>

                <!-- Post Content -->
                <div class="mb-6">
                    <label for="content" class="block text-lg font-semibold mb-2">Content</label>
                    <textarea id="content" name="content" rows="5" class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write your post content here" required></textarea>
                </div>

                <!-- Upload Images -->
                <div class="mb-6">
                    <label class="block text-lg font-semibold mb-2">Upload Images</label>
                    <label class="custom-file-upload hover-animate">
                        <input type="file" id="imageUpload" name="images[]" accept="image/*"  class="hidden" required>
                        <i class="fas fa-cloud-upload-alt"></i> Choose Images
                    </label>
                    <div id="imagePreview" class="file-preview mt-4"></div>
                </div>

                <!-- Category Dropdown -->
                <div class="mb-6">
                    <label for="category" class="block text-lg font-semibold mb-2">Category</label>
                    <select id="category" name="category" class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="Technology">Technology</option>
                        <option value="Lifestyle">Lifestyle</option>
                        <option value="Business">Business</option>
                        <option value="Travel">Travel</option>
                    </select>
                </div>

                <!-- Publish Button -->
                <div class="text-center">
                    <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-full hover-animate">Publish Post</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10">
        <div class="container mx-auto px-6 text-center">
            <div class="flex justify-center space-x-6 mb-6">
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-linkedin"></i></a>
            </div>
            <p class="text-sm">© 2024 Advanced Blog. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Script for handling image preview
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');

        imageUpload.addEventListener('change', function() {
            imagePreview.innerHTML = '';
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    imagePreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });

        // SweetAlert for alerts
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($alertMessage): ?>
                Swal.fire({
                    icon: '<?php echo $alertType; ?>',
                    title: '<?php echo $alertMessage; ?>',
                    showConfirmButton: true,
                });
            <?php endif; ?>
        });

        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($alertMessage): ?>
                Swal.fire({
                    icon: '<?php echo $alertType; ?>',
                    title: '<?php echo $alertMessage; ?>',
                    showConfirmButton: true,
                });
            <?php endif; ?>
        });
    </script>

    </script>
    
</body>
</html>
