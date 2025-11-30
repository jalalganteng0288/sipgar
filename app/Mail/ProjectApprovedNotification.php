<?php

namespace App\Mail;

use App\Models\HousingProject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Proyek Perumahan yang disetujui.
     * @var HousingProject
     */
    public $project;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(HousingProject $project)
    {
        $this->project = $project;
    }

    /**
     * Dapatkan amplop pesan (subjek dan pengirim).
     */
    public function envelope(): Envelope
    {
        // Subjek yang jelas
        return new Envelope(
            subject: 'Persetujuan Proyek Perumahan: ' . $this->project->name,
        );
    }

    /**
     * Dapatkan definisi konten pesan.
     */
    public function content(): Content
    {
        // Mengarahkan ke template Blade email
        return new Content(
            markdown: 'emails.projects.approved',
            with: [
                'projectName' => $this->project->name,
                'projectStatus' => $this->project->status,
                'projectUrl' => route('projects.show', $this->project->id), // Menggunakan rute publik
            ],
        );
    }

    /**
     * Dapatkan array lampiran untuk pesan.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}