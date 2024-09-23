<?php

$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT * FROM posts");
$conn->close();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css">
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


    <section class="relative h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        
        <div class="relative text-center fade-in px-4">
            <h1 class="text-4xl md:text-6xl font-extrabold">Welcome to Advanced Blog</h1>
            <p class="text-lg md:text-xl mt-4">A space to share ideas, stories, and creativity.</p>
            <a href="#" class="mt-8 inline-block px-6 py-3 bg-blue-500 text-white rounded-full hover-animate">Explore Now</a>
        </div>
    </section>


    <section class="container mx-auto px-4 md:px-6 py-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Latest Posts</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover-animate">
                        <?php $images = explode(',', $row['images']); ?>
                        <img class="w-full h-48 object-cover" src="<?php echo $images[0]; ?>" alt="Blog Post">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2"><?php echo $row['title']; ?></h3>
                            <?php
                            $content = $row['content'];
                            $truncatedContent = implode(' ', array_slice(explode(' ', $content), 0, 5));
?>
                            <p class="text-gray-700 mb-4"><?php echo $truncatedContent; ?>...</p>

                            <div class="flex justify-between items-center">
                            <a href="view_post.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Read More</a>
                            <span class="text-gray-500 text-sm"><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-gray-700">No posts found.</p>
            <?php endif; ?>
        </div>
    </section>



    <section class="bg-gray-800 text-white py-16 relative">
        <svg class="absolute left-0 top-0 w-full h-48" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 1440 320">
            <path fill="#fff" fill-opacity="0.1" d="M0,160L48,160C96,160,192,160,288,170.7C384,181,480,203,576,213.3C672,224,768,224,864,197.3C960,171,1056,117,1152,112C1248,107,1344,149,1392,165.3L1440,181.3L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
        </svg>
        <div class="relative container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-8">Join the Community</h2>
            <p class="text-center mb-12">Subscribe to get the latest updates, articles, and insights straight to your inbox.</p>
            <div class="flex justify-center">
                <input type="email" placeholder="Enter your email" class="px-4 py-2 rounded-l-full bg-white text-gray-800 focus:outline-none w-2/3 sm:w-1/2 md:w-1/3">
                <button class="px-6 py-2 bg-blue-500 text-white rounded-r-full hover:bg-blue-600">Subscribe</button>
            </div>
        </div>
    </section>


    <footer class="bg-gray-900 text-white py-10">
        <div class="container mx-auto px-4 text-center">
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
        document.querySelectorAll('.hover-animate').forEach(element => {
            element.addEventListener('mouseover', () => {
                element.classList.add('hover-animate');
            });
        });
    </script>
</body>
</html>
