<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
public function index()
{
    $category = Category::all();
    return response()->json([
        'status'=>200,
        'category'=>$category,
    ]);
}

public function allcategory()
{
    $category = Category::where('status','0')->get();
    return response()->json([
        'status'=>200,
        'category' =>$category,
    ]);
}

public function edit($id)
{
    $category = Category::find($id);
    if($category)
    {
        return response()->json([
            'status'=>200,
            'category'=>$category
        ]);
    }
    else
    {
        return response()->json([
            'status'=>404,
            'message'=>'Catégorie non trouvé!'
        ]);
    }
}

public function update(Request $request, $id){
    $validator = Validator::make($request->all(), [
        'slug'=>'required|max:191',
        'name'=>'required|max:191',
    ],
    [
        'slug.required'=>'Le champ Slug est obligatoire.',
        'slug.max'=>'La longueur du Slug est trop longue. La longueur maximale est de 191.',
        'name.required'=>'Le champ Nom est obligatoire.',
        'name.max'=>'La longueur du nom est trop longue. La longueur maximale est de 191.',
    ]);

    if($validator->fails())
    {
        return response()->json([
            'status'=>422,
            'errors'=>$validator->getMessageBag(),
        ]);
    }
    else
    {
        $category = Category::find($id);
        if($category)
        {
            $category->slug = $request->input('slug');
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->save();
            return response()->json([
                'status'=>200,
                'message'=>'Catégorie mise à jour avec succès',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Catégorie non trouvé!'
            ]);
        }
    }
}

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
        ],
        [
            'slug.required'=>'Le champ Slug est obligatoire.',
            'slug.max'=>'La longueur du Slug est trop longue. La longueur maximale est de 191.',
            'name.required'=>'Le champ Nom est obligatoire.',
            'name.max'=>'La longueur du nom est trop longue. La longueur maximale est de 191.',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->getMessageBag(),
            ]);
        }
        else
        {
            $category = new Category;
            $category->slug = $request->input('slug');
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->status = $request->input('status') == true ? '1':'0';
            $category->save();
            return response()->json([
                'status'=>200,
                'message'=>'Catégorie ajoutée avec succès',
            ]);
        }
    }

    public function destroy($id)
    {
        if($id === 1 | $id==1 | $id === '1' | $id == '1'){
            return response()->json([
                'status'=>401,
                'message'=>'La catégorie \'non classée\' ne peut pas être supprimée!',
            ]);
        }
        else{
        $category = Category::find($id);
        $products = Product::where('category_id',$id)->get();
        foreach($products as $product){
            $product->category_id = 1;
            $product->save();
        }
        if($category)
        {
            $category->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Catégorie supprimée avec succès',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Catégorie non trouvé!',
            ]);
        }
        }
    }
}
