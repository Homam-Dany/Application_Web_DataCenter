<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | DC-Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/auth/login.css'])
</head>

<body
    style="background: url('{{ url('images/bgdc.png') }}?v={{ time() }}') no-repeat center center fixed !important; background-size: cover !important; background-color: #1a1a1a;">
    <div class="container {{ (isset($panel) && $panel === 'register') || request('panel') === 'register' || session('panel') === 'register' ? 'active' : '' }}"
        id="container">
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Créer un compte</h1>
                <span>Utilisez votre email pour vous inscrire</span>
                <input type="text" name="name" placeholder="Nom" required value="{{ old('name') }}">
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror

                <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror

                <input type="password" name="password" placeholder="Mot de passe" required>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror

                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                <button type="submit">S'INSCRIRE</button>
                <a href="{{ route('resources.index') }}" class="guest-link-auth">Continuer comme invité</a>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Se Connecter</h1>
                <div class="social-icons">

                </div>
                <span>Utilisez votre email et mot de passe</span>
                <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror

                <input type="password" name="password" placeholder="Mot de passe" required>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror

                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                <button type="submit">SE CONNECTER</button>
                <a href="{{ route('resources.index') }}" class="guest-link-auth">Continuer comme invité</a>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>De retour ?</h1>
                    <p>Connectez-vous avec vos informations personnelles pour accéder au parc informatique.</p>
                    <button class="hidden" id="login">SE CONNECTER</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour !</h1>
                    <p>Inscrivez-vous pour commencer à gérer vos ressources dès maintenant.</p>
                    <button class="hidden" id="register">S'INSCRIRE</button>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/auth/login.js'])
</body>

</html>