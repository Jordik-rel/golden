<?php

namespace App\Console\Commands;

use App\Models\MatierePremiere;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StockFaibleNotification;

class CheckStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les stocks de matières premières';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 🔴 Niveau critique
        $critiques = MatierePremiere::whereColumn('quantite', '<=', 'seuil_min')->get();

        // 🟠 Niveau alerte
        $alertes = MatierePremiere::whereColumn('quantite', '<=', 'seuil_alerte')
            ->whereColumn('quantite', '>', 'seuil_min')
            ->get();

        if ($critiques->count() > 0 || $alertes->count() > 0) {

            $admins = User::whereHas('roles.permission', function ($query) {
                $query->where('liste_permission', 'Gerer matiere');
            })->get();

            Notification::send($admins, new StockFaibleNotification($critiques, $alertes));
        }

        return 0;
    }
}
