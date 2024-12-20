<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;

class ManageDatasetsController extends Controller
{
    public function index()
    {
        $datasets = Dataset::join('users', 'users.id', '=', 'datasets.id_user')->select('datasets.id', 'name', 'full_name', 'datasets.status', 'note')->get();
        return view('admin.manage-datasets.index', compact(['datasets']));
    }

    public function show($id)
    {
        $dataset = Dataset::join('users', 'users.id', '=', 'datasets.id_user')->findOrFail($id);
        $papers = Paper::where('id_dataset', $id)->get();

        $desiredRowCount = 6;

        // Lokasi folder tempat dataset disimpan
        $folderPath = 'public/datasets/' . $id;
        $files = Storage::files($folderPath);
        $data = [];

        foreach ($files as $file) {
            // Periksa apakah file memiliki ekstensi .csv
            if (pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
                $filePath = Storage::path($file);

                // Buat instance dari Reader
                $csv = Reader::createFromPath($filePath, 'r');
                $stmt = (new Statement())->offset(0)->limit($desiredRowCount);

                // Proses data CSV
                $records = $stmt->process($csv);

                // Baca header dari file CSV
                $headers = $csv->fetchOne();

                // Simpan data ke dalam array
                $data[] = [
                    'fileName' => basename($file),
                    'records' => $records,
                ];
            }
        }

        return view('admin.manage-datasets.detail-dataset', compact(['dataset', 'papers', 'id', 'data', 'files']));
    }

    public function valid($id)
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->status = 'valid';
        $dataset->note = '-';
        $dataset->update();
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function invalid(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
            ]);
        }

        $dataset = Dataset::findOrFail($id);
        $dataset->status = 'invalid';
        $dataset->note = $request->note;
        $dataset->update();
        return response()->json([
            'status' => 200,
            'message' => 'invalid',
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $dataset = Dataset::findOrFail($id);
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
                Storage::delete('public/' . $urlFile->url_file);
                $urlFile->delete();
            }
            DB::commit();
            $datasets = Dataset::join('users', 'users.id', '=', 'datasets.id_user')->select('datasets.id', 'name', 'full_name', 'status', 'note')->get();
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
        $characteristics = Characteristic::all();
        $subjectAreas = SubjectArea::all();
        $associatedTasks = AssociatedTask::all();
        $featureTypes = FeatureType::all();

        $dataset = Dataset::leftJoin('subject_areas', 'subject_areas.id', '=', 'datasets.id_subject_area')->select('datasets.id as id_dataset', 'datasets.*', 'subject_areas.*')->find($id);
        $datasetCharacteristics = DatasetCharacteristic::join('characteristics', 'characteristics.id', '=', 'dataset_characteristics.id_characteristic')->where('id_dataset', $id)->get();
        $datasetFeatureTypes = DatasetFeatureType::join('feature_types', 'feature_types.id', '=', 'dataset_feature_types.id_feature_type')->where('id_dataset', $id)->get();
        $datasetAssociatedTasks = DatasetAssociatedTask::join('associated_tasks', 'associated_tasks.id', '=', 'dataset_associated_tasks.id_associated_task')->where('id_dataset', $id)->get();
        return view('admin.manage-datasets.edit', compact('characteristics', 'datasetCharacteristics', 'dataset', 'subjectAreas', 'associatedTasks', 'featureTypes', 'datasetFeatureTypes', 'datasetAssociatedTasks', 'id'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataset = Dataset::findOrFail($id);
            $dataset->id_subject_area = $request->subjectArea;

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
