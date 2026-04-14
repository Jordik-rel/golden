<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProductionJournaliereMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $date_production;
    public string $superviseur;
    public string $heure_generation;
    public int    $nombre_productions;
    public float  $volume_total;
    public array  $productions;
    public string $pdf_url;
    public string $pdf_path;
    /**
     * Create a new message instance.
     */
    public function __construct(
         $productionsCollection,
        string $date_production,
        string $pdf_filename,
        ?string $superviseur = null
    )
    {
        $this->date_production    = $date_production;
        // $this->superviseur        = $superviseur;
        $this->superviseur = $superviseur ?? 'Superviseur';
        $this->heure_generation   = now()->format('H:i');
        $this->nombre_productions = $productionsCollection->count();
        $this->pdf_url            = asset('storage/rapports/' . $pdf_filename);
        $this->pdf_path           = Storage::disk('public')->path('rapports/' . $pdf_filename);
 
        $this->volume_total = $productionsCollection->sum('quantite');
 
        // Formater les productions pour la vue
        $this->productions = $productionsCollection->map(function ($p) {
            $sorties = $p->type->mouvements
                ->where('type_mouvement', 'sortie')
                ->map(fn($m) => [
                    'matiere'  => $m->matiere->libelle_matiere,
                    'quantite' => $m->quantite,
                    'unite'    => $m->matiere->unite,
                ])->values()->toArray();
 
            return [
                'type'     => $p->type->libelle_type_production,
                'quantite' => $p->quantite,
                'sorties'  => $sorties,
            ];
        })->toArray();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
         return new Envelope(
            subject: 'Rapport de Production — ' .
                \Carbon\Carbon::parse($this->date_production)->translatedFormat('d F Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.production.journaliere',
            with: [
                'date_production'    => $this->date_production,
                'superviseur'        => $this->superviseur,
                'heure_generation'   => $this->heure_generation,
                'nombre_productions' => $this->nombre_productions,
                'volume_total'       => $this->volume_total,
                'productions'        => $this->productions,
                'pdf_url'            => $this->pdf_url,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (!file_exists($this->pdf_path)) {
            return [];
        }

        return [
            Attachment::fromPath($this->pdf_path)
                ->as(
                    'rapport_production_' .
                    \Carbon\Carbon::parse($this->date_production)->format('Y-m-d') .
                    '.pdf'
                )
                ->withMime('application/pdf'),
        ];
    }
}
