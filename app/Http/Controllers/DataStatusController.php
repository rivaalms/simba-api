<?php

namespace App\Http\Controllers;

use App\Models\DataStatus;
use Illuminate\Http\Request;

class DataStatusController extends Controller
{
   public function get() {
      $status = DataStatus::all();
      return $this->apiResponse($status);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:data_statuses,name'
      ]);

      $status = DataStatus::create([
         'name' => $validator['name']
      ]);

      return $this->apiResponse($status, 'Data status created successfully', 201);
   }

   public function update(Request $request, int $id) {
      $status = DataStatus::find($id);

      $validator = $request->validate([
         'name' => 'required'
      ]);

      $status->update([
         'name' => $validator['name']
      ]);

      return $this->apiResponse(true, 'Data status updated successfully');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);

      DataStatus::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Data status has been deleted');
   }

   public function getDataStatusOptions () {
      $status = DataStatus::select('name as label', 'id as value')->get();
      return $this->apiResponse($status);
   }
}
