<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Services\Welfare\MflsPartnerDocumentService;
use Illuminate\Http\Request;

class MflsPartnerDocumentController extends Controller
{
    public function preview(string $partnerId, MflsPartnerDocumentService $documents)
    {
        $document = $documents->findForPartner($partnerId);

        if (!$document || !is_file($documents->absolutePath($document))) {
            abort(404, 'Programme information is not available for this partner.');
        }

        $payload = $documents->buildPreviewPayload($document);

        return response()->json([
            'title' => ($documents->partnerName($partnerId) ?: 'Partner') . ' Programme Information',
            'styles' => $payload['styles'],
            'html' => $payload['html'],
            'download_url' => route('welfare.impact.mfls.partner-programme-info.download', [
                'partnerId' => $partnerId,
                'v' => $document->updated_at ? $document->updated_at->timestamp : time(),
            ]),
            'updated_at' => optional($document->updated_at)->toIso8601String(),
        ]);
    }

    public function viewHtml(string $partnerId, MflsPartnerDocumentService $documents)
    {
        $document = $documents->findForPartner($partnerId);

        if (!$document || !is_file($documents->absolutePath($document))) {
            abort(404, 'Programme information is not available for this partner.');
        }

        $payload = $documents->buildPreviewPayload($document);

        return response()->view('welfare.admin.mfls-partner-document-preview', [
            'title' => ($documents->partnerName($partnerId) ?: 'Partner') . ' Programme Information',
            'styles' => $payload['styles'],
            'html' => $payload['html'],
        ]);
    }

    public function download(string $partnerId, MflsPartnerDocumentService $documents)
    {
        $document = $documents->findForPartner($partnerId);

        if (!$document) {
            abort(404, 'Programme information is not available for this partner.');
        }

        return $documents->downloadResponse($document);
    }

    public function upload(Request $request, string $partnerId, MflsPartnerDocumentService $documents)
    {
        if (!$documents->isValidPartnerId($partnerId)) {
            abort(404);
        }

        $request->validate([
            'programme_file' => 'required|file|mimes:xlsx,xls|max:20480',
        ]);

        $documents->storeUpload($partnerId, $request->file('programme_file'));

        return redirect()
            ->route('welfare.admin.dashboard')
            ->with('admin_tab', 'panel-mfls-documents')
            ->with('success', 'Programme information updated for ' . ($documents->partnerName($partnerId) ?: $partnerId) . '.');
    }
}
