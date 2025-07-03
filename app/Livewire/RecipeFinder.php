<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RecipeFinder extends Component
{
    public string $ingredients = '';
    public array $recipe = [];
    public bool $loading = false;
    public ?string $error = null;

    public function findRecipe()
    {
        $this->reset(['recipe', 'error']);
        $this->loading = true;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyCXf5fOuwAtQS6POncuEqlz0DCMsTWD1H0', [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $this->buildPrompt(),
                            ],
                        ],
                    ],
                ],
            ]);

            $output = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;

            $this->recipe = $this->extractJson($output) ?? [];
            if (empty($this->recipe)) {
                $this->error = 'Risposta non valida ricevuta.';
            }
        } catch (\Throwable $e) {
            $this->error = 'Errore durante la richiesta: ' . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    private function buildPrompt(): string
    {
        return <<<EOT
Genera una ricetta nel seguente formato JSON:
{
  "title": "Nome della ricetta",
  "ingredients": ["Ingrediente 1", "Ingrediente 2", "..."],
  "instructions": ["Passaggio 1", "Passaggio 2", "..."],
  "prep_time_minutes": 0,
  "cook_time_minutes": 0,
  "total_time_minutes": 0,
  "servings": 0
}
Rispondi solo con JSON. Non usare blocchi markdown o testo extra.

Ingredienti disponibili: {$this->ingredients}
EOT;
    }

    private function extractJson(string $text): ?array
    {
        if (preg_match('/```json\s*(.*?)\s*```/is', $text, $matches)) {
            $clean = $matches[1];
        } elseif (preg_match('/```(.*?)```/is', $text, $matches)) {
            $clean = $matches[1];
        } else {
            $clean = $text;
        }

        return json_decode(trim($clean), true);
    }

    public function anotherRecipe()
    {
        $this->findRecipe();
    }

    public function render()
    {
        return view('livewire.recipe-finder');
    }
}
