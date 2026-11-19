@extends('home')

@section('title', 'Galería de imágenes')

@section('content')
    <h1>Galería de imágenes</h1>

    <h2>Subir imagen</h2>

    <form
        action="{{ route('photos.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <label>
            Título
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </label>

        <label>
            Imagen
            <input
                type="file"
                name="image"
                accept="image/*"
                required
            >
        </label>

        <button type="submit">Subir imagen</button>
    </form>

    <h2>Imágenes guardadas</h2>

    @forelse ($photos as $photo)
        <article class="photo-card">
            <div class="photo-card__header">
                <h3>{{ $photo->name }}</h3>

                <form action="{{ route('photos.destroy', $photo) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar imagen</button>
                </form>
            </div>

            <div class="photo-card__body">
                <img
                    src="{{ asset('storage/'.$photo->image_path) }}"
                    alt="{{ $photo->name }}"
                >

                <form
                    action="{{ route('photos.update', $photo) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    <label>
                        Título
                        <input type="text" name="name" value="{{ $photo->name }}" required>
                    </label>

                    <label>
                        Nueva imagen
                        <input type="file" name="image" accept="image/*" required>
                    </label>

                    <button type="submit">Reemplazar imagen</button>
                </form>
            </div>
        </article>
    @empty
        <p>Aún no hay imágenes.</p>
    @endforelse
@endsection