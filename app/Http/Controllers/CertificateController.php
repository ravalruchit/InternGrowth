<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateController extends Controller
{
    public function download($id)
    {
        $certificate = Certificate::with(['student.user', 'task.startup', 'hiringOffer.startup'])->findOrFail($id);
        return view('certificates.download', compact('certificate'));
    }

    public function verify($certificateNumber)
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['student.user', 'task.startup', 'hiringOffer.startup'])
            ->firstOrFail();
        
        return view('certificates.verify', compact('certificate'));
    }
}
