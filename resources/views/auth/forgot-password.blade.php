<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié | DC-Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/auth/forgot-password.css'])
</head>

<body>

    <div class="container">
        <h1>Mot de passe oublié ?</h1>
        <p>Pas de souci. Indiquez simplement votre adresse email et nous vous enverrons un lien de réinitialisation.</p>

        @if (session('status'))
            <div class="status-msg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group">
                <input type="email" name="email" placeholder="Entrez votre email" value="{{ old('email') }}" required
                    autofocus>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <button type="submit">Envoyer le lien</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Retour à la connexion
        </a>
    </div>

</body>

</html>