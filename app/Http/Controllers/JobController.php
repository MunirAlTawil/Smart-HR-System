<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::where('status', 'active');

        if ($request->has('department') && $request->department) {
            $query->where('department', $request->department);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $jobs = $query->latest()->paginate(12);
        $departments = Job::distinct()->pluck('department')->unique();
        $types = Job::distinct()->pluck('type')->unique();

        return view('jobs', compact('jobs', 'departments', 'types'));
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        return view('job-details', compact('job'));
    }
}
