<!DOCTYPE html>
<html>
<head>
    <title>Importação de Documentos</title>
</head>
<body>

@if (session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

<h1>Importar Documentos</h1>

<form action="{{ route('documents.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="import_file">Selecione o arquivo JSON:</label>
    <input type="file" name="import_file" id="import_file" required>

    @error('import_file')
    <div style="color: red;">
        {{ $message }}
    </div>
    @enderror

    <button type="submit">Importar para Fila</button>
</form>

<h2>Processar Fila de Documentos</h2>

<form action="{{ route('documents.process.queue') }}" method="POST">
    @csrf
    <button type="submit">Processar Fila</button>
</form>

</body>
</html>
