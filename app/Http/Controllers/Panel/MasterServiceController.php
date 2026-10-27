<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\GuidanceField;
use App\Models\ServiceFunction;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MasterServiceController extends Controller
{
    public function index(): View
    {
        $services = ServiceType::layanan()->get();
        $supportings = ServiceType::pendukung()->get();
        $fields = GuidanceField::all();
        $functions = ServiceFunction::all();
        $isAdmin = Auth::user()->isAdmin();

        return view('panel.layanan.index', compact(
            'services',
            'supportings',
            'fields',
            'functions',
            'isAdmin'
        ));
    }

    public function storeService(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:service_types,code'],
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'in:layanan,pendukung'],
            'description' => ['nullable', 'string'],
        ]);

        ServiceType::create($request->all());

        return back()->with('success', 'Layanan / Kegiatan Pendukung BK berhasil ditambahkan.');
    }

    public function updateService(Request $request, ServiceType $service): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);

        $service->update($request->only('name', 'description'));

        return back()->with('success', 'Data layanan berhasil diperbarui.');
    }
}
