<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class PhotoController extends Controller
{
    public function index()
    {
        try {
            return view('pages.photos', ['photos' => Photo::latest()->get()]);
        } catch (Throwable $e) {
            return redirect('/photos')->with('error', 'No se pudieron cargar las imágenes.');
        }
    }

    public function store(Request $request)
    {
        $imagePath = null;

        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            $imagePath = $request->file('image')->store('photos', 'public');

            Photo::create([
                'name' => $data['name'],
                'image_path' => $imagePath,
            ]);

            return back()->with('success', 'Imagen subida correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->with('error', 'No se pudo subir la imagen.');
        }
    }

    public function update(Request $request, Photo $photo)
    {
        $newImagePath = null;
        $updated = false;

        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            $newImagePath = $request->file('image')->store('photos', 'public');
            $oldImagePath = $photo->image_path;

            $photo->update([
                'name' => $data['name'],
                'image_path' => $newImagePath,
            ]);

            $updated = true;
            Storage::disk('public')->delete($oldImagePath);

            return back()->with('success', 'Imagen reemplazada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            if ($newImagePath && ! $updated) {
                Storage::disk('public')->delete($newImagePath);
            }

            return back()->with('error', 'No se pudo reemplazar la imagen.');
        }
    }

    public function destroy(Photo $photo)
    {
        try {
            $imagePath = $photo->image_path;

            $photo->delete();
            Storage::disk('public')->delete($imagePath);

            return back()->with('success', 'Imagen eliminada correctamente.');
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo eliminar la imagen.');
        }
    }
}