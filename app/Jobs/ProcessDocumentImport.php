<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDocumentImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $documentData;

    public function __construct(array $documentData)
    {
        $this->documentData = $documentData;
    }

    public function handle()
    {
        // Adiciona lógica para salvar o documento na tabela
        Document::create([
            'title' => $this->documentData['titulo'],
            'content' => $this->documentData['conteúdo'],
            'category_id' => Category::where('name', $this->documentData['categoria'])->first()->id,
        ]);
    }
}

