<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Comment;
use App\Models\DataType;
use App\Models\DataStatus;
use App\Models\DataCategory;
use Illuminate\Http\Request;
use App\Traits\CheckUserable;
use App\Http\Requests\DataReq;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class DataC extends Controller
{
   use CheckUserable;

   public function get(Request $request) {
      $user = $request->user();
      $req = $request->all();

      if ($this->isSchool($user) || $this->isSupervisor($user)) {
         $req[$user->userable_type] = $user->userable_id;
      }

      $query = Data::latest('updated_at')
         ->category($req['category'] ?? null)
         ->type($req['type'] ?? null)
         ->status($req['status'] ?? null)
         ->year($req['year'] ?? null)
         ->filterSchool($req['school'] ?? null)
         ->schoolSupervisor($req['supervisor'] ?? null);

      $data = $query->paginate($request->per_page)->withQueryString();

      return parent::apiResponse($data);
   }

   public function show(Request $request, int $id) {
      $user = $request->user();
      $data = Data::find($id);

      if (($this->isSchool($user) && $user->userable_id !== $data->school_id)
         || ($this->isSupervisor($user) && $user->userable_id !== $data->supervisor_id)
      ) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      return parent::apiResponse($data);
   }

   public function create(DataReq $request) {
      $user = $request->user();

      if ($this->isSchool($user) && $request->school_id !== $user->userable_id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      $_data = $request->safe()->except(['file']);
      $_file = $request->validated('file');

      $extension = $_file->getClientOriginalExtension();
      $path = $_file->storeAs('files', time() . ".$extension");
      $_data['path'] = Crypt::encryptString($path);

      $data = Data::create($_data);
      return parent::apiResponse($data, 'Data berhasil dibuat');
   }

   public function update(DataReq $request, int $id) {
      $user = $request->user();
      $_data = $request->validated();
      $data = Data::find($id);

      if ($this->isSchool($user)
         && ($data->school_id != $user->userable_id
            || $_data['school_id'] != $user->userable_id
            || $_data['school_id'] != $data->school_id
         )
      ) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      $data->update($_data);
      return parent::apiResponse(true, 'Data berhasil diperbarui');
   }

   public function updateFile(DataReq $request, int $id) {
      $user = $request->user();
      $data = Data::find($id);

      if ($this->isSchool($user) && $data->school_id != $user->userable_id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      $_file = $request->validated('file');
      $oldPath = Crypt::decryptString($data->path);
      if (Storage::exists($oldPath)) Storage::delete($oldPath);

      $extension = $_file->getClientOriginalExtension();
      $path = $_file->storeAs('files', time() . ".$extension");

      $data->update(['path' => Crypt::encryptString($path)]);

      return parent::apiResponse(true, 'File pada data berhasil diperbarui');
   }

   public function downloadFile(Request $request) {
      $data = Data::find($request->id);
      if (!$data) return parent::apiResponse(null, 'Data tidak ditemukan', 422);

      $path = Crypt::decryptString($data->path);
      if (!Storage::exists($path)) return parent::apiResponse(null, 'File tidak ditemukan', 422);

      return Storage::download($path);
   }

   public function count(Request $request) {
      $user = $request->user();

      $allCategories = DataCategory::select('id', 'name')->get();
      $allTypes = DataType::without(['category'])->select('id', 'name', 'data_category_id')->get();
      $allStatuses = DataStatus::select('id', 'name')->get();

      $totalQuery = Data::year($request->year)->yearRange(request(['start_year', 'end_year']));

      switch ($user->userable_type) {
         case 'school':
            $total = $totalQuery->where('school_id', $user->userable_id)->count();
            break;
         case 'supervisor':
            $total = $totalQuery->whereHas('school', fn ($query, $user) =>
               $query->where('supervisor_id', $user->userable_id)
            )->count();
            break;
         default:
            $total = $totalQuery->count();
            break;
      }

      $data = Data::without(['type', 'status', 'school'])
         ->year($request->year)
         ->yearRange(request(['start_year', 'end_year']))
         ->when($this->isAdmin($user), function($query) use($user) {
            switch ($user->userable_type) {
               case 'school':
                  $query->where('school_id', $user->userable_id);
                  break;
               case 'supervisor':
                  $query->whereHas('school', fn ($query) =>
                     $query->where('supervisor_id', $user->userable_id)
                  );
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
         $cCount = $cData->groupBy('type')->map(fn ($type) => $type->count())->sum();

         $tInC = $allTypes->where('data_category_id', $c->id);
         $data_by_type = array();

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

      $byStatus = Data::without(['type', 'status', 'school'])
         ->year($request->year)
         ->yearRange(request(['start_year', 'end_year']))
         ->when(!$this->isAdmin($user), function($query) use($user) {
            switch ($user->userable_type) {
               case 'school':
                  $query->where('school_id', $user->userable_id);
                  break;
               case 'supervisor':
                  $query->whereHas('school', fn ($query) =>
                     $query->where('supervisor_id', $user->userable_id)
                  );
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

      return parent::apiResponse(array_merge(compact('total', 'data_by_status', 'data_by_category'), request(['start_year', 'end_year'])));
   }

   public function delete(int $id) {
      $data = Data::find($id);
      $filePath = Crypt::decryptString($data->path);
      if (Storage::exists($filePath)) Storage::delete($filePath);
      Comment::where('data_id', $id)->delete();
      $data->delete();
      return parent::apiResponse(true, 'Data berhasil dihapus');
   }
}
