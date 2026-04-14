<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Production — Golden Stock</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Sans', Arial, sans-serif; font-size:11px; color:#1A1A2E; background:#fff; }

        /* ── HEADER ────────────────────────────────────────────────── */
        .header        { background:#0D1B2A; padding:16px 28px 14px; }
        .header table  { width:100%; }
        .company-name  { font-size:24px; font-weight:bold; color:#fff; letter-spacing:2px; }
        .tagline       { font-size:8.5px; color:#C8960C; font-style:italic; margin-top:3px; }
        .badge         { width:48px; height:48px; border-radius:50%; background:#C8960C;
                         text-align:center; line-height:48px; font-size:17px;
                         font-weight:bold; color:#0D1B2A; display:inline-block; }
        .gold-stripe   { height:4px; background:#C8960C; }

        /* ── BODY ───────────────────────────────────────────────────── */
        .body          { padding:16px 28px 80px; }

        /* ── TITLE ──────────────────────────────────────────────────── */
        .doc-title     { font-size:15px; font-weight:bold; color:#0D1B2A;
                         text-transform:uppercase; letter-spacing:.8px; }
        .doc-sub       { font-size:8.5px; color:#6B7280; margin-top:3px; margin-bottom:3px; }
        .gold-sep      { border:none; border-top:2px solid #C8960C; margin:8px 0 12px; }

        /* ── META ───────────────────────────────────────────────────── */
        .meta-table    { width:100%; border-collapse:collapse; margin-bottom:16px; }
        .meta-table th { background:#F4F6F9; font-size:7.5px; font-weight:bold;
                         color:#6B7280; text-transform:uppercase; letter-spacing:.4px;
                         padding:5px 9px; border:1px solid #D1D9E0; }
        .meta-table td { background:#fff; font-size:10px; font-weight:bold;
                         color:#1A1A2E; padding:6px 9px; border:1px solid #D1D9E0; }
        .validated     { color:#16A34A; font-weight:bold; }

        /* ── SECTION HEADER ─────────────────────────────────────────── */
        .section-hdr   { background:#1E3A5F; color:#fff; font-size:9.5px; font-weight:bold;
                         padding:7px 12px; letter-spacing:.4px; }

        /* ── RECAP ──────────────────────────────────────────────────── */
        .recap-table   { width:100%; border-collapse:collapse; }
        .recap-table td { background:#F4F6F9; padding:5px 10px;
                          border:1px solid #D1D9E0; font-size:8.5px; color:#6B7280; }
        .recap-table td.val { font-size:11px; font-weight:bold; color:#0D1B2A;
                              text-align:right; }

        /* ── MOUVEMENT TABLE ────────────────────────────────────────── */
        .mv-table       { width:100%; border-collapse:collapse; margin-bottom:18px; }
        .mv-table thead tr { background:#0D1B2A; }
        .mv-table thead th { color:#fff; font-size:8.5px; font-weight:bold;
                             padding:7px 9px; text-align:left; }
        .mv-table thead th.right { text-align:right; }
        .mv-table thead th.gold  { color:#C8960C; text-align:right; }
        .mv-table tbody tr:nth-child(odd)  { background:#fff; }
        .mv-table tbody tr:nth-child(even) { background:#EDF2F7; }
        .mv-table tbody td   { padding:7px 9px; font-size:8.5px;
                               border-bottom:1px solid #D1D9E0; color:#1A1A2E; }
        .mv-table tbody td.muted  { color:#6B7280; font-style:italic; }
        .mv-table tbody td.right  { text-align:right; font-weight:bold; color:#1E3A5F; }
        .mv-table tfoot tr   { background:#0D1B2A; }
        .mv-table tfoot td   { color:#fff; font-weight:bold; font-size:9px;
                               padding:8px 9px; }
        .mv-table tfoot td.gold { color:#C8960C; text-align:right; }

        /* ── NO MOVEMENT ────────────────────────────────────────────── */
        .no-mv  { background:#FEF2F2; border:1px solid #FECACA;
                  color:#DC2626; font-style:italic; font-size:8.5px;
                  padding:8px 12px; margin-bottom:18px; }

        /* ── KPI ────────────────────────────────────────────────────── */
        .kpi-table     { width:100%; border-collapse:collapse; margin-bottom:16px; }
        .kpi-table td  { width:33.33%; background:#F4F6F9; border:1px solid #D1D9E0;
                         padding:12px 14px; vertical-align:top; }
        .kpi-label     { font-size:7.5px; color:#6B7280; text-transform:uppercase;
                         letter-spacing:.4px; margin-bottom:5px; }
        .kpi-value     { font-size:22px; font-weight:bold; color:#1E3A5F; }

        /* ── SIGNATURE ──────────────────────────────────────────────── */
        .sig-table     { width:100%; border-collapse:collapse; margin-bottom:12px; }
        .sig-table th  { background:#F4F6F9; font-size:8px; font-weight:bold;
                         color:#6B7280; text-align:center; text-transform:uppercase;
                         letter-spacing:.4px; padding:6px; border:1px solid #D1D9E0; }
        .sig-table td  { height:52px; border:1px solid #D1D9E0; text-align:center;
                         vertical-align:bottom; padding:5px 8px; font-size:8.5px;
                         border-top:2px solid #0D1B2A; color:#1A1A2E; }
        .sig-line      { color:#6B7280; font-style:italic; }

        /* ── NOTE ───────────────────────────────────────────────────── */
        .note { font-size:7.5px; color:#9CA3AF; font-style:italic; margin-top:5px; }

        /* ── FOOTER ─────────────────────────────────────────────────── */
        .footer        { position:fixed; bottom:0; left:0; right:0;
                         border-top:2px solid #C8960C; padding:5px 28px; }
        .footer table  { width:100%; }
        .footer td     { font-size:7.5px; color:#9CA3AF; }
        .footer td.right { text-align:right; }

        .spacer { height:16px; }
    </style>
</head>
<body>

{{-- FOOTER --}}
<div class="footer">
    <table><tr>
        <td>© {{ date('Y') }} Golden Stock — Document confidentiel</td>
        <td class="right">Généré le {{ $generate_date }}</td>
    </tr></table>
</div>

{{-- HEADER --}}
<div class="header">
    <table>
        <tr>
            <td style="width:70%;">
                <div class="company-name">GOLDEN STOCK</div>
                <div class="tagline">Gestion &amp; Suivi de Production</div>
            </td>
            <td style="width:30%; text-align:right; vertical-align:middle;">
                {{-- Remplacez par votre logo : --}}
                {{-- <img src="{{ asset('logo.png') }}" height="46"> --}}
                <!-- <div class="badge">GS</div> -->
            </td>
        </tr>
    </table>
</div>
<div class="gold-stripe"></div>

{{-- BODY --}}
<div class="body">

    {{-- Title --}}
    <div class="doc-title">Rapport de Production Journalière</div>
    <div class="doc-sub">
        Matières consommées —
        Journée du {{ \Carbon\Carbon::parse($productions->first()?->date_production ?? now())->translatedFormat('d F Y') }}
    </div>
    <hr class="gold-sep">

    {{-- Meta --}}
    <table class="meta-table">
        <thead>
            <tr>
                <th>Superviseur</th>
                <th>Date de production</th>
                <th>Heure de saisie</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($date_production)->translatedFormat('d F Y') }}
                </td>
                <td>{{ now()->format('H:i') }}</td>
                <td><span class="validated">✔ Validé</span></td>
            </tr>
        </tbody>
    </table>

    {{-- ── UNE SECTION PAR PRODUCTION ────────────────────────────── --}}
    @php $totalGlobal = 0; @endphp

        {{-- Filtrer uniquement les mouvements de type 'sortie' --}}
    @foreach($productions as $production)
        @php
            $sorties = $production->type->mouvements
                ->where('type_mouvement', 'sortie');

            $totalGlobal += $production->quantite;
        @endphp

        {{-- Section header --}}
        <div class="section-hdr">&#x1F4E6;&nbsp; {{ strtoupper($production->type->libelle_type_production) }}</div>

        {{-- Quantité produite --}}
        <table class="recap-table">
            <tr>
                <td>Quantité produite :</td>
                <td class="val">{{ number_format($production->quantite, 2, ',', ' ') }}</td>
            </tr>
        </table>

        @if($sorties->isNotEmpty())
            {{-- Tableau mouvements sortie --}}
            <table class="mv-table">
                <thead>
                    <tr>
                        <th style="width:30%;">Matière première</th>
                        <th style="width:10%;">Unité</th>
                        <th class="right" style="width:18%;">Qté consommée</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sorties as $mouvement)
                    <tr>
                        <td>{{ $mouvement->matiere->nom }}</td>
                        <td class="muted">{{ $mouvement->matiere->unite }}</td>
                        <td class="right">{{ number_format($mouvement->quantite, 2, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-mv">
                ⚠&nbsp; Aucun mouvement de sortie enregistré pour ce type de production.
            </div>
        @endif

        <div class="spacer"></div>
    @endforeach

    {{-- ── SYNTHÈSE GLOBALE ───────────────────────────────────────── --}}
    <div class="section-hdr">&#x1F4CA;&nbsp; SYNTHÈSE GLOBALE</div>
    <br>
    <table class="kpi-table">
        <tr>
            <td>
                <div class="kpi-label">Productions</div>
                <div class="kpi-value">{{ $productions->count() }}</div>
            </td>
            <td>
                <div class="kpi-label">Avec mouvements sortie</div>
                <div class="kpi-value">
                    {{ $productions->filter(fn($p) => $p->type->mouvements->where('type_mouvement','sortie')->isNotEmpty())->count() }}
                </div>
            </td>
            <td>
                <div class="kpi-label">Volume total produit</div>
                <div class="kpi-value">{{ number_format($totalGlobal, 2, ',', ' ') }}</div>
            </td>
        </tr>
    </table>

    {{-- ── SIGNATURES ──────────────────────────────────────────────── --}}
    <div class="section-hdr">&#x270D;&nbsp; VALIDATION &amp; SIGNATURES</div>
    <br>
    <table class="sig-table">
        <thead>
            <tr>
                <th>Établi par</th>
                <th>Vérifié par</th>
                <th>Approuvé par</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ auth()->user()->name }}<br>
                    <span class="sig-line">Superviseur de production</span>
                </td>
                <td><span class="sig-line">Responsable Qualité</span></td>
                <td><span class="sig-line">Directeur Général</span></td>
            </tr>
        </tbody>
    </table>

    <p class="note">
        Ce document est généré automatiquement par le système Golden Stock.
        Toute modification manuelle le rend invalide.
    </p>

</div>
</body>
</html>