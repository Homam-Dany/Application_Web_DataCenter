<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation Mot de passe | DC-Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/auth/reset-password.css'])
</head>

<body>

    <div class="container">
        <h1>Réinitialiser le mot de passe</h1>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="input-group">
                <input type="email" name="email" placeholder="Email" value="{{ old('email', $request->email) }}"
                    required autofocus>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Nouveau mot de passe" required
                    autocomplete="new-password">
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="text-align: left;">
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required
                    autocomplete="new-password">
                @error('password_confirmation') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <button type="submit">Valider le nouveau mot de passe</button>
        </form>
    </div>

</body>

</html>