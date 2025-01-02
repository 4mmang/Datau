<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssociatedTask;
use App\Models\Characteristic;
use App\Models\Dataset;
use App\Models\DatasetAssociatedTask;
use App\Models\DatasetCharacteristic;
use App\Models\DatasetFeatureType;
use App\Models\Download;
use App\Models\FeatureType;
use App\Models\Paper;
use App\Models\SubjectArea;
use App\Models\UrlFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KelolaDatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::all();
        $user = Auth::user();
        if ($user->role != 'admin') {
            $datasets = Dataset::where('id_user', $user->id)->get();
        }
        return view('admin.dataset.index', compact(['datasets']));
    }

    public function show($id)
    {
        $user = Auth::user();
        $dataset = Dataset::with('featuresType.feature', 'characteristics.characteristic', 'associatedTask.associated')->findOrFail($id);
        if ($user->role != 'admin') {
            $dataset = Dataset::where('id', $id)
                ->where('id_user', $user->id)
                ->firstOrFail();
        }
        $papers = Paper::where('id_dataset', $id)->get();

        // Lokasi folder tempat dataset disimpan
        $folderPath = 'public/datasets/' . $id;
        $files = Storage::files($folderPath);

        return view('admin.dataset.show', compact(['dataset', 'papers', 'id', 'files']));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $dataset = Dataset::findOrFail($id);
            if ($user->role != 'admin') {
                $dataset = Dataset::where('id', $id)
                    ->where('id_user', $user->id)
                    ->firstOrFail();
            }

            $id = $dataset->id;
            $dataset->delete();

            $characteristics = DatasetCharacteristic::where('id_dataset', $id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }

            $associatedTasks = DatasetAssociatedTask::where('id_dataset', $id)->get();
            foreach ($associatedTasks as $associatedTask) {
                $associatedTask->delete();
            }

            $featureTypes = DatasetFeatureType::where('id_dataset', $id)->get();
            foreach ($featureTypes as $featureType) {
                $featureType->delete();
            }

            $downloads = Download::where('id_dataset', $id)->get();
            foreach ($downloads as $download) {
                $download->delete();
            }

            $papers = Paper::where('id_dataset', $id)->get();
            foreach ($papers as $paper) {
                $paper->delete();
            }

            $urlFiles = UrlFile::where('id_dataset', $id)->get();
            foreach ($urlFiles as $urlFile) {
                // Storage::delete('public/' . $urlFile->url_file);
                $urlFile->delete();
            }
            Storage::deleteDirectory('public/datasets/' . $id);

            DB::commit();
            $datasets = Dataset::with('user')->get();
            // $datasets = Dataset::join('users', 'users.id', '=', 'datasets.id_user')->select('datasets.id', 'name', 'full_name', 'datasets.status', 'note')->get();
            return response()->json([
                'status' => 200,
                'message' => 'Deleted successfully',
                'datasets' => $datasets,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        if ($user->status === 'off') {
            return redirect()->route('admin.dataset.index');
        }
        $characteristics = Characteristic::all();
        $subjectAreas = SubjectArea::all();
        $associatedTasks = AssociatedTask::all();
        $featureTypes = FeatureType::all();

        $dataset = Dataset::findOrFail($id);
        if ($user->role != 'admin') {
            $dataset = Dataset::where('id', $id)
                ->where('id_user', $user->id)
                ->firstOrFail();
        }
        // $dataset = Dataset::leftJoin('subject_areas', 'subject_areas.id', '=', 'datasets.id_subject_area')->select('datasets.id as id_dataset', 'datasets.*', 'subject_areas.*')->find($id);
        $datasetCharacteristics = DatasetCharacteristic::join('characteristics', 'characteristics.id', '=', 'dataset_characteristics.id_characteristic')->where('id_dataset', $id)->get();
        $datasetFeatureTypes = DatasetFeatureType::join('feature_types', 'feature_types.id', '=', 'dataset_feature_types.id_feature_type')->where('id_dataset', $id)->get();
        $datasetAssociatedTasks = DatasetAssociatedTask::join('associated_tasks', 'associated_tasks.id', '=', 'dataset_associated_tasks.id_associated_task')->where('id_dataset', $id)->get();
        return view('admin.dataset.edit', compact('characteristics', 'datasetCharacteristics', 'dataset', 'subjectAreas', 'associatedTasks', 'featureTypes', 'datasetFeatureTypes', 'datasetAssociatedTasks', 'id'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user->status === 'off') {
                return redirect()->route('admin.dataset.index');
            }
            $dataset = Dataset::findOrFail($id);
            if ($user->role != 'admin') {
                $dataset = Dataset::where('id', $id)
                    ->where('id_user', $user->id)
                    ->firstOrFail();
            }
            $dataset->abstract = $request->abstract ?? '-';
            $dataset->id_subject_area = $request->subjectArea;
            $dataset->information = $request->information ?? '-';
            $dataset->update();

            $oldCharacteristic = DatasetCharacteristic::where('id_dataset', $id)->get();
            if ($oldCharacteristic) {
                foreach ($oldCharacteristic as $value) {
                    $value->delete();
                }
            }
            if ($request->characteristics) {
                foreach ($request->characteristics as $characteristic) {
                    $newCharacteristic = new DatasetCharacteristic();
                    $newCharacteristic->id_dataset = $id;
                    $newCharacteristic->id_characteristic = $characteristic;
                    $newCharacteristic->save();
                }
            }

            $oldAssociatedTasks = DatasetAssociatedTask::where('id_dataset', $id)->get();
            if ($oldAssociatedTasks) {
                foreach ($oldAssociatedTasks as $value) {
                    $value->delete();
                }
            }
            if ($request->associatedTasks) {
                foreach ($request->associatedTasks as $associatedTask) {
                    $newAssociatedTask = new DatasetAssociatedTask();
                    $newAssociatedTask->id_dataset = $id;
                    $newAssociatedTask->id_associated_task = $associatedTask;
                    $newAssociatedTask->save();
                }
            }

            $oldFeatureType = DatasetFeatureType::where('id_dataset', $id)->get();
            if ($oldFeatureType) {
                foreach ($oldFeatureType as $value) {
                    $value->delete();
                }
            }
            if ($request->featureTypes) {
                foreach ($request->featureTypes as $featureType) {
                    $newfeatureType = new DatasetFeatureType();
                    $newfeatureType->id_dataset = $id;
                    $newfeatureType->id_feature_type = $featureType;
                    $newfeatureType->save();
                }
            }

            DB::commit();
            return back()->with([
                'message' => 'Successfully updated dataset',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return back()->withErrors([
                'message' => 'There is an error',
            ]);
        }
    }
}
