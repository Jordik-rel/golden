<x-mail::message>

<x-mail::panel>
**Rapport de Production Journalière**
Journée du {{ \Carbon\Carbon::parse($date_production)->translatedFormat('d F Y') }}
</x-mail::panel>

Bonjour,

Veuillez trouver ci-joint le rapport de production journalière généré automatiquement par **{{ config('app.name') }}**.

---

**Récapitulatif de la journée**

<x-mail::table>
| Informations | Détails |
|:-------------|:--------|
| Superviseur | {{ $superviseur }} |
| Date de production | {{ \Carbon\Carbon::parse($date_production)->translatedFormat('d F Y') }} |
| Heure de génération | {{ $heure_generation }} |
| Nombre de productions | {{ $nombre_productions }} |
| Volume total produit | {{ number_format($volume_total, 2, ',', ' ') }} unités |
</x-mail::table>

---

**Productions du jour**

@foreach($productions as $production)
<x-mail::panel>
**{{ $production['type'] }}** — {{ number_format($production['quantite'], 2, ',', ' ') }} unités produites

@if(count($production['sorties']) > 0)
Matières consommées :
@foreach($production['sorties'] as $sortie)
- {{ $sortie['matiere'] }} : **{{ number_format($sortie['quantite'], 2, ',', ' ') }} {{ $sortie['unite'] }}**
@endforeach
@else
_Aucun mouvement de sortie enregistré._
@endif
</x-mail::panel>
@endforeach

---

Le rapport complet au format PDF est disponible en pièce jointe ou via le lien ci-dessous.

<x-mail::button :url="$pdf_url" color="primary">
Télécharger le rapport PDF
</x-mail::button>

Ce document est généré automatiquement. Toute modification manuelle le rend invalide.

Cordialement,<br>
L'équipe {{ config('app.name') }}

</x-mail::message>