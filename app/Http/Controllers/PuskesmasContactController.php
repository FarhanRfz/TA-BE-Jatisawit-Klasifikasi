<?php

namespace App\Http\Controllers;

use App\Models\PuskesmasContact;
use Illuminate\Http\Request;

class PuskesmasContactController extends Controller
{
    public function index()
    {
        return PuskesmasContact::all();
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'jenis_kontak' => 'required|in:Facebook,Instagram,Tiktok',
            'link_kontak' => 'required|url'
        ]);

        return PuskesmasContact::create($validated);
    }

    public function show($id_contacts)
    {
        return PuskesmasContact::findOrFail($id_contacts);
    }

    public function update(Request $request, $id_contacts)
    {
        $kontak = PuskesmasContact::findOrFail($id_contacts);

        $validated = $request->validate([
            'jenis_kontak' => 'string|max:100',
            'link_kontak' => 'string|max:255',
        ]);

        $kontak->update($validated);
        return $kontak;
    }

    public function destroy($id_contacts)
    {
        $kontak = PuskesmasContact::findOrFail($id_contacts);
        $kontak->delete();
        return response()->json(['message' => 'Kontak deleted successfully']);
    }

}
