@extends('home')

@section('title', 'Gestor de documentos')

@section('content')
    <h1>Gestor de documentos PDF</h1>

    <h2>Subir documento</h2>

    <form
        action="{{ route('docs.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <label>
            Título
            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                required
            >
        </label>

        <label>
            Archivo PDF
            <input
                type="file"
                name="document"
                accept="application/pdf"
                required
            >
        </label>

        <button type="submit">Subir documento</button>
    </form>

    <h2>Documentos guardados</h2>

    @forelse ($docs as $doc)
        <article class="doc-card">
            <div class="doc-card__header">
                <h3>
                    <a
                        href="{{ asset('storage/'.$doc->doc_path) }}"
                        target="_blank"
                        rel="noopener"
                    >
                        {{ $doc->title }}
                    </a>
                </h3>

                <form action="{{ route('docs.destroy', $doc) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar documento</button>
                </form>
            </div>

            <form
                class="doc-card__update"
                action="{{ route('docs.update', $doc) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <label>
                    Título
                    <input type="text" name="title" value="{{ $doc->title }}" required>
                </label>

                <label>
                    Nuevo PDF
                    <input type="file" name="document" accept="application/pdf" required>
                </label>

                <button type="submit">Reemplazar documento</button>
            </form>
        </article>
    @empty
        <p>Aún no hay documentos.</p>
    @endforelse
@endsection