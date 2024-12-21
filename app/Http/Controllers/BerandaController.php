<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Dataset;
use App\Models\Download;
use App\Models\SubjectArea;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $subjectAreas = SubjectArea::all();
        $data = [];
        foreach ($subjectAreas as $subjectArea) {
            $datasetCount = Dataset::where('id_subject_area', $subjectArea->id)->count();
            array_push($data, $datasetCount);
        }

        $datasets = Dataset::where('status', 'valid')->get();
        $countDownloads = [];
        foreach ($datasets as $dataset) {
            $downloads = Download::where('id_dataset', $dataset->id)->get();
            foreach ($downloads as $download) {
                $countDownloads[] = $dataset->id;
            }
        }

        $count = 0;
        $popularDataset = null;

        // // Check if $countDownloads is not empty before using max
        if (!empty($countDownloads)) {
            $count = max(array_count_values($countDownloads));

            // Menghitung berapa kali setiap nilai muncul dalam array
            $counts = array_count_values($countDownloads);

            // Menentukan nilai yang paling banyak muncul
            $maxCount = max($counts);

            // Mendapatkan nilai yang paling banyak muncul
            $mostCommonValue = array_search($maxCount, $counts);

            $popularDataset = Dataset::findOrFail($mostCommonValue);
        }

        $newArticles = Article::latest()->take(6)->get();
        // return view('welcome', compact(['dataset', 'countDownloads', 'popularDataset', 'newDataset']));
        return view('beranda', compact(['subjectAreas', 'data', 'count', 'popularDataset', 'newArticles']));
    }
}
