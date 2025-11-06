<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // Mostrar el formulario de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Registrar un nuevo usuario desde la web
    public function registerWeb(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Confirmación de la contraseña
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Autenticar al usuario
        auth()->login($user);

        // Redirigir al dashboard o a la página que elijas
        return redirect()->route('dashboard');
    }


    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6']
        ]);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(Request $request)
    {
        //VIA API
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:6']
        ]);


        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)){
            return response([
                'message' => 'Wrong creds',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    // Mostrar el formulario de login
    public function showLoginForm()
    {
        //VIA WEB
        return view('auth.login');
    }

    // Procesar el formulario de login
    public function loginWeb(Request $request)
    {
        //VIA WEB

        // Validar los datos de entrada
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Redirigir al usuario a la página protegida después del login
            return redirect()->intended('/dashboard'); // O la página que desees
        }

        // Si el login falla, volver a mostrar el formulario con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

}
