<?php

namespace App\Http\Controllers;

use App\Models\AssociatedTask;
use App\Models\Characteristic;
use App\Models\Dataset;
use App\Models\DatasetAssociatedTask;
use App\Models\DatasetCharacteristic;
use App\Models\DatasetFeatureType;
use App\Models\FeatureType;
use App\Models\SubjectArea;
use App\Models\UrlFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContributeDatasetController extends Controller
{
    public function create()
    {
        if (Auth::user()->status != 'on') {
            return view('info.akun-off');
        }
        $myDataset = Dataset::where('id_user', Auth::user()->id)
            ->where('status', 'pending')
            ->first();

        if (optional($myDataset)->count() > 0) {
            return view('info.pending-dataset');
        }

        $characteristics = Characteristic::all();
        $subjectAreas = SubjectArea::all();
        $associatedTasks = AssociatedTask::all();
        $featureTypes = FeatureType::all();
        return view('dataset.create', compact(['characteristics', 'subjectAreas', 'associatedTasks', 'featureTypes', 'myDataset']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'file' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $dataset = new Dataset();
            $dataset->id_user = Auth::user()->id;
            $dataset->id_subject_area = $request->subjectArea ?? 11;
            $dataset->name = $request->name;
            $dataset->abstract = $request->abstract;
            $dataset->instances = $request->instances;
            $dataset->features = $request->features;
            $dataset->information = $request->information;
            $dataset->save();

            foreach ($request->file('file') as $file) {
                $urlFiles = new UrlFile();
                $urlFiles->id_dataset = $dataset->id;
                $path = $file->storeAs('public/datasets/' . $dataset->id, $file->getClientOriginalName());
                $urlFiles->url_file = str_replace('public/', '', $path);
                $urlFiles->save();
            }

            if ($request->characteristics) {
                foreach ($request->characteristics as $characteristic) {
                    $newCharacteristic = new DatasetCharacteristic();
                    $newCharacteristic->id_dataset = $dataset->id;
                    $newCharacteristic->id_characteristic = $characteristic;
                    $newCharacteristic->save();
                }
            }

            if ($request->associatedTasks) {
                foreach ($request->associatedTasks as $associatedTasks) {
                    $newAssociatedTasks = new DatasetAssociatedTask();
                    $newAssociatedTasks->id_dataset = $dataset->id;
                    $newAssociatedTasks->id_associated_task = $associatedTasks;
                    $newAssociatedTasks->save();
                }
            }

            if ($request->featureTypes) {
                foreach ($request->featureTypes as $featureType) {
                    $newfeatureType = new DatasetFeatureType();
                    $newfeatureType->id_dataset = $dataset->id;
                    $newfeatureType->id_feature_type = $featureType;
                    $newfeatureType->save();
                }
            }

            DB::commit();

            return back()->with([
                'message' => 'Dataset berhasil ditambahkan',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors([
                // 'error' => 'Terjadi kesalahan'
                'error' => $th->getMessage(),
            ]);
        }
    }
}
