<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Peticione;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PeticioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $peticiones = Peticione::all();
        return response()->json($peticiones, 200);
    }

    public function listMine(Request $request)
    {
// parent::index()
        $user = Auth::user();
//        $id = 1;
        $peticiones = Peticione::all()->where('user_id', $user);
        return response()->json($peticiones, 200);
    }

    public function show(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        return response()->json($peticion, 200);
    }

    public function update(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        $user = Auth::user();

        if ($peticion->user_id != $user->id && $user->role_id !== 1) {
            abort(403, 'No tienes permiso para actualizar esta petición.');
        }

        $validatedData = $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'destinatario' => 'required|string|max:255',
        ]);

        $peticion->update($validatedData);
        return response()->json($peticion, 200);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'destinatario' => 'required',
            'categoria_id' => 'required',
        ]);
        // 'file' => 'required',
        $input = $request->all();
        $category = Categoria::findOrFail($request->input('categoria_id'));
        //$user = 1;
        $user = Auth::user();
        $peticion = new Peticione($input);
        $peticion->user()->associate($user);
        $peticion->categoria()->associate($category);
        $peticion->firmantes = 0;
        $peticion->estado = 'pendiente';
        $peticion->save();
        return response()->json($peticion, 200);
    }

    public function firmar(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        $user = Auth::user();

        if ($peticion->firmas()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Ya has firmado esta petición'], 403);
        }

        $peticion->firmas()->attach($user->id);
        $peticion->firmantes += 1;
        $peticion->save();

        return response()->json(['message' => 'Has firmado la petición exitosamente']);
    }


    public function cambiarEstado(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        $peticion->estado = 'aceptada';
        $peticion->save();
        return $peticion;
    }

    public function delete(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        $user = Auth::user();

        if ($peticion->user_id != $user->id && $user->role_id !== 1) {
            abort(403, 'No tienes permiso para eliminar esta petición.');
        }

        $peticion->delete();
        return response()->json($peticion, 200);
    }

}
