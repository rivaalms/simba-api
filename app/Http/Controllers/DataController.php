<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class DataController extends Controller
{
   public function getData(Request $request) {
      $data = Data::filter(request(['school', 'type', 'category', 'status', 'year']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($data);
   }

   public function createData(Request $request) {
      $validator = $request->validate([
         'school_id' => 'required',
         'year' => 'required',
         'data_type_id' => 'required',
         'data_status_id' => 'required',
         'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.google-apps.document,application/vnd.google-apps.spreadsheet'
      ]);

      $extension = $request->file('file')->getClientOriginalExtension();
      $path = $validator['file']->storeAs('files', time().".$extension");

      $validator = array_diff_key($validator, array('file' => ''));
      $validator['path'] = Crypt::encryptString($path);

      $data = Data::create($validator);
      return $this->apiResponse($data, 'New data created successfully');
   }

   public function downloadData(Request $request) {
      $data = Data::find($request->id);
      if (!$data) return $this->apiResponse(null, 'Data not found', 422);

      $path = Crypt::decryptString($data->path);
      if (!Storage::exists($path)) return $this->apiResponse(null, 'File not found', 422);

      return Storage::download($path);
   }
}
