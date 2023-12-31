<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHPiggy</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.rawgit.com/theus/chart.css/v1.0.0/dist/chart.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="/assets/main.css" />
    <link rel="stylesheet" href="/assets/index.css" />

</head>

<body class="bg-indigo-50 font-['Outfit']">
    <div class="flex">
        <!-- <aside class="w-1/4 bg-indigo-900 text-white">
            <ul class="py-4">
                <li><a href="#" class="block px-4 py-2">Sidebar Item 1</a></li>
                <li><a href="#" class="block px-4 py-2">Sidebar Item 2</a></li>
            </ul>
        </aside> -->

        <main class="w-3/4">
            <!-- Start Header -->
            <header class="bg-indigo-900">
                <nav class="mx-auto flex container items-center justify-between py-4" aria-label="Global">
                    <a href="/" class="-m-1.5 p-1.5 text-white text-2xl font-bold">PHPiggy</a>
                    <!-- Navigation Links -->
                    <div class="flex lg:gap-x-10">
                        <a href="/about" class="text-gray-300 hover:text-white transition">About</a>
                        <?php if (isset($_SESSION['user'])) : ?>
                        <a href="/logout" class="text-gray-300 hover:text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 inline-block">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Logout
                        </a>
                        <?php else :?>
                        <a href="/login" class="text-gray-300 hover:text-white transition">Login</a>
                        <a href="/register" class="text-gray-300 hover:text-white transition">Register</a>
                        <?php endif; ?>
                    </div>
                </nav>
            </header>

            <!-- End Header -->