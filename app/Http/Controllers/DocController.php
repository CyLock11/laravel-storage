<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class DocController extends Controller
{
    public function index()
    {
        try {
            return view('pages.docs', ['docs' => Doc::latest()->get()]);
        } catch (Throwable $e) {
            return redirect('/docs')->with('error', 'No se pudieron cargar los documentos.');
        }
    }

    public function store(Request $request)
    {
        $docPath = null;

        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'document' => 'required|file|mimes:pdf|max:10240',
            ]);

            $docPath = $request->file('document')->store('docs', 'public');

            Doc::create([
                'title' => $data['title'],
                'doc_path' => $docPath,
            ]);

            return back()->with('success', 'Documento subido correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            if ($docPath) {
                Storage::disk('public')->delete($docPath);
            }

            return back()->with('error', 'No se pudo subir el documento.');
        }
    }

    public function update(Request $request, Doc $doc)
    {
        $newDocPath = null;
        $updated = false;

        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'document' => 'required|file|mimes:pdf|max:10240',
            ]);

            $newDocPath = $request->file('document')->store('docs', 'public');
            $oldDocPath = $doc->doc_path;

            $doc->update([
                'title' => $data['title'],
                'doc_path' => $newDocPath,
            ]);

            $updated = true;
            Storage::disk('public')->delete($oldDocPath);

            return back()->with('success', 'Documento reemplazado correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            if ($newDocPath && ! $updated) {
                Storage::disk('public')->delete($newDocPath);
            }

            return back()->with('error', 'No se pudo reemplazar el documento.');
        }
    }

    public function destroy(Doc $doc)
    {
        try {
            $docPath = $doc->doc_path;

            $doc->delete();
            Storage::disk('public')->delete($docPath);

            return back()->with('success', 'Documento eliminado correctamente.');
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo eliminar el documento.');
        }
    }
}