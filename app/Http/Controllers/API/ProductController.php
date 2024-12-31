<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }

    public function search($keyword)
    {
        $results = Product::where("name", "Like", "%$keyword%")->get();
        return response()->json([
            'status' => 200,
            'results' => $results,
        ]);
    }

    public function latests()
    {
        $products = Product::where('status', '0')->latest()->take(4)->get();
        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }

    public function biggestdiscounts()
    {
        $products = Product::all()->max($this->original_price, $this->selling_price);
        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'category_id' => 'required|max:191',
                'slug' => 'required|max:191',
                'name' => 'required|max:191',
                'brand' => 'required|max:20',
                'selling_price' => 'required|max:20',
                'original_price' => 'required|max:20',
                'qty' => 'required|max:4',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'category_id.required' => 'Le champ Id catégorie est obligatoire.',
                'category_id.max' => 'La longueur du Id catégorie est trop longue. La longueur maximale est de 191.',
                'slug.required' => 'Le champ Slug est obligatoire.',
                'slug.max' => 'La longueur du Slug est trop longue. La longueur maximale est de 191.',
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du Nom est trop longue. La longueur maximale est de 191.',
                'brand.required' => 'Le champ Marque est obligatoire.',
                'brand.max' => 'La longueur du Marque est trop longue. La longueur maximale est de 20.',
                'selling_price.required' => 'Le champ Prix de vente est obligatoire.',
                'selling_price.max' => 'La longueur du Prix de vente est trop longue. La longueur maximale est de 20.',
                'original_price.required' => 'Le champ Prix d\'origine est obligatoire.',
                'original_price.max' => 'La longueur du Prix d\'origine est trop longue. La longueur maximale est de 20.',
                'qty.required' => 'Le champ Quantité est obligatoire.',
                'qty.max' => 'La longueur du Quantité est trop longue. La longueur maximale est de 191.',
                'image.required' => 'l\'image est obligatoire.',
                'image.image' => 'Le format de l\'image n\'est pas valide',
                'image.mimes' => 'Le format de l\'image n\'est pas valide',
                'image.max' => 'La longueur du Image est trop longue. La longueur maximale est de 2048.'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        } else {
            $product = new Product;
            $product->category_id = $request->input('category_id');
            $product->slug = $request->input('slug');
            $product->name = $request->input('name');
            $product->description = $request->input('description');

            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/product/', $filename);
                $product->image = 'upload/product/' . $filename;
            }

            $product->status = $request->input('status') == true ? '1' : '0';
            $product->save();

            return response()->json([
                'status' => 200,
                'message' => 'ajout avec succès',
            ]);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Produit non trouvé'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'category_id' => 'required|max:191',
                'slug' => 'required|max:191',
                'name' => 'required|max:191',
                'brand' => 'required|max:20',
                'selling_price' => 'required|max:20',
                'original_price' => 'required|max:20',
                'qty' => 'required|max:4',
            ],
            [
                'category_id.required' => 'Le champ Id catégorie est obligatoire.',
                'category_id.max' => 'La longueur du Id catégorie est trop longue. La longueur maximale est de 191.',
                'slug.required' => 'Le champ Slug est obligatoire.',
                'slug.max' => 'La longueur du Slug est trop longue. La longueur maximale est de 191.',
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du Nom est trop longue. La longueur maximale est de 191.',
                'brand.required' => 'Le champ Marque est obligatoire.',
                'brand.max' => 'La longueur du Marque est trop longue. La longueur maximale est de 20.',
                'selling_price.required' => 'Le champ Prix de vente est obligatoire.',
                'selling_price.max' => 'La longueur du Prix de vente est trop longue. La longueur maximale est de 20.',
                'original_price.required' => 'Le champ Prix d\'origine est obligatoire.',
                'original_price.max' => 'La longueur du Prix d\'origine est trop longue. La longueur maximale est de 20.',
                'qty.required' => 'Le champ Quantité est obligatoire.',
                'qty.max' => 'La longueur du Quantité est trop longue. La longueur maximale est de 191.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        } else {
            $product =  Product::find($id);
            if ($product) {
                $product->category_id = $request->input('category_id');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->description = $request->input('description');

                $product->brand = $request->input('brand');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->qty = $request->input('qty');

                if ($request->hasFile('image')) {
                    $path = $product->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/product/', $filename);
                    $product->image = 'upload/product/' . $filename;
                }

                $product->status = $request->input('status');
                $product->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Produit mis à jour avec succès',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Produit non trouvé',
                ]);
            }
        }
    }
}
