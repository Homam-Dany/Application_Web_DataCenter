<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Demandes de réservation (Responsable)</h1>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="5">
    <tr>
        <th>Utilisateur</th>
        <th>Ressource</th>
        <th>Date début</th>
        <th>Date fin</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>

    @foreach($reservations as $reservation)
        <tr>
            <td>{{ $reservation->user->name ?? 'Non défini' }}</td>
            <td>{{ $reservation->resource->name ?? 'Non défini' }}</td>
            <td>{{ $reservation->start_date }}</td>
            <td>{{ $reservation->end_date }}</td>
            <td>{{ $reservation->status }}</td>
            <td>
                <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                    @csrf
                    <select name="statut" required>
                        <option value="">--Changer le statut--</option>
                        <option value="Approuvée">Approuvée</option>
                        <option value="Refusée">Refusée</option>
                    </select>
                    <button type="submit">Mettre à jour</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>