<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response; // Importation de la classe Response de Laravel pour la gestion des réponses HTTP
use Illuminate\Support\Facades\Hash; // Importation de la classe Hash de Laravel pour le hachage de mot de passe

class NewsController extends Controller
{
    /**
     * Ajouter un utilisateur pour les tests.
     */
    public function addUser() // Déclaration de la fonction addUser
    {
        $user = new User();
        $user->name = 'John Doe';
        $user->email = 'ayoubhazar20@gmail.com';
        $user->password = Hash::make('ayoubhazar20');
        $user->save();

        return response()->json($user); // Retourne l'utilisateur ajouté sous forme de réponse JSON
    }

    /**
     * Afficher les dernières nouvelles par ordre décroissant de la date de publication.
     */
    public function latestNews()
    {
        $news = News::whereDate('end_date', '>=', now()) // Récupération des nouvelles dont la date d'expiration est postérieure ou égale à la date actuelle
            ->orderBy('start_date', 'desc') // Tri des nouvelles par date de début de manière décroissante
            ->get(); // Récupération des résultats

        return response()->json($news); // Retourne les nouvelles sous forme de réponse JSON
    }

    /**
     * Rechercher les nouvelles par nom de catégorie.
     */
    public function searchByCategoryName($categoryName) // Déclaration de la fonction searchByCategoryName avec un paramètre $categoryName
    {
        // Recherche des catégories avec des noms contenant le nom partiel fourni
        $categories = Category::where('name', 'LIKE', '%' . $categoryName . '%')->get(); // Recherche des catégories correspondant au nom partiel

        if ($categories->isEmpty()) { // Vérifie si aucune catégorie n'a été trouvée
            return response()->json(['error' => 'Aucune catégorie trouvée correspondant au nom partiel'], 404); // Retourne une réponse JSON avec un message d'erreur
        }

        // Initialiser une collection vide pour stocker toutes les nouvelles
        $allNews = collect(); // Création d'une nouvelle collection

        // Parcourir chaque catégorie trouvée
        foreach ($categories as $category) { // Boucle à travers chaque catégorie
            // Récupérer les nouvelles associées à la catégorie et à ses sous-catégories
            $news = $this->getNewsByCategory($category); // Appel de la fonction getNewsByCategory pour récupérer les nouvelles associées à la catégorie

            // Fusionner les nouvelles dans la collection
            $allNews = $allNews->merge($news); // Fusionne les nouvelles dans la collection principale
        }

        // Filtrer les nouvelles expirées
        $currentDate = now(); // Récupère la date actuelle
        $filteredNews = $allNews->filter(function ($item) use ($currentDate) { // Filtre les nouvelles en fonction de la date actuelle
            return $item->end_date >= $currentDate; // Retourne true si la date d'expiration de la nouvelle est postérieure ou égale à la date actuelle
        });

        return response()->json(['news' => $filteredNews]); // Retourne les nouvelles filtrées sous forme de réponse JSON
    }

    /**
     * Rechercher les nouvelles par ID de catégorie.
     */
    public function searchByCategory(string $categoryId) // Déclaration de la fonction searchByCategory avec un paramètre $categoryId
    {
        try { // Essaie d'exécuter le code suivant
            $category = Category::findOrFail($categoryId); // Récupère la catégorie correspondant à l'ID fourni

            $news = $this->getNewsByCategory($category); // Récupère les nouvelles associées à la catégorie

            return response()->json(['news' => $news]); // Retourne les nouvelles associées à la catégorie sous forme de réponse JSON
        } catch (\Exception $e) { // Attrape toute exception générée
            return response()->json(['error' => 'Catégorie non trouvée']); // Retourne une réponse JSON avec un message d'erreur
        }
    }

    /**
     * Récupérer récursivement toutes les nouvelles associées à la catégorie donnée et à ses sous-catégories.
     */
    private function getNewsByCategory(Category $category): Collection // Déclaration de la fonction privée getNewsByCategory avec un paramètre $category de type Category
    {
        $news = $category->news; // Récupère les nouvelles associées à la catégorie

        foreach ($category->children as $subcategory) { // Boucle à travers les sous-catégories de la catégorie
            $news = $news->merge($this->getNewsByCategory($subcategory)); // Récupère récursivement les nouvelles associées aux sous-catégories et les fusionne avec les nouvelles existantes
        }

        return $news; // Retourne toutes les nouvelles associées à la catégorie et à ses sous-catégories
    }

    /**
     * Afficher la liste des nouvelles.
     */
    public function index() // Déclaration de la fonction index
    {
        $news = News::with(['category:id,name'])->get(); // Récupère toutes les nouvelles avec les noms de catégorie correspondants
        return response()->json($news); // Retourne les nouvelles sous forme de réponse JSON
    }

    /**
     * Stocker une nouvelle dans la base de données.
     */
    public function store(Request $request) // Déclaration de la fonction store avec un paramètre $request de type Request
    {
        $validator = Validator::make($request->all(), [ // Crée un validateur pour valider les données de la requête
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date|date_format:Y/m/d',
            'end_date' => 'required|date|date_format:Y/m/d|after:start_date',
        ]);

        if ($validator->fails()) { // Vérifie si la validation a échoué
            return response()->json(['error' => $validator->errors()], 400); // Retourne une réponse JSON avec les erreurs de validation
        }
        $validatedData = $validator->validated(); // Récupère les données validées par le validateur

        $category = Category::find($validatedData['category_id']); // Récupère la catégorie associée à l'ID fourni

        if (!$category) { // Vérifie si la catégorie n'existe pas
            return response()->json(['error' => 'ID de catégorie invalide.'], 400); // Retourne une réponse JSON avec un message d'erreur
        }

        $news = News::create($validatedData); // Crée une nouvelle dans la base de données avec les données validées

        return response()->json("Données enregistrées avec succès", 200); // Retourne une réponse JSON avec un message de succès
    }

    /**
     * Mettre à jour une nouvelle dans la base de données.
     */
    public function update(Request $request, $id) // Déclaration de la fonction update avec des paramètres $request de type Request et $id
    {
        $validator = Validator::make($request->all(), [ // Crée un validateur pour valider les données de la requête
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date|date_format:Y/m/d',
            'end_date' => 'required|date|date_format:Y/m/d|after:start_date',
        ]);

        if ($validator->fails()) { // Vérifie si la validation a échoué
            return response()->json(['error' => $validator->errors()], 400); // Retourne une réponse JSON avec les erreurs de validation
        }

        $news = News::findOrFail($id); // Récupère la nouvelle correspondant à l'ID fourni

        $validatedData = $validator->validated(); // Récupère les données validées par le validateur

        $category = Category::find($validatedData['category_id']); // Récupère la catégorie associée à l'ID fourni

        if (!$category) { // Vérifie si la catégorie n'existe pas
            return response()->json(['error' => 'ID de catégorie invalide.'], 400); // Retourne une réponse JSON avec un message d'erreur
        }

        $news->update($validatedData); // Met à jour la nouvelle avec les données validées

        return response()->json(['message' => 'Nouvelle mise à jour avec succès', 'data' => $news]); // Retourne une réponse JSON avec un message de succès et les données mises à jour
    }

    /**
     * Supprimer une nouvelle de la base de données.
     */
    public function destroy(string $id) // Déclaration de la fonction destroy avec un paramètre $id
    {
        $news = News::findOrFail($id); // Récupère la nouvelle correspondant à l'ID fourni

        $news->delete(); // Supprime la nouvelle de la base de données

        return response()->json(['message' => 'Nouvelle supprimée avec succès']); // Retourne une réponse JSON avec un message de succès
    }
}