<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CompanyLogoController extends Controller
{
    public function store(Request $request, Company $company)
    {
        // Access check (adjust to your policy)
        if ($company->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'logo' => 'required|image|max:2048', // 2MB
        ]);

        // Delete old logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $file = $request->file('logo');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('companies/logos', $filename, 'public');

        $company->update(['logo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Logo uploaded.',
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    public function destroy(Request $request, Company $company)
    {
        if ($company->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
            $company->update(['logo' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo removed.',
        ]);
    }
}
