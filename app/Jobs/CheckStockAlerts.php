<?php

namespace App\Jobs;

use App\Models\MatierePremiere;
use App\Models\User;
use App\Notifications\StockAlertNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckStockAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Récupérer les matières sous le seuil d'alerte ou critique
        $matieresCritiques = MatierePremiere::whereNull('deleted_at')
            ->where('quantite', '<=', DB::raw('seuil_min'))
            ->get();

        $matieresAlerte = MatierePremiere::whereNull('deleted_at')
            ->where('quantite', '>', DB::raw('seuil_min'))
            ->where('quantite', '<=', DB::raw('seuil_alerte'))
            ->get();

        $matieres = $matieresCritiques->merge($matieresAlerte);

        if ($matieres->isEmpty()) {
            Log::info('[StockAlert] Tous les stocks sont dans les normes.');
            return;
        }

        // Notifier tous les administrateurs
        $admins = User::where('role', 'admin')
            ->orWhere('is_admin', true)
            ->get();

        if ($admins->isEmpty()) {
            Log::warning('[StockAlert] Aucun administrateur trouvé pour les notifications.');
            return;
        }

        foreach ($matieres as $matiere) {
            foreach ($admins as $admin) {
                // Éviter les doublons : ne pas renvoyer si déjà notifié aujourd'hui
                $dejaNotifie = $admin->notifications()
                    ->whereDate('created_at', today())
                    ->where('type', StockAlertNotification::class)
                    ->whereJsonContains('data->matiere_id', $matiere->id)
                    ->exists();

                if (!$dejaNotifie) {
                    $admin->notify(new StockAlertNotification($matiere));
                    Log::info("[StockAlert] Notification envoyée pour {$matiere->libelle_matiere} (niveau: " . ($matiere->quantite <= $matiere->seuil_min ? 'critique' : 'alerte') . ")");
                }
            }
        }
    }
}