<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $timelines = Timeline::orderBy('order')->orderBy('start_date')->get();
        return view('admin.timeline.index', [
            'title' => 'Timeline Kegiatan',
            'timelines' => $timelines
        ]);
    }

    public function create()
    {
        return view('admin.timeline.create', [
            'title' => 'Tambah Timeline'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'order' => 'required|integer',
            'status' => 'required|in:upcoming,active,completed'
        ]);

        Timeline::create($validated);

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline berhasil ditambahkan.');
    }

    public function edit(Timeline $timeline)
    {
        return view('admin.timeline.edit', [
            'title' => 'Edit Timeline',
            'timeline' => $timeline
        ]);
    }

    public function update(Request $request, Timeline $timeline)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'order' => 'required|integer',
            'status' => 'required|in:upcoming,active,completed'
        ]);

        $timeline->update($validated);

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline berhasil diperbarui.');
    }

    public function destroy(Timeline $timeline)
    {
        $timeline->delete();
        return redirect()->route('admin.timeline.index')->with('success', 'Timeline berhasil dihapus.');
    }
}
