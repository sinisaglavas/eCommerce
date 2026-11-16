<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** @var array<string, array{original: string, translated: string}> */
    private const array DESCRIPTIONS = [
        'laptops' => [
            'original' => 'Prenosivi računari za posao, studije i kreativni rad.',
            'translated' => 'Laptops for work, study, and creative projects.',
        ],
        'smartphones' => [
            'original' => 'Telefoni sa dugotrajnom baterijom i jasnim ekranom.',
            'translated' => 'Phones with long battery life and clear displays.',
        ],
        'tvs' => [
            'original' => 'Televizori za filmove, sport i kućnu zabavu.',
            'translated' => 'TVs for movies, sports, and home entertainment.',
        ],
        'monitors' => [
            'original' => 'Monitori za kancelariju, dizajn i igre.',
            'translated' => 'Monitors for office work, design, and gaming.',
        ],
        'tablets' => [
            'original' => 'Tableti za učenje, crtanje i zabavu.',
            'translated' => 'Tablets for learning, drawing, and entertainment.',
        ],
        'smartwatches' => [
            'original' => 'Pametni satovi za svakodnevne aktivnosti.',
            'translated' => 'Smartwatches for everyday activities.',
        ],
    ];

    public function up(): void
    {
        foreach (self::DESCRIPTIONS as $slug => $description) {
            DB::table('categories')
                ->where('slug', $slug)
                ->where('description', $description['original'])
                ->update(['description' => $description['translated']]);
        }
    }

    public function down(): void
    {
        foreach (self::DESCRIPTIONS as $slug => $description) {
            DB::table('categories')
                ->where('slug', $slug)
                ->where('description', $description['translated'])
                ->update(['description' => $description['original']]);
        }
    }
};
