<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Treatment;

class ReportController extends Controller
{
    public function activeTreatmentsReport(Request $request)
    {
        $user = $request->user();
        $treatments = Treatment::where('user_id', $user->id)->where('is_active', true)->get();

        $pdf = Pdf::loadView('reports.active_treatments', compact('treatments'));
        return $pdf->download('active_treatments.pdf');
    }

    public function historyReport(Request $request)
    {
        $user = $request->user();
        $treatments = Treatment::where('user_id', $user->id)->where('is_active', false)->get();

        $pdf = Pdf::loadView('reports.history', compact('treatments'));
        return $pdf->download('treatment_history.pdf');
    }

    public function stockReport(Request $request)
    {
        $user = $request->user();
        $treatments = Treatment::where('user_id', $user->id)->get();

        $pdf = Pdf::loadView('reports.stock', compact('treatments'));
        return $pdf->download('stock_report.pdf');
    }
}

