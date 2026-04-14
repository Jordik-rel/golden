<?php

namespace App\Notifications;

use App\Models\MatierePremiere;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockAlertNotification extends Notification
{
    use Queueable;

    public string $niveau; // 'alerte' ou 'critique'

    public function __construct(public MatierePremiere $matiere)
    {
        $this->niveau = $matiere->quantite <= $matiere->seuil_min
            ? 'critique'
            : 'alerte';
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isCritique = $this->niveau === 'critique';

        return (new MailMessage)
            ->subject($isCritique
                ? '🚨 Stock CRITIQUE — ' . $this->matiere->libelle_matiere
                : '⚠️ Stock faible — ' . $this->matiere->libelle_matiere
            )
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line($isCritique
                ? 'Le stock de **' . $this->matiere->libelle_matiere . '** a atteint un niveau **CRITIQUE**.'
                : 'Le stock de **' . $this->matiere->libelle_matiere . '** est en dessous du seuil d\'alerte.'
            )
            ->line('---')
            ->line('**Matière :** ' . $this->matiere->libelle_matiere)
            ->line('**Stock actuel :** ' . number_format($this->matiere->quantite, 2, ',', ' ') . ' ' . $this->matiere->unite)
            ->line('**Seuil alerte :** ' . number_format($this->matiere->seuil_alerte, 2, ',', ' ') . ' ' . $this->matiere->unite)
            ->line('**Seuil critique :** ' . number_format($this->matiere->seuil_min, 2, ',', ' ') . ' ' . $this->matiere->unite)
            ->action('Voir le stock', url('/dashboard/matieres/' . $this->matiere->id))
            ->line($isCritique
                ? 'Une action **immédiate** est requise pour éviter un arrêt de production.'
                : 'Veuillez prévoir un réapprovisionnement dans les meilleurs délais.'
            )
            ->salutation('Golden Stock — Système d\'alertes automatiques');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'matiere_id'      => $this->matiere->id,
            'libelle_matiere' => $this->matiere->libelle_matiere,
            'quantite'        => $this->matiere->quantite,
            'unite'           => $this->matiere->unite,
            'seuil_alerte'    => $this->matiere->seuil_alerte,
            'seuil_min'       => $this->matiere->seuil_min,
            'niveau'          => $this->niveau,
            'message'         => $this->niveau === 'critique'
                ? 'Stock critique : ' . $this->matiere->libelle_matiere
                : 'Stock faible : ' . $this->matiere->libelle_matiere,
        ];
    }
}