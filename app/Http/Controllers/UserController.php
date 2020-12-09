<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'surname' => ['required', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'unique:users'],
            'password' => ['required'],
            'cpf' => ['required', 'cpf', 'unique:users'],
            'date' => ['required', 'date'],
            'gender' => ['max:1'],
            'state' => ['max:2'],
            'city' => ['max:255'], 
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'cpf' => $request->input('cpf'),
            'date' => $request->input('date'),
            'gender' => $request->input('gender'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
        ]);

        return response()->json($user);
    }


    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'surname' => ['required', 'max:255'],
            'cpf' => ['required', 'cpf'],
            'date' => ['required','date'],
            'gender' => ['max:1'],
            'state' => ['max:2'],
            'city' => ['max:255'],
            ]);

        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->cpf = $request->input('cpf');
        $user->date = $request->input('date');
        $user->gender = $request->input('gender');
        $user->state = $request->input('state');
        $user->city = $request->input('city');

        $user->save();

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['msg' => 'Deletado com sucesso']);
    }

}
