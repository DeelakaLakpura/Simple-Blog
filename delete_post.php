<?php
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

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Delete the post from the database
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $alertMessage = "Post deleted successfully!";
        $alertType = 'success';
    } else {
        $alertMessage = "Error deleting post: " . $stmt->error;
        $alertType = 'error';
    }

    $stmt->close();
}

// Fetch all posts to display
$result = $conn->query("SELECT * FROM posts");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom styles for responsiveness */
        .image-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-gray-900">Advanced Blog</a>
            <nav class="flex space-x-6">
                <a href="upload.php" class="text-gray-600 hover:text-gray-900">Upload Posts</a>
              
            </nav>
        </div>
    </header>

    <!-- Posts Table -->
    <section class="container mx-auto px-6 py-16">
        <h2 class="text-4xl font-bold text-center mb-12">Manage Posts</h2>
        <div class="bg-white shadow-lg rounded-lg p-8">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">Title</th>
                        <th class="py-2 px-4 border-b">Category</th>
                        <th class="py-2 px-4 border-b">Images</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo $row['id']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['title']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['category']; ?></td>
                                <td class="py-2 px-4 border-b">
                                    <?php $images = explode(',', $row['images']); ?>
                                    <?php foreach ($images as $image): ?>
                                        <img src="<?php echo $image; ?>" class="image-preview" alt="Post Image">
                                    <?php endforeach; ?>
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-2 px-4 border-b text-center">No posts found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- SweetAlert for alerts -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to delete the post
                    window.location.href = 'delete_post.php?delete=' + id;
                }
            });
        }

        // SweetAlert for notifications
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
</body>
</html>
