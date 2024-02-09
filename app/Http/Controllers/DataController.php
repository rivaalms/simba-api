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
      $data = Data::filter(request(['school', 'type', 'category', 'status', 'year', 'supervisor']))->latest('updated_at')->paginate($request->per_page)->withQueryString();
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

      $allCategories = DataCategory::all();
      $allTypes = DataType::without(['category'])->get();
      $allStatuses = DataStatus::all();

      $totalQuery = Data::filter(request(['year']))->yearRange(request(['start_year', 'end_year']));

      if (!$isAdmin) {
         switch ($user->userable_type) {
            case 'school':
               $total = $totalQuery->where('school_id', $user->userable_id)->count();
               break;
            case 'supervisor':
               $total = $totalQuery->whereHas('school', function ($query) use ($user) {
                  $query->where('supervisor_id', $user->userable_id);
               })->count();
            default:
               $total = $totalQuery->count();
               break;
         }
      } else {
         $total = $totalQuery->count();
      }

      $data = Data::without(['type', 'status', 'school'])->filter(request(['year']))->yearRange(request(['start_year', 'end_year']))
         ->when(!$isAdmin, function ($query) use ($user) {
            switch ($user->userable_type) {
               case 'school':
                  $query->where('school_id', $user->userable_id);
                  break;
               case 'supervisor':
                  $query->whereHas('school', function ($query) use ($user) {
                     $query->where('supervisor_id', $user->userable_id);
                  });
                  break;
               default: break;
            }
         })
         ->selectRaw('data.id, data_types.name as type, data_categories.name as category')
         ->leftJoin('data_types', 'data.data_type_id', '=', 'data_types.id')
         ->leftJoin('data_categories', 'data_types.data_category_id', '=', 'data_categories.id')
         ->get();

      foreach ($allCategories as $c) {
         $cData = $data->where('category', $c->name);
         $cCount = $cData->groupBy('type')->map(function ($type) {
            return $type->count();
         })->sum();

         $tInC = $allTypes->where('data_category_id', $c->id);
         $data_by_type = [];

         foreach ($tInC as $t) {
            $tData = $cData->where('type', $t->name);
            $tCount = $tData->count();

            $data_by_type[] = [
               'name' => $t->name,
               'count' => $tCount
            ];
         }

         $data_by_category[] = [
            'name' => $c->name,
            'count' => $cCount,
            'data_by_type' => $data_by_type
         ];
      }

      $byStatus = Data::without(['type', 'status', 'school'])->filter(request(['year']))->yearRange(request(['start_year', 'end_year']))
         ->when(!$isAdmin, function ($query) use ($user) {
            switch ($user->userable_type) {
               case 'school':
                  $query->where('school_id', $user->userable_id);
                  break;
               case 'supervisor':
                  $query->whereHas('school', function ($query) use ($user) {
                     $query->where('supervisor_id', $user->userable_id);
                  });
                  break;
               default: break;
            }
         })
         ->selectRaw('count(data.id) as count, data_statuses.name as name')
         ->leftJoin('data_statuses', 'data.data_status_id', '=', 'data_statuses.id')
         ->groupBy('data_statuses.name')
         ->get();

         $data_by_status = array();
         foreach ($allStatuses as $s) {
            $sData = $byStatus->where('name', $s->name)->first();

            $data_by_status[] = [
               'name' => $s->name,
               'count' => $sData?->count ?: 0
            ];
         }

      return $this->apiResponse(array_merge(compact('total', 'data_by_status', 'data_by_category'), request(['start_year', 'end_year'])));
   }

   public function updateDataStatus(Request $request, int $id)
   {
      $data = $request->validate([
         'data_status_id' => 'required'
      ]);

      Data::where('id', $id)->update($data);
      return $this->apiResponse(true, 'Status data berhasil diperbarui');
   }
}
