<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;   // ← tambahkan ini
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan & tahun dari query ?month=...&year=...
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        // Gunakan Carbon custom
        $selectedDate = \Carbon\Carbon::createFromDate($year, $month, 1);

        $currentMonth = $selectedDate->month;
        $currentYear =  $selectedDate->year;
        $monthName = $selectedDate->translatedFormat('F Y');
        $daysInMonth = $selectedDate->daysInMonth;
        $startDay = $selectedDate->dayOfWeek; // posisi hari 1

        // Hitung bulan sebelumnya & berikutnya
        $prevMonth = $selectedDate->copy()->subMonth();
        $nextMonth = $selectedDate->copy()->addMonth();



        return view('events.index', compact(
            'currentMonth', 'currentYear', 'daysInMonth',
            'startDay', 'monthName'
        ));
    }


    public function create()
    {
        return view('events.create');
    }

    public function createRoutine()
    {
        return view('events.create-routine');
    }

    public function show($id)
    {
        return view('events.show', ['eventId' => $id]);
    }

    public function attendance($id)
    {
        return view('events.attendance', ['eventId' => $id]);
    }
}
