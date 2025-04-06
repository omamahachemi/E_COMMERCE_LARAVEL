<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }
    public function indexuser()
    {
        $products = Product::all();
        return view('home', compact('products'));
    }
    public function showuser()
    {
        $products = Product::orderBy('created_at', 'desc')->take(3)->get(); // Récupère les 3 derniers produits
        return view('home', compact('products'));
    }
    /**
     * Afficher le formulaire de création d'un produit.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|string', // Champ optionnel pour l'URL de l'image
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher les détails d'un produit.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $product = Product::find($id);
        // dd($product);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Afficher le formulaire de modification d'un produit.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Mettre à jour un produit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprimer un produit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Générer une description avec l'API Gemini.
     */
    public function generateDescription(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $apiKey = env('GEMINI_API_KEY'); // Assurez-vous d'avoir GEMINI_API_KEY dans votre .env
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Générer une description concise et attrayante d'un livre à partir de son titre. (300 characteres max): " . $request->name],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Extraire la description de la réponse
                $description = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($description) {
                    return response()->json([
                        'description' => $description,
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Aucune description générée.',
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la génération de la description : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Générer une image avec l'API Hugging Face.
     */
    public function generateImage(Request $request)
    {
        set_time_limit(120); // Augmente la limite à 120 secondes

        $request->validate([
            'description' => 'required|string',
        ]);

        $apiKey = env('HUGGINGFACE_API_KEY'); // Utilisez votre clé API
        $url = "https://router.huggingface.co/hf-inference/models/black-forest-labs/FLUX.1-dev";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'inputs' => $request->description, // Texte de description
            ]);

            // Afficher la réponse brute pour le débogage
            // \Log::info('Réponse brute de l\'API Hugging Face:', ['response' => $response->body()]);

            if ($response->successful()) {
                // Si l'API renvoie une image binaire
                if (strpos($response->header('Content-Type'), 'image/') !== false) {
                    $imageData = $response->body();
                    $imageName = 'generated-image-' . time() . '.png'; // Nom du fichier
                    
                    
                    // Vérifier si le dossier images existe, sinon le créer
                    $imagesPath = public_path('images');
                    if (!file_exists($imagesPath)) {
                        mkdir($imagesPath, 0755, true);
                    }
                    
                    // Enregistrer l'image directement dans le dossier public/images
                    file_put_contents(public_path('images/' . $imageName), $imageData);

                    return response()->json([
                        'image_url' => $imageName, // Juste le nom du fichier
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Format de réponse inattendu : ' . $response->header('Content-Type'),
                    ], 500);
                }
            } else {
                return response()->json([
                    'error' => 'Erreur API : ' . $response->body(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la génération de l\'image : ' . $e->getMessage(),
            ], 500);
        }
    }
}
