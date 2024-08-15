<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDocumentImport;
use Illuminate\Http\Request;
use App\Jobs\ProcessJsonImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class DocumentImportController extends Controller
{
    public function showImportForm()
    {
        return view('documents.import');
    }

    public function import(Request $request)
    {
        // Valida o arquivo JSON
        $request->validate([
            'import_file' => 'required|file|mimetypes:application/json',
        ]);

        // Salva o arquivo no storage
        $filePath = $request->file('import_file')->store('data');

        // Adiciona cada registro na fila para processamento
        $fileContent = Storage::get($filePath);
        $documents = json_decode($fileContent, true);

        foreach ($documents['documentos'] as $document) {
            ProcessDocumentImport::dispatch($document);
        }

        return redirect()->route('documents.import.form')->with('success', 'Documentos importados para a fila com sucesso!');
    }

    public function processQueue()
    {
        Process::run('php artisan queue:work --daemon > /dev/null 2>&1 &');

        return redirect()->route('documents.import.form')->with('success', 'Processamento da fila iniciado!');
    }
}
