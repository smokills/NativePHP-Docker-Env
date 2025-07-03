<div class="max-w-2xl mx-auto p-6 space-y-6">
    <h1 class="text-3xl font-bold text-center text-gray-800">ChefGPT</h1>

    <div class="space-y-4">
        <input
            type="text"
            wire:model.defer="ingredients"
            class="w-full p-3 border border-gray-300 rounded-xl focus:ring focus:ring-indigo-300 focus:outline-none"
            placeholder="Es. uova, zucchine, parmigiano"
        />

        <button
            wire:click="findRecipe"
            wire:loading.attr="disabled"
            class="w-full bg-indigo-600 text-white py-3 rounded-xl text-lg font-semibold transition hover:bg-indigo-700 disabled:opacity-50"
        >
            <span wire:loading.remove>Trova Ricetta</span>
            <span wire:loading>
                <svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </span>
        </button>
    </div>

    @if ($error)
        <div class="bg-red-100 text-red-800 p-4 rounded-xl">
            {{ $error }}
        </div>
    @endif

    @if ($recipe)
        <div class="bg-white p-6 rounded-xl shadow space-y-4 animate-fade-in">
            <h2 class="text-2xl font-bold text-indigo-700">{{ $recipe['title'] }}</h2>

            <div>
                <h3 class="font-semibold text-gray-700">Ingredienti</h3>
                <ul class="list-disc list-inside text-gray-800">
                    @foreach ($recipe['ingredients'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-gray-700">Istruzioni</h3>
                <ol class="list-decimal list-inside text-gray-800 space-y-1">
                    @foreach ($recipe['instructions'] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </div>

            <div class="text-sm text-gray-500">
                Tempo: {{ $recipe['total_time_minutes'] }} min • Porzioni: {{ $recipe['servings'] }}
            </div>
        </div>
    @endif
</div>
