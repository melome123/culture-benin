<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Culture Bénin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white px-6 py-4 shadow flex justify-between items-center">
        <div class="text-2xl font-bold">Culture Bénin</div>

        <ul class="flex items-center gap-6">

            @auth
                @if(auth()->user()->role_id === 1)
                    <li>
                        <a class="text-gray-700 hover:text-black text-lg" href="{{ url('/admin/dashboard') }}">Admin</a>
                    </li>
                @endif

                @if(auth()->user()->role_id === 2)
                    <li>
                        <a class="text-gray-700 hover:text-black text-lg" href="{{ url('/moderation') }}">Modération</a>
                    </li>
                @endif
            @endauth

            <!-- Logout + Contribute -->
            <li>
                <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                    @csrf
                    <button type="submit"
                        class="border border-blue-500 text-blue-500 py-2 px-4 rounded hover:bg-blue-50 transition">
                        Logout
                    </button>

                    <a id="btn-contribute"
                       class="ml-3 bg-blue-600 text-white py-2 px-4 rounded cursor-pointer hover:bg-blue-700 transition">
                        Contribute
                    </a>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Slider Section -->
    <div class="container mx-auto mt-10 px-6"
         x-data="{ current: 0 }">

        <h2 class="text-3xl font-bold mb-6 text-gray-800">Nos Contenus</h2>

        <!-- Slider Wrapper -->
        <div class="relative overflow-hidden rounded-xl shadow-lg bg-white">

            <!-- Slides -->
            <div class="flex transition-all duration-700"
                 :style="'transform: translateX(-' + current * 100 + '%)'">

                @foreach($contenus as $contenu)
                    <div class="min-w-full p-10 flex items-center justify-center bg-gray-50">

                        <!-- Card -->
                        <div class="bg-white p-6 rounded-xl shadow-md w-full max-w-lg">

                            <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                {{ $contenu->titre }}
                            </h3>

                            <p class="text-gray-600 mb-4">
                                {{ Str::limit($contenu->description, 120) }}
                            </p>

                            <p class="text-sm text-gray-500 mb-4">
                                Auteur :
                                <span class="font-semibold text-gray-800">
                                    {{ $contenu->user->name ?? 'Inconnu' }}
                                </span>
                            </p>

                            <a href="{{ url('/contenu/'.$contenu->id) }}"
                               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                Voir plus
                            </a>

                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Controls -->
            <div class="absolute inset-0 flex justify-between items-center px-4">
                <button @click="current = (current - 1 + {{ count($contenus) }}) % {{ count($contenus) }}"
                        class="bg-gray-800 text-white p-3 rounded-full hover:bg-black">
                    ‹
                </button>

                <button @click="current = (current + 1) % {{ count($contenus) }}"
                        class="bg-gray-800 text-white p-3 rounded-full hover:bg-black">
                    ›
                </button>
            </div>
        </div>

        <!-- Indicators -->
        <div class="flex justify-center mt-4 space-x-2">
            @for($i = 0; $i < count($contenus); $i++)
                <div @click="current = {{ $i }}"
                     class="w-4 h-4 rounded-full cursor-pointer"
                     :class="current === {{ $i }} ? 'bg-blue-600' : 'bg-gray-400'">
                </div>
            @endfor
        </div>
    </div>

    <!-- Script Contribution -->
    <script>
        document.querySelector('#btn-contribute').addEventListener('click', function() {
            fetch('/demande', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: {{ auth()->id() }} })
            })
            .then(r => r.json())
            .then(data => {
                window.location.href = '/admin/demandes';
            });
        });
    </script>

</body>
</html>
