<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | DC-Manager</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Material Icons (Used by UniTime design) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/auth/login.css'])
</head>

<body>
    <div
        class="login-content {{ (isset($panel) && $panel === 'register') || request('panel') === 'register' || session('panel') === 'register' ? 'sign-up-mode' : '' }}">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- FORMULAIRE DE CONNEXION -->
                <form method="POST" action="{{ route('login') }}" class="sign-in-form">
                    @csrf
                    <h2 class="title" style="color:#5c6bc0">Se Connecter</h2>

                    <div class="input-field">
                        <i class="material-icons">email</i>
                        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                    </div>
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror

                    <div class="input-field">
                        <i class="material-icons">lock</i>
                        <input type="password" name="password" placeholder="Mot de passe" required>
                    </div>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror

                    <div class="form-options">
                        <a href="{{ route('password.request') }}" class="forgot-pass">Mot de passe oublié ?</a>
                    </div>

                    <input type="submit" value="SE CONNECTER" class="btn solid" />

                    <a href="{{ route('resources.index') }}" class="guest-link-auth">Continuer comme invité</a>
                </form>

                <!-- FORMULAIRE D'INSCRIPTION -->
                <form method="POST" action="{{ route('register') }}" class="sign-up-form">
                    @csrf
                    <h2 class="title" style="color:#5c6bc0">Inscription</h2>

                    <div class="input-field">
                        <i class="material-icons">person</i>
                        <input type="text" name="name" placeholder="Nom" required value="{{ old('name') }}">
                    </div>
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror

                    <div class="input-field">
                        <i class="material-icons">email</i>
                        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                    </div>
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror

                    <div class="input-field">
                        <i class="material-icons">lock</i>
                        <input type="password" name="password" placeholder="Mot de passe" required>
                    </div>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror

                    <div class="input-field">
                        <i class="material-icons">lock_outline</i>
                        <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe"
                            required>
                    </div>

                    <input type="submit" value="S'INSCRIRE" class="btn solid" />
                    <a href="{{ route('resources.index') }}" class="guest-link-auth">Continuer comme invité</a>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Nouveau ici ?</h3>
                    <p>Rejoignez-nous et commencez à gérer vos ressources dès maintenant !</p>
                    <button class="btn transparent" id="sign-up-btn">S'INSCRIRE</button>
                </div>
                <!-- Image UniTime adaptée (Log.png for "New Here?" usually sits on the left panel to invite sign up) -->
                <!-- In sliding form: Left Panel visible when in Sign In Mode (to invite usage of Sign Up) -->
                <!-- UniTime uses log.png in Left Panel -->
                <img src="{{ asset('images/log.png') }}" class="image" alt="Login Illustration" />
            </div>

            <div class="panel right-panel">
                <div class="content">
                    <h3>Déjà membre ?</h3>
                    <p>Connectez-vous pour accéder à votre espace personnel.</p>
                    <button class="btn transparent" id="sign-in-btn">SE CONNECTER</button>
                </div>
                <!-- Image UniTime adaptée -->
                <img src="{{ asset('images/register.png') }}" class="image" alt="Register Illustration" />
            </div>
        </div>
    </div>

    @vite(['resources/js/auth/login.js'])
</body>

</html>