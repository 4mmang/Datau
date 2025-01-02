<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Dataset;
use App\Models\SubjectArea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $countDataset = Dataset::all()->count();
        $countUser = User::where('role', 'user')->count();
        $countArticle = Article::all()->count();

        $subjectAreas = SubjectArea::all();
        $data = [];
        foreach ($subjectAreas as $subjectArea) {
            if (Auth::user()->role != 'admin') {
                $datasetCount = Dataset::where('id_subject_area', $subjectArea->id)->where('id_user', Auth::user()->id)->count();
            }else{
                $datasetCount = Dataset::where('id_subject_area', $subjectArea->id)->count();
            }
            array_push($data, $datasetCount);
        }
        return view('admin.dashboard', compact(['countDataset', 'countUser', 'countArticle', 'subjectAreas', 'data']));
    }
}
