<?php

use App\Models\Fournisseur;
use App\Models\MatierePremiere;
use App\Models\TypeProduction;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MatierePremiere::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(TypeProduction::class)->nullable()->constrained()->cascadeOnDelete();
            $table->enum('type_mouvement',['entree','sortie','ajustement']);
            $table->string('libelle_mouvement');
            $table->foreignIdFor(Fournisseur::class)->constrained()->cascadeOnDelete();
            $table->double('quantite');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
