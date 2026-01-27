{{-- resources/views/notifications/partials/content.blade.php --}}

{{-- 1. Affichage du motif de REFUS (pour l'utilisateur) --}}
@if(isset($notification->data['rejection_reason']) && $status === 'Refusée')
    <div
        style="margin-top: 15px; padding: 15px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px;">
        <span
            style="display: block; font-size: 0.75rem; color: #ef4444; text-transform: uppercase; font-weight: 800; margin-bottom: 5px;">
            <i class="fas fa-exclamation-circle"></i> Motif du refus :
        </span>
        <p style="margin: 0; color: #ef4444; font-style: italic; font-size: 0.95rem;">
            "{{ $notification->data['rejection_reason'] }}"
        </p>
    </div>
@endif

{{-- 2. Affichage de la JUSTIFICATION DU BESOIN --}}
@if(isset($notification->data['justification']))
    <div
        style="margin-top: 15px; padding: 15px; background: var(--bg-hover); border-radius: 8px; border-left: 3px solid var(--primary); border: 1px solid var(--border-color);">
        <span
            style="display: block; font-size: 0.75rem; color: var(--primary); text-transform: uppercase; font-weight: 800; margin-bottom: 5px;">
            <i class="fas fa-quote-left"></i> Justification du besoin :
        </span>
        <p style="margin: 0; color: var(--text-secondary); font-style: italic; font-size: 0.95rem; line-height: 1.5;">
            "{{ $notification->data['justification'] }}"
        </p>
    </div>
@endif

{{-- 3. Affichage de la DESCRIPTION (pour les incidents) --}}
@if(isset($notification->data['description']))
    <div
        style="margin-top: 15px; padding: 15px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 8px; border-left: 3px solid #f59e0b;">
        <span
            style="display: block; font-size: 0.75rem; color: #d97706; text-transform: uppercase; font-weight: 800; margin-bottom: 5px;">
            <i class="fas fa-exclamation-triangle"></i> Détails du problème :
        </span>
        <p style="margin: 0; color: #d97706; font-style: italic; font-size: 0.95rem; line-height: 1.5;">
            "{{ $notification->data['description'] }}"
        </p>
    </div>
@endif