<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormDataRequest;
use App\Models\Comment;
use App\Models\Data;
use App\Models\DataType;
use App\Models\DataStatus;
use App\Models\DataCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
   public function get(Request $request)
   {
      $data = Data::filter(request(['school', 'type', 'category', 'status', 'year']))->latest()->paginate($request->per_page)->withQueryString();
      return $this->apiResponse($data);
   }

   public function getSingle(Request $request, int $id)
   {
      $data = Data::find($id);
      return $this->apiResponse($data);
   }

   public function create(FormDataRequest $request)
   {
      $_data = $request->safe()->except(['file']);
      $_file = $request->validated('file');

      $extension = $_file->getClientOriginalExtension();
      $path = $_file->storeAs('files', time() . ".$extension");
      $_data['path'] = Crypt::encryptString($path);

      $data = Data::create($_data);
      return $this->apiResponse($data, 'Data berhasil dibuat');
   }

   public function update(FormDataRequest $request, int $id)
   {
      $_data = $request->validated();
      Data::find($id)->update($_data);
      return $this->apiResponse(true, 'Data berhasil diperbarui');
   }

   public function updateFile(FormDataRequest $request, int $id)
   {
      $_file = $request->validated('file');
      $data = Data::find($id);
      $oldPath = Crypt::decryptString($data->path);
      if (Storage::exists($oldPath)) Storage::delete($oldPath);

      $extension = $_file->getClientOriginalExtension();
      $path = $_file->storeAs('files', time() . ".$extension");

      $data->update([
         'path' => Crypt::encryptString($path)
      ]);
      return $this->apiResponse(true, 'File pada data berhasil diperbarui');
   }

   public function downloadFile(Request $request)
   {
      $data = Data::find($request->id);
      if (!$data) return $this->apiResponse(null, 'Data tidak ditemukan', 422);

      $path = Crypt::decryptString($data->path);
      if (!Storage::exists($path)) return $this->apiResponse(null, 'File tidak ditemukan', 422);

      return Storage::download($path);
   }

   public function delete(int $id)
   {
      $data = Data::find($id);

      $filePath = Crypt::decryptString($data->path);
      if (Storage::exists($filePath)) Storage::delete($filePath);
      Comment::where('data_id', $id)->delete();
      $data->delete();
      return $this->apiResponse(true, 'Data berhasil dihapus');
   }

   public function count()
   {
      $user = request()->user();
      $isAdmin = !$user->userable_type;

      $query = Data::filter(request(['year']))->yearRange(request(['start_year', 'end_year']))->get();
      $status = DataStatus::all();
      $category = DataCategory::all();
      $type = DataType::all();

      if (!$isAdmin) {
         switch ($user->userable_type) {
            case 'school':
               $query = $query->where('school_id', $user->userable_id);
               break;
            default:
               break;
         }
      }

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

      return $this->apiResponse(array_merge(compact('total', 'data_by_status', 'data_by_category'), request(['start_year', 'end_year'])));
   }
}
