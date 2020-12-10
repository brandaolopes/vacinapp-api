<?php

namespace App\Http\Controllers;


use App\Vaccine;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VaccineController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * VaccinesController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    /**
     * @return mixed
     * 
     * Retorna todas as vacinas cadastradas pelo usuario autenticado
     */
    public function index()
    {
        return $this->user->vaccines()->get(['title', 'description', 'batch', 'total_doses_number', 'frequency'])->toArray();
    }


     /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * Retorna uma vacina pelo ID
     */
    public function show($id)
    {
        $vaccine = $this->user->vaccines()->find($id);

        if (!$vaccine) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vaccine with id '.$id.' not found!'
            ], 400);
        }

        return $vaccine;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * 
     * Cadastra uma vacina
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'total_doses_number' => 'required|numeric|min:1|max:10',

        ]);

        $vaccine = new Vaccine();
        $vaccine->title = $request->title;
        $vaccine->description = $request->description;
        $vaccine->batch = $request->batch;
        $vaccine->total_doses_number = $request->total_doses_number;
        $vaccine->frequency = $request->frequency;

        if ($this->user->vaccines()->save($vaccine)){
            return response()->json([
                'success' => true,
                'vaccine' => $vaccine
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vaccine could not be added.'
            ], 500);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * Edita as informações de uma vacina
     */
    public function update(Request $request, $id)
    {
        $vaccine = $this->user->vaccines()->find($id);

        if (!$vaccine) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vaccine with id '.$id.' not found!'
            ], 400);
        } 

        $updated = $vaccine->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vaccine could not be updated.'
            ], 500);
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * Deleta uma vacina
     */
    public function destroy($id)
    {
        $vaccine = $this->user->vaccines()->find($id);

        if (!$vaccine) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vaccine with id '.$id.' not found!'
            ], 400);
        } 

        if ($vaccine->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Vaccine could not be deleted.'
            ], 500);
        }

    }


}
