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

// Fetch the post details
$post_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
<header style="background-color:#172554" class=" shadow sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-3xl font-bold text-gray-900 flex items-center">
                <img src="https://i.ibb.co/mq0NVsw/hero-logo.png" class="w-50 h-14 mr-2" alt="Logo">
            </a>
           
          
            <button class="block md:hidden text-gray-600 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </header>

    <!-- Post View Section -->
    <section class="container mx-auto px-4 md:px-6 py-16 fade-in">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-6"><?php echo $post['title']; ?></h2>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <?php $images = explode(',', $post['images']); ?>
                <?php foreach ($images as $image): ?>
                    <img class="w-full h-64 object-cover mb-4 transition-transform transform hover:scale-105" src="<?php echo $image; ?>" alt="Post Image">
                <?php endforeach; ?>
                <p class="text-gray-700 mb-4"><?php echo nl2br($post['content']); ?></p>
                <p class="text-gray-500 text-sm">
                    <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                </p>
                <div class="flex items-center mt-4">
                    <a href="#" class="text-blue-500 hover:text-blue-700 transition-colors duration-300"><i class="fas fa-share-alt"></i> Share</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="text-red-500 hover:text-red-700 transition-colors duration-300"><i class="fas fa-heart"></i> Like</a>
                </div>
            </div>
        </div>
    </section>
    <script>
    </script>
</body>
</html>
