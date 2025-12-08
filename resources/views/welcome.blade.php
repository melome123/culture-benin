<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culture Bénin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for slider -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* animation fade */
        .fade-enter {
            opacity: 0;
        }
        .fade-enter-active {
            opacity: 1;
            transition: opacity 1s;
        }
    </style>
</head>

<body class="bg-black text-white">

    <!-- SLIDER -->
    <div x-data="{ current: 0, images: [
            'https://i.imgur.com/9FbcL89.jpeg',
            'https://i.imgur.com/bo5ehEl.jpeg',
            'https://i.imgur.com/zKXCJqP.jpeg'
        ]}"
        x-init="setInterval(() => { current = (current + 1) % images.length }, 5000)"
        class="relative h-screen w-full overflow-hidden">

        <!-- Images -->
        <template x-for="(img, index) in images">
            <div 
                x-show="current === index"
                x-transition.opacity.duration.1000ms
                class="absolute inset-0 bg-cover bg-center"
                :style="'background-image: url(' + img + ')'">
            </div>
        </template>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Content text -->
        <div class="relative z-10 h-full flex flex-col justify-center items-center px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4 drop-shadow-lg animate-pulse">
                Culture Bénin
            </h1>

            <p class="text-lg md:text-xl max-w-2xl mb-8">
                Plongez dans la richesse des traditions, des arts, des rythmes et de l’histoire d’un pays
                vibrant et culturellement unique.
            </p>

            <div class="flex gap-4">
                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-yellow-400 text-black font-semibold rounded-xl shadow-lg hover:scale-105 transition duration-300">
                    S'inscrire
                </a>

                <a href="{{ route('login') }}"
                   class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition duration-300">
                    Se connecter
                </a>
            </div>
        </div>
    </div>

</body>
</html>
