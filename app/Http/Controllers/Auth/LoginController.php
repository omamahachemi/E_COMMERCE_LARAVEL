<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Ce contrôleur gère l'authentification des utilisateurs pour l'application et
    | les redirige vers la page d'accueil après une connexion réussie.
    |
    */

    use AuthenticatesUsers;

    /**
     * Rediriger l'utilisateur après la connexion.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Créer une nouvelle instance du contrôleur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Afficher le formulaire de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // return view('auth.login');
        return view('home');
    }

    /**
     * Gérer une tentative de connexion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        
        // Valider les données du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Tenter de connecter l'utilisateur
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // Si la connexion échoue, rediriger avec un message d'erreur
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Rediriger l'utilisateur en fonction de son rôle après la connexion.
     *
     * @return string
     */
    protected function redirectTo()
    {
        // Vérifiez si l'utilisateur est un administrateur
        if (Auth::user()->is_admin) {
            return '/admin/dashboard'; // Redirection pour l'admin
        } else {
            return '/'; // Redirection pour les utilisateurs normaux
        }
        // return '/admin/dashboard';
    }

    /**
     * Déconnecter l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
