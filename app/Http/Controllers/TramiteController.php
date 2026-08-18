<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TramiteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $alumno = $request->user()->loadMissing('alumno')->alumno;
        abort_unless($alumno, 403, 'La cuenta no está vinculada con un alumno.');

        $data = $request->validate([
            'documento' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        $tramite = Tramite::firstOrNew(['alumno_id' => $alumno->id]);
        $previousPath = $tramite->ruta_pdf;
        $path = $data['documento']->store('tramites');

        $tramite->fill([
            'ruta_pdf' => $path,
            'estatus' => 'Pendiente',
            'observaciones' => null,
        ])->save();

        if ($previousPath && $previousPath !== $path) {
            Storage::delete($previousPath);
        }

        return back()->with('status', 'Documento enviado para revisión.');
    }

    public function update(Request $request, Tramite $tramite): RedirectResponse
    {
        $data = $request->validate([
            'estatus' => ['required', Rule::in(['Aceptado', 'Rechazado'])],
            'observaciones' => ['nullable', 'required_if:estatus,Rechazado', 'string', 'max:2000'],
        ]);

        $tramite->update([
            'estatus' => $data['estatus'],
            'observaciones' => $data['estatus'] === 'Rechazado' ? $data['observaciones'] : null,
        ]);

        return back()->with('status', 'Trámite actualizado correctamente.');
    }

    public function download(Request $request, Tramite $tramite): StreamedResponse
    {
        $user = $request->user()->loadMissing('alumno');
        $allowed = $user->hasAnyRole('Administrador', 'Director')
            || ($user->rol === 'Alumno' && $user->alumno?->id === $tramite->alumno_id);
        abort_unless($allowed, 403);
        abort_unless(Storage::exists($tramite->ruta_pdf), 404);

        return Storage::download($tramite->ruta_pdf, "tramite-{$tramite->id}.pdf");
    }
}
