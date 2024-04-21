<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Top-level categories
        $actualites = Category::create(['name' => 'Actualités']);
        $divertissement = Category::create(['name' => 'Divertissement']);
        $technologie = Category::create(['name' => 'Technologie']);
        $sante = Category::create(['name' => 'Santé']);

        // Subcategories for Actualités
        $politique = $actualites->children()->create(['name' => 'Politique']);
        $economie = $actualites->children()->create(['name' => 'Économie']);
        $sport = $actualites->children()->create(['name' => 'Sport']);

        // Subcategories for Divertissement
        $cinema = $divertissement->children()->create(['name' => 'Cinéma']);
        $musique = $divertissement->children()->create(['name' => 'Musique']);
        $sorties = $divertissement->children()->create(['name' => 'Sorties']);

        // Subcategories for Technologie
        $informatique = $technologie->children()->create(['name' => 'Informatique']);
        $ordinateurs = $informatique->children()->create(['name' => 'Ordinateurs de bureau']);
        $portable = $informatique->children()->create(['name' => 'PC portable']);
        $connexion = $informatique->children()->create(['name' => 'Connexion internet']);

        $gadgets = $technologie->children()->create(['name' => 'Gadgets']);
        $smartphones = $gadgets->children()->create(['name' => 'Smartphones']);
        $tablettes = $gadgets->children()->create(['name' => 'Tablettes']);
        $jeuxVideo = $gadgets->children()->create(['name' => 'Jeux vidéo']);

        // Subcategories for Santé
        $medecine = $sante->children()->create(['name' => 'Médecine']);
        $bienetre = $sante->children()->create(['name' => 'Bien-être']);
    }
}