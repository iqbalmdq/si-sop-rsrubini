<?php

namespace App\Http\Controllers;

use App\Models\KategoriSop;
use App\Models\Sop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicSopController extends Controller
{
    public function index(): View
    {
        $kategoris = KategoriSop::where('is_active', true)
            ->withCount('sops')
            ->orderBy('nama_kategori')
            ->get();

        $totalSop = Sop::where('status', 'aktif')->count();

        return view('public.sop.index', compact('kategoris', 'totalSop'));
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $kategori = $request->get('kategori', '');
        $bidang = $request->get('bidang', '');

        $sops = Sop::with(['kategori', 'creator'])
            ->where('status', 'aktif')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery
                        ->where('nomor_sop', 'like', "%{$query}%")
                        ->orWhere('judul_sop', 'like', "%{$query}%")
                        ->orWhere('deskripsi', 'like', "%{$query}%")
                        ->orWhere('isi_sop', 'like', "%{$query}%");
                });
            })
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('kategori_id', $kategori);
            })
            ->when($bidang, function ($q) use ($bidang) {
                $q->where('bidang_bagian', $bidang);
            })
            ->orderBy('nomor_sop')
            ->paginate(10);

        return response()->json([
            'data' => $sops->items(),
            'pagination' => [
                'current_page' => $sops->currentPage(),
                'last_page' => $sops->lastPage(),
                'per_page' => $sops->perPage(),
                'total' => $sops->total(),
            ]
        ]);
    }

    public function showByParts($type, $category, $number, $year): View
    {
        $nomor_sop = "{$type}/{$category}/{$number}/{$year}";
        $sop = Sop::where('nomor_sop', $nomor_sop)->where('status', 'aktif')->firstOrFail();

        $sop->load(['kategori', 'creator']);

        // SOP terkait dari kategori yang sama
        $relatedSops = Sop::where('kategori_id', $sop->kategori_id)
            ->where('id', '!=', $sop->id)
            ->where('status', 'aktif')
            ->limit(5)
            ->get();

        return view('public.sop.show', compact('sop', 'relatedSops'));
    }

    public function download(Sop $sop)
    {
        if ($sop->status !== 'aktif' || !$sop->file_path) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $sop->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Sanitize filename by replacing slashes with dashes
        $filename = str_replace(['/', '\\'], '-', $sop->nomor_sop) . '.pdf';

        return response()->download($filePath, $filename);
    }

    public function downloadByParts($type, $category, $number, $year)
    {
        $nomor_sop = "{$type}/{$category}/{$number}/{$year}";
        $sop = Sop::where('nomor_sop', $nomor_sop)->where('status', 'aktif')->firstOrFail();

        if (!$sop->file_path) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $sop->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Sanitize filename by replacing slashes with dashes
        $filename = str_replace(['/', '\\'], '-', $sop->nomor_sop) . '.pdf';

        return response()->download($filePath, $filename);
    }

    public function getBidangList(): JsonResponse
    {
        $bidangs = Sop::where('status', 'aktif')
            ->distinct()
            ->pluck('bidang_bagian')
            ->sort()
            ->values();

        return response()->json($bidangs);
    }
}
