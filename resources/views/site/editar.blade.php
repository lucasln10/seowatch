<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Site</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-10">

    <div class="max-w-xl mx-auto bg-white p-8 shadow-md rounded">
        <h1 class="text-2xl font-bold mb-6 text-center">Editar Site</h1>

        {{-- Mensagens de erro --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('site/editar/' . $site->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block font-semibold mb-1">Título (opcional):</label>
                <input type="text" id="title" name="title" class="w-full border border-gray-300 p-2 rounded"
                    value="{{ old('title', $site->title) }}">
            </div>

            <div>
                <label for="url" class="block font-semibold mb-1">URL:</label>
                <input type="url" id="url" name="url" class="w-full border border-gray-300 p-2 rounded"
                    value="{{ old('url', $site->url) }}" required>
                    <script>
                        const urlInput = document.getElementById('url');

                        urlInput.addEventListener('blur', function () {
                            if (this.value && !this.value.startsWith('http://') && !this.value.startsWith('https://')) {
                                this.value = 'https://' + this.value;
                            }
                        });
                    </script>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Salvar Alterações
                </button>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </form>
    </div>

</body>
</html>