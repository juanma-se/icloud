<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'relevance' => fake()->randomElement(['alta', 'media', 'baja']),
            'approval_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'upload_date' => fake()->dateTimeBetween('-1 years', 'now'),
            'pdf_path' => 'documents/' . Str::random(10) . '.pdf',
        ];
    }
}
