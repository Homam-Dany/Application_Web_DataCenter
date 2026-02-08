<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rapport Mensuel - DC Manager</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a202c;
            line-height: 1.5;
            margin: 40px;
        }

        .header-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 120px;
            background-color: #4f46e5;
            /* Indigo 600 */
            z-index: -1;
        }

        .header {
            color: white;
            padding-top: 30px;
            margin-bottom: 50px;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            font-size: 18px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .meta-info {
            position: absolute;
            top: 40px;
            right: 40px;
            text-align: right;
            color: white;
            font-size: 11px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: #4f46e5;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .kpi-grid {
            width: 100%;
            margin-bottom: 20px;
        }

        .kpi-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            width: 30%;
            display: inline-block;
            margin-right: 2%;
        }

        .kpi-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
            display: block;
        }

        .kpi-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 5px;
            display: block;
            font-weight: 600;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 20px;
        }

        th {
            background: #f1f5f9;
            text-align: left;
            padding: 10px;
            color: #475569;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Status colors */
        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .bg-green {
            background-color: #10b981;
        }

        .bg-orange {
            background-color: #f59e0b;
        }

        .bg-red {
            background-color: #ef4444;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header-bg"></div>

    <div class="header">
        <div class="logo">DC-Manager</div>
        <div class="report-title">Rapport Mensuel d'Activité</div>
    </div>

    <div class="meta-info">
        <div>PÉRIODE: {{ strtoupper($stats['period']) }}</div>
        <div>EXPORTÉ LE: {{ now()->format('d/m/Y') }}</div>
        <div>PAR: {{ strtoupper(auth()->user()->name) }}</div>
    </div>

    <!-- KPI SECTION -->
    <div class="section-title">Performance Globale</div>
    <div class="kpi-grid">
        <div class="kpi-box">
            <span class="kpi-value">{{ $stats['total_reservations'] }}</span>
            <span class="kpi-label">Réservations</span>
        </div>
        <div class="kpi-box">
            <span class="kpi-value">{{ $stats['total_incidents'] }}</span>
            <span class="kpi-label">Incidents Signalés</span>
        </div>
        <div class="kpi-box">
            <span class="kpi-value"
                style="color: {{ $stats['new_users'] > 0 ? '#10b981' : '#64748b' }}">+{{ $stats['new_users'] }}</span>
            <span class="kpi-label">Nouveaux Comptes</span>
        </div>
    </div>

    <!-- INFRASTRUCTURE SECTION -->
    <div class="section-title">État de l'Infrastructure</div>
    <table style="margin-bottom: 20px;">
        <tr>
            <td width="50%" style="vertical-align: top; border: none; padding: 0;">
                <table style="border: 1px solid #e2e8f0; border-radius: 6px;">
                    <tr>
                        <th colspan="2">Parc Informatique</th>
                    </tr>
                    <tr>
                        <td>Total Équipements</td>
                        <td style="font-weight: bold; text-align: right;">{{ $resourceStats['total'] }}</td>
                    </tr>
                    <tr>
                        <td><span class="status-dot bg-green"></span> Opérationnels</td>
                        <td style="font-weight: bold; text-align: right; color: #10b981;">{{ $resourceStats['active'] }}
                        </td>
                    </tr>
                    <tr>
                        <td><span class="status-dot bg-orange"></span> En Maintenance</td>
                        <td style="font-weight: bold; text-align: right; color: #f59e0b;">
                            {{ $resourceStats['maintenance'] }}</td>
                    </tr>
                </table>
            </td>
            <td width="5%" style="border: none;"></td>
            <td width="45%" style="vertical-align: top; border: none; padding: 0;">
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; text-align: center; border-radius: 6px;">
                    <div style="font-size: 10px; color: #64748b; margin-bottom: 5px;">TAUX D'OCCUPATION BAIE (RACK)
                    </div>
                    <div style="font-size: 32px; font-weight: 800; color: #4f46e5;">
                        {{ $resourceStats['occupancy_percentage'] }}%</div>
                    <div style="font-size: 10px; color: #94a3b8;">{{ $resourceStats['racked'] }} / 42 UNITÉS</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TOP USERS -->
    <div class="section-title">Top Contributeurs</div>
    <table>
        <thead>
            <tr>
                <th>Utilisateur (Responsable)</th>
                <th>Rôle</th>
                <th style="text-align: right;">Réservations Ce Mois</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topUsers as $user)
                <tr>
                    <td>{{ $user->name }} <span style="color: #94a3b8; font-size: 9px;">({{ $user->email }})</span></td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $user->reservations_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Aucune activité enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- AUDIT LOGS -->
    <div class="section-title">Journal d'Audit (Extrait)</div>
    <table>
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="20%">Acteur</th>
                <th width="20%">Action</th>
                <th width="45%">Détails</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m H:i') }}</td>
                    <td>{{ $log->user->name ?? 'Système' }}</td>
                    <td style="color: #4f46e5; font-weight: 600;">{{ $log->action }}</td>
                    <td style="color: #64748b;">{{ Str::limit($log->description, 50) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} Gestion Data Center IDAI • Rapport généré automatiquement par le système.
    </div>
</body>

</html>