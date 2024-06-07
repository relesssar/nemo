<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css'>
    <style type="text/tailwindcss">

    </style>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
<div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">

                @if (Route::has('login'))
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <div class="text-center place-content-center ">
                <span id="name_ru" class=" text-4xl">&nbsp;</span>
                <span id="name_en" class=" text-3xl text-grey-800">&nbsp;</span>
                <span id="iata" class="text-1xl text-grey-500">&nbsp;</span>
            </div>

            <div class="relative mx-auto w-96 mt-10">
                <input
                    id="autocomplete-input"
                    type="text"
                    name="query"
                    placeholder="Search..."
                    class="w-full border border-gray-300 p-2 rounded-md"
                >
                <div id="autocomplete-results" class="absolute left-0 right-0 mt-1 max-h-60 w-full overflow-auto border border-gray-300 bg-white rounded-md shadow-lg z-10 hidden">
                    <!-- Autocomplete suggestions will appear here -->
                </div>
            </div>

            <footer class="py-16 text-center text-sm text-black dark:text-white/70">

            </footer>
        </div>
    </div>
</div>

<script>
    document.getElementById('autocomplete-input').addEventListener('input', function() {
        const inputValue = this.value;
        const resultsContainer = document.getElementById('autocomplete-results');

        if (inputValue.length < 2) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('hidden');
            return;
        }

        // Tailwind-specific show/hide method using 'hidden' class
        resultsContainer.classList.remove('hidden');

        fetchSuggestions(inputValue);
    });

    function fetchSuggestions(query) {
        // Example endpoint
        const endpointUrl = `get-airport?q=${encodeURIComponent(query)}`;

        fetch(endpointUrl,{
            method: "post",
        })
            .then(response => response.json())
            .then(suggestions => {
                const resultsContainer = document.getElementById('autocomplete-results');
                resultsContainer.innerHTML = ''; // Clear previous suggestions

                if (Object.keys(suggestions).length === 0) {
                    console.log('no data');
                    document.getElementById('name_ru').innerHTML ="";
                    document.getElementById('name_en').innerHTML = "нет данных";
                    document.getElementById('iata').innerHTML ="";
                } else {
                    //console.log(suggestions);
                    suggestions.forEach(item => {
                        //console.log(item);
                        const div = document.createElement('div');
                        div.textContent = item['name_ru']; // Replace with the appropriate property if the suggestions contain objects
                        div.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer'); // Tailwind classes
                        div.onclick = () => {
                            // console.log(item);
                            document.getElementById('autocomplete-input').value = item['name_ru']; // Replace with item.property if needed
                            document.getElementById('name_ru').innerHTML =item['name_ru'];
                            document.getElementById('name_en').innerHTML = "(" + item['name_en'] + ")";
                            document.getElementById('iata').innerHTML =item['iata'];
                            resultsContainer.innerHTML = '';
                            resultsContainer.classList.add('hidden');
                        };
                        resultsContainer.appendChild(div);
                    });
                }


            })
            .catch(error => console.error('Fetching suggestions error:', error));
    }

</script>
</body>

<script src='https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.9.1/cdn.js'></script>
</html>
