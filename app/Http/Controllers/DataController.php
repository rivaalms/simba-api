<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\DataType;
use App\Models\DataStatus;
use App\Models\DataCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
   public function get(Request $request) {
      $data = Data::filter(request(['school', 'type', 'category', 'status', 'year']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($data);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'school_id' => 'required',
         'year' => 'required',
         'data_type_id' => 'required',
         'data_status_id' => 'required',
      ]);

      $extension = $request->file('file')->getClientOriginalExtension();
      $path = $validator['file']->storeAs('files', time().".$extension");

      $validator = array_diff_key($validator, array('file' => ''));
      $validator['path'] = Crypt::encryptString($path);

      $data = Data::create($validator);
      return $this->apiResponse($data, 'Data berhasil dibuat', 201);
   }

   public function update(Request $request, int $id) {
      $validator = $request->validate([
         'school_id' => 'required',
         'year' => 'required',
         'data_type_id' => 'required',
         'data_status_id' => 'required',
      ]);

      $validator = array_diff_key($validator, array('file' => ''));

      $data = Data::find($id)->update($validator);
      return $this->apiResponse($data, 'Data berhasil diperbarui');
   }

   public function updateFile(Request $request, int $id) {
      $validator = $request->validate([
         'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.google-apps.document,application/vnd.google-apps.spreadsheet'
      ]);

      $data = Data::find($id);
      $oldPath = Crypt::decryptString($data->path);
      if (Storage::exists($oldPath)) Storage::delete($oldPath);

      $extension = $request->file('file')->getClientOriginalExtension();
      $path = $validator['file']->storeAs('files', time()."$extension");

      $data->update([
         'path' => Crypt::encryptString($path)
      ]);
      return $this->apiResponse(true, 'File berhasil diupload');
   }

   public function downloadFile(Request $request) {
      $data = Data::find($request->id);
      if (!$data) return $this->apiResponse(null, 'Data tidak ditemukan', 422);

      $path = Crypt::decryptString($data->path);
      if (!Storage::exists($path)) return $this->apiResponse(null, 'File tidak ditemukan', 422);

      return Storage::download($path);
   }

   public function delete(Request $request) {
      $request->validate([
         'id' => 'required'
      ]);

      $data = Data::find($request->id);
      if (!$data) return $this->apiResponse(null, 'Data tidak ditemukan', 422);

      $path = Crypt::decryptString($data->path);
      if (Storage::exists($path)) Storage::delete($path);

      $data->delete();
      return $this->apiResponse(true, 'Data berhasil dihapus');
   }

   public function count() {
      $query = Data::all();
      $status = DataStatus::all();
      $category = DataCategory::all();
      $type = DataType::all();

      $total = $query->count();

      $data_by_status = array();
      foreach ($status as $s) {
         array_push($data_by_status, array(
            'name' => $s->name,
            'count' => $query->where('data_status_id', $s->id)->count()
         ));
      }

      $data_by_category = array();
      foreach ($category as $c) {
         $cSum = 0;
         $data_by_type = array();
         $_type = $type->where('data_category_id', $c->id);
         foreach ($_type as $t) {
            $_total = $query->where('data_type_id', $t->id)->count();
            $cSum += $_total;
            array_push($data_by_type, array(
               'name' => $t->name,
               'count' => $_total
            ));
         }
         array_push($data_by_category, array(
            'name' => $c->name,
            'total' => $cSum,
            'data_by_type' => $data_by_type
         ));
      }

      return $this->apiResponse(compact('total', 'data_by_status', 'data_by_category'));
   }
}
