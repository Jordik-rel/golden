<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Production — Golden Stock</title>
    <style>
        @page {
            margin: 0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Georgia, serif;
            font-size: 10px;
            color: #1C1C1E;
            background: #fff;
            line-height: 1.5;
        }

        /* ── PAGE STRUCTURE ───────────────────────────────────── */
        .page-wrap {
            padding: 0 0 72px 0;
        }

        /* ── TOP BAND ─────────────────────────────────────────── */
        .top-band {
            height: 5px;
            background: linear-gradient(to right, #B8860B, #DAA520, #F5C842, #DAA520, #B8860B);
        }

        /* ── HEADER ───────────────────────────────────────────── */
        .header {
            background: #0A0F1E;
            padding: 28px 40px 24px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle geometric accent */
        .header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: 60px;
            width: 160px;
            height: 160px;
            border: 28px solid rgba(218,165,32,0.08);
            border-radius: 50%;
        }
        .header::after {
            content: '';
            position: absolute;
            top: 10px;
            right: 20px;
            width: 80px;
            height: 80px;
            border: 16px solid rgba(218,165,32,0.05);
            border-radius: 50%;
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            position: relative;
            z-index: 1;
        }

        .brand-block {}

        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #FFFFFF;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .brand-separator {
            width: 40px;
            height: 2px;
            background: #DAA520;
            margin: 8px 0 6px;
        }

        .brand-tagline {
            font-size: 8px;
            color: #8A8FA8;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .doc-type-block {
            text-align: right;
        }

        .doc-type-label {
            font-size: 7px;
            color: #DAA520;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .doc-type-name {
            font-size: 11px;
            color: #FFFFFF;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .doc-ref {
            font-size: 7px;
            color: #5A6080;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        /* ── SUBHEADER ────────────────────────────────────────── */
        .subheader {
            background: #F7F6F2;
            border-bottom: 1px solid #E8E4D9;
            padding: 14px 40px;
        }

        .subheader table { width: 100%; border-collapse: collapse; }

        .sh-cell {
            padding: 0 20px 0 0;
            vertical-align: top;
            width: 25%;
        }

        .sh-cell:last-child { padding-right: 0; }

        .sh-label {
            font-size: 7px;
            color: #9A9480;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
        }

        .sh-value {
            font-size: 10px;
            color: #1C1C1E;
            font-weight: bold;
        }

        .sh-divider {
            width: 1px;
            background: #D8D2C2;
            padding: 0;
        }

        .status-pill {
            display: inline-block;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
            font-size: 7.5px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* ── BODY ─────────────────────────────────────────────── */
        .body {
            padding: 28px 40px 20px;
        }

        /* ── SECTION ──────────────────────────────────────────── */
        .section {
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }

        .section-num {
            width: 22px;
            height: 22px;
            background: #0A0F1E;
            color: #DAA520;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            line-height: 22px;
            flex-shrink: 0;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #0A0F1E;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            flex: 1;
        }

        .section-qty {
            font-size: 8px;
            color: #9A9480;
        }

        .section-qty strong {
            color: #1C1C1E;
            font-size: 11px;
        }

        .section-rule {
            height: 1px;
            background: linear-gradient(to right, #DAA520, #E8E4D9 80%);
            margin-bottom: 10px;
        }

        /* ── MOVEMENT TABLE ───────────────────────────────────── */
        .mv-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mv-table thead tr {
            background: #F7F6F2;
            border-top: 2px solid #DAA520;
        }

        .mv-table thead th {
            font-size: 7px;
            font-weight: bold;
            color: #7A7460;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 7px 12px;
            text-align: left;
            border-bottom: 1px solid #E8E4D9;
        }

        .mv-table thead th.r { text-align: right; }

        .mv-table tbody tr {
            border-bottom: 1px solid #F0EDE6;
        }

        .mv-table tbody tr:last-child {
            border-bottom: 2px solid #E8E4D9;
        }

        .mv-table tbody td {
            padding: 8px 12px;
            font-size: 9px;
            color: #1C1C1E;
        }

        .mv-table tbody td.unit {
            color: #9A9480;
            font-style: italic;
            font-size: 8.5px;
        }

        .mv-table tbody td.qty {
            text-align: right;
            font-weight: bold;
            color: #0A0F1E;
            font-variant-numeric: tabular-nums;
        }

        .mv-table tbody td.idx {
            color: #C8C0A8;
            font-size: 8px;
            width: 28px;
        }

        /* ── NO MOVEMENT ──────────────────────────────────────── */
        .no-mv {
            padding: 12px 16px;
            border-left: 3px solid #E8D5A0;
            background: #FDFBF5;
            color: #9A8A60;
            font-size: 8.5px;
            font-style: italic;
        }

        /* ── GLOBAL SUMMARY ───────────────────────────────────── */
        .summary-section {
            background: #0A0F1E;
            padding: 20px 40px;
            margin: 0 0 0 0;
        }

        .summary-title {
            font-size: 7px;
            color: #DAA520;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .kpi-row { display: flex; gap: 0; }

        .kpi-item {
            flex: 1;
            padding: 0 24px 0 0;
            border-right: 1px solid #1E2A40;
            margin-right: 24px;
        }

        .kpi-item:last-child {
            border-right: none;
            margin-right: 0;
            padding-right: 0;
        }

        .kpi-label {
            font-size: 7px;
            color: #5A6080;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 26px;
            font-weight: bold;
            color: #FFFFFF;
            line-height: 1;
        }

        .kpi-unit {
            font-size: 8px;
            color: #DAA520;
            margin-top: 2px;
        }

        /* Use table for dompdf compat */
        .kpi-table-inner { width: 100%; border-collapse: collapse; }
        .kpi-cell {
            padding: 0 24px 0 0;
            border-right: 1px solid #1E2A40;
            vertical-align: top;
            width: 33.33%;
        }
        .kpi-cell:last-child {
            border-right: none;
            padding-right: 0;
            padding-left: 24px;
        }
        .kpi-cell:not(:first-child) {
            padding-left: 24px;
        }

        /* ── SIGNATURES ───────────────────────────────────────── */
        .sig-section {
            padding: 20px 40px 0;
        }

        .sig-section-title {
            font-size: 7px;
            color: #9A9480;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E8E4D9;
        }

        .sig-table { width: 100%; border-collapse: collapse; }

        .sig-cell {
            width: 33.33%;
            padding: 0 20px 0 0;
            vertical-align: top;
        }

        .sig-cell:last-child { padding-right: 0; }

        .sig-role {
            font-size: 7px;
            color: #9A9480;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .sig-name {
            font-size: 9.5px;
            font-weight: bold;
            color: #1C1C1E;
            margin-bottom: 2px;
        }

        .sig-title-role {
            font-size: 7.5px;
            color: #7A7460;
            font-style: italic;
        }

        .sig-box {
            margin-top: 10px;
            height: 42px;
            border: 1px dashed #D8D2C2;
            border-radius: 2px;
            background: #FAFAF8;
        }

        /* ── FOOTER ───────────────────────────────────────────── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #F7F6F2;
            border-top: 1px solid #E8E4D9;
            padding: 8px 40px;
        }

        .footer table { width: 100%; border-collapse: collapse; }

        .footer td {
            font-size: 7px;
            color: #B0A890;
            vertical-align: middle;
        }

        .footer td.right { text-align: right; }

        .footer-dot {
            display: inline-block;
            width: 3px;
            height: 3px;
            background: #DAA520;
            border-radius: 50%;
            vertical-align: middle;
            margin: 0 6px;
        }

        /* ── NOTE ─────────────────────────────────────────────── */
        .doc-note {
            font-size: 7px;
            color: #C0B898;
            font-style: italic;
            text-align: center;
            padding: 12px 40px 0;
        }

        /* ── HELPERS ──────────────────────────────────────────── */
        .spacer-sm { height: 8px; }
        .spacer-md { height: 16px; }
        .spacer-lg { height: 24px; }

        .gold { color: #DAA520; }
        .muted { color: #9A9480; }
    </style>
</head>
<body>

{{-- FIXED FOOTER --}}
<div class="footer">
    <table><tr>
        <td>
            © {{ date('Y') }} Golden Stock
            <span class="footer-dot"></span>
            Document confidentiel — Usage interne uniquement
        </td>
        <td class="right">Généré le {{ $generate_date }}</td>
    </tr></table>
</div>

{{-- PAGE --}}
<div class="page-wrap">

    {{-- TOP ACCENT BAND --}}
    <div class="top-band"></div>

    {{-- HEADER --}}
    <div class="header">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="vertical-align:bottom; width:60%;">
                    <div class="brand-name">Golden Stock</div>
                    <div class="brand-separator"></div>
                    <div class="brand-tagline">Gestion &amp; Suivi de Production</div>
                </td>
                <td style="text-align:right; vertical-align:bottom; width:40%;">
                    <div class="doc-type-label">Document</div>
                    <div class="doc-type-name">Rapport de Production</div>
                    <div class="doc-ref">
                        Réf. {{ 'GS-' . \Carbon\Carbon::parse($date_production)->format('Ymd') . '-' . str_pad(Auth::id(), 3, '0', STR_PAD_LEFT) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- SUBHEADER META BAR --}}
    <div class="subheader">
        <table>
            <tr>
                <td class="sh-cell">
                    <div class="sh-label">Superviseur</div>
                    <div class="sh-value">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                </td>
                <td class="sh-divider"></td>
                <td class="sh-cell" style="padding-left:20px;">
                    <div class="sh-label">Date de production</div>
                    <div class="sh-value">
                        {{ \Carbon\Carbon::parse($date_production)->translatedFormat('d F Y') }}
                    </div>
                </td>
                <td class="sh-divider"></td>
                <td class="sh-cell" style="padding-left:20px;">
                    <div class="sh-label">Heure de saisie</div>
                    <div class="sh-value">{{ now()->format('H:i') }}</div>
                </td>
                <td class="sh-divider"></td>
                <td class="sh-cell" style="padding-left:20px;">
                    <div class="sh-label">Statut</div>
                    <div class="sh-value"><span class="status-pill">✔ Validé</span></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- BODY --}}
    <div class="body">

        @php $totalGlobal = 0; $sectionNum = 0; @endphp

        @foreach($productions as $production)
            @php
                $sorties = $production->type->mouvements->where('type_mouvement', 'sortie');
                $totalGlobal += $production->quantite;
                $sectionNum++;
            @endphp

            <div class="section">
                {{-- Section header --}}
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:22px; vertical-align:middle;">
                            <div class="section-num">{{ $sectionNum }}</div>
                        </td>
                        <td style="padding-left:10px; vertical-align:middle;">
                            <div class="section-title">{{ $production->type->libelle_type_production }}</div>
                        </td>
                        <td style="text-align:right; vertical-align:middle;">
                            <span class="muted" style="font-size:8px;">Quantité produite&nbsp;&nbsp;</span>
                            <strong style="font-size:12px; color:#0A0F1E;">{{ number_format($production->quantite, 2, ',', ' ') }}</strong>
                        </td>
                    </tr>
                </table>
                <div class="section-rule"></div>

                @if($sorties->isNotEmpty())
                    <table class="mv-table">
                        <thead>
                            <tr>
                                <th style="width:24px;">#</th>
                                <th>Matière première</th>
                                <th style="width:80px;">Unité</th>
                                <th class="r" style="width:100px;">Qté consommée</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sorties as $i => $mouvement)
                            <tr>
                                <td class="idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $mouvement->matiere->nom }}</td>
                                <td class="unit">{{ $mouvement->matiere->unite }}</td>
                                <td class="qty">{{ number_format($mouvement->quantite, 2, ',', ' ') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-mv">
                        Aucun mouvement de sortie enregistré pour cette production.
                    </div>
                @endif
            </div>

        @endforeach

    </div>

    {{-- GLOBAL SUMMARY --}}
    <div class="summary-section">
        <div class="summary-title">Synthèse globale</div>
        <table class="kpi-table-inner">
            <tr>
                <td class="kpi-cell">
                    <div class="kpi-label">Productions enregistrées</div>
                    <div class="kpi-value">{{ $productions->count() }}</div>
                    <div class="kpi-unit">types de production</div>
                </td>
                <td class="kpi-cell">
                    <div class="kpi-label">Avec mouvements de sortie</div>
                    <div class="kpi-value">
                        {{ $productions->filter(fn($p) => $p->type->mouvements->where('type_mouvement','sortie')->isNotEmpty())->count() }}
                    </div>
                    <div class="kpi-unit">productions actives</div>
                </td>
                <td class="kpi-cell">
                    <div class="kpi-label">Volume total produit</div>
                    <div class="kpi-value">{{ number_format($totalGlobal, 2, ',', ' ') }}</div>
                    <div class="kpi-unit">unités cumulées</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="spacer-md"></div>

    {{-- SIGNATURES --}}
    <div class="sig-section">
        <div class="sig-section-title">Validation &amp; Signatures</div>
        <table class="sig-table">
            <tr>
                <td class="sig-cell">
                    <div class="sig-role">Établi par</div>
                    <div class="sig-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                    <div class="sig-title-role">Superviseur de production</div>
                    <div class="sig-box"></div>
                </td>
                <td class="sig-cell">
                    <div class="sig-role">Vérifié par</div>
                    <div class="sig-name">&nbsp;</div>
                    <div class="sig-title-role">Responsable Qualité</div>
                    <div class="sig-box"></div>
                </td>
                <td class="sig-cell">
                    <div class="sig-role">Approuvé par</div>
                    <div class="sig-name">&nbsp;</div>
                    <div class="sig-title-role">Directeur Général</div>
                    <div class="sig-box"></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- LEGAL NOTE --}}
    <div class="doc-note">
        Document généré automatiquement par Golden Stock. Toute modification manuelle annule la validité de ce rapport.
    </div>

    <div class="spacer-lg"></div>

</div>

</body>
</html>