<?php

namespace Modules\Gatepass\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Gatepass\Entities\GatepassItem;
use Modules\Gatepass\Entities\GatepassDocument;
use App\Events\NotifyUser;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class GatepassService
{
    /**
     * Create a new job.
     *
     * @param array $data
     * @return Gatepass
     */
    public function createGatepass(array $data): Gatepass
    {
        DB::beginTransaction();
        try {
            $gatepass = new Gatepass();
            $gatepass->type_id = $data['type'];
            $user = auth()->user();
    
            // Check if gatepass number already exists
            if (Gatepass::where('gate_pass_no', $this->generateGatepassNumber())->exists()) {
                throw new \Exception('Duplicate gatepass number detected.');
            }

            $gatepass->gate_pass_no = $this->generateGatepassNumber();
            $gatepass->to_location_id = $data['to_location'] ?? null;
            $gatepass->party_id = $data['party'] ?? null;
            $gatepass->creation_datetime = $data['creation_date_time'];
            $gatepass->challan_no = $data['challan_no'] ?? null;
            $gatepass->created_by = $data['creation_user'] ?? $user->id;
            $gatepass->created_by_department = $data['department'] ?? $user->department?->id;
            $gatepass->note = $data['note'] ?? null;
            $gatepass->to_person_id = $data['to_person'] ?? null;
            $gatepass->to_person_department_id = $data['to_department'] ?? null;
            $gatepass->external_to_person = $data['external_to_person'] ?? null;
            $gatepass->mobile = $data['mobile_no'] ?? null;
            $gatepass->purpose = $data['purpose'] ?? null;
            $gatepass->vehicle_no = $data['vehicle_no'] ?? null;
            $gatepass->from_location_id = $data['from_location'] ?? null;
            $gatepass->carrying_person = $data['carrying_person'] ?? null;
            $gatepass->party_challan_no = $data['challan_no_party'] ?? null;
            $gatepass->tag = "Outgoing";
            $gatepass->status = $data['status'] ?? 0;
            $gatepass->save();

            $items = $data['item_details'];
 
            foreach($items as $item) {
                $gatepassItem = new GatepassItem();
                $gatepassItem->gatepass_id = $gatepass->id;
                $gatepassItem->item_id = $item['id'];
                $gatepassItem->item_description = $item['item_description'] ?? null;
                $gatepassItem->unit_id = $item['unit_id'] ?? null;
                $gatepassItem->qty = $item['qty'];
                $gatepassItem->save();
            }

            if(isset($data['document']) && !empty($data['document'])) {
                
                $documents = $data['document'];
                foreach($documents as $document) {
                    $findDoc = GatepassDocument::find($document['id']);
                    if($findDoc) {
                        $findDoc->gatepass_id = $gatepass->id;
                        $findDoc->save();
                    }
                }
            }

            if($user->hasRole('HOD') && $data['status'] == 0) {
                if($gatepass->typeInfo->name == 'Internal') {
                    $gatepass->status = 4;
                    $gatepass->second_approved_by = $user->id;
                    $gatepass->second_approval_datetime = now();
                    $gatepass->save();
                }

                if($gatepass->typeInfo->name == 'External') {
                    $gatepass->status = 2;
                    $gatepass->approved_by = $user->id;
                    $gatepass->approval_datetime = now();
                    $gatepass->save();

                    // send approval notification to GM/Production Head role users
                    $this->sendApprovalNotification($gatepass, "2");
                }
            }

            // Send notification to the user
            if($user->department_id && !$user->hasRole('HOD')) {
                $this->sendNotificationToHoD($gatepass);
            }

            DB::commit();
            return $gatepass;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    private function generateGatepassNumber()
    {
        $latestGatepass = Gatepass::withTrashed()
        ->whereYear('creation_datetime', Carbon::now()->year)
        ->whereMonth('creation_datetime', Carbon::now()->month)
        ->orderBy('gate_pass_no', 'desc')
        ->first();

        $gatepassNo = '';

        if ($latestGatepass) {
            // If a gatepass exists for the current month and year, increment the gatepass number
            $lastGatepassNo = (int)substr($latestGatepass->gate_pass_no, -5);
            $nextGatepassNo = $lastGatepassNo + 1;
            $gatepassNo = Carbon::now()->format('y-m-') . str_pad($nextGatepassNo, 5, '0', STR_PAD_LEFT);
            // dd($latestGatepass->gate_pass_no, $lastGatepassNo, $nextGatepassNo, $gatepassNo);
        } else {
            // If no gatepass exists for the current month and year, start with 00001
            $gatepassNo = Carbon::now()->format('y-m-') . '00001';
        }

        return $gatepassNo;
    }

    public function updateGatepass(array $data, $id): Gatepass
    {
        DB::beginTransaction();
        try {
            $gatepass = Gatepass::find($id);
            $gatepass->type_id = $data['type'];
            $gatepass->to_location_id = $data['to_location'] ?? null;
            $gatepass->party_id = $data['party'] ?? null;
            $gatepass->creation_datetime = $data['creation_date_time'];
            $gatepass->challan_no = $data['challan_no'] ?? null;
            $gatepass->created_by = $data['creation_user'] ?? auth()->user()->id;
            $gatepass->created_by_department = $data['department'] ?? auth()->user()->department?->id;
            $gatepass->note = $data['note'] ?? null;
            $gatepass->to_person_id = $data['to_person'] ?? null;
            $gatepass->to_person_department_id = $data['to_department'] ?? null;
            $gatepass->external_to_person = $data['external_to_person'] ?? null;
            $gatepass->mobile = $data['mobile_no'] ?? null;
            $gatepass->purpose = $data['purpose'] ?? null;
            $gatepass->vehicle_no = $data['vehicle_no'] ?? null;
            $gatepass->from_location_id = $data['from_location'] ?? null;
            $gatepass->carrying_person = $data['carrying_person'] ?? null;
            $gatepass->party_challan_no = $data['challan_no_party'] ?? null;
            $gatepass->status = 1; // status will be updated
            $gatepass->save();

            $items = $data['item_details'];
            $deleteItems = GatepassItem::where('gatepass_id', $gatepass->id)->delete();
            foreach($items as $item) {
                $gatepassItem = new GatepassItem();
                $gatepassItem->gatepass_id = $gatepass->id;
                $gatepassItem->item_id = $item['id'];
                $gatepassItem->item_description = $item['item_description'] ?? null;
                $gatepassItem->unit_id = $item['unit_id'] ?? null;
                $gatepassItem->qty = $item['qty'];
                $gatepassItem->save();
            }

            $deleteDocuments = GatepassDocument::where('gatepass_id', $gatepass->id)->update(['gatepass_id' => null]);
            if(isset($data['document']) && !empty($data['document'])) {
                $documents = $data['document'];
                foreach($documents as $document) {
                    $findDoc = GatepassDocument::find($document['id']);
                    if($findDoc) {
                        $findDoc->gatepass_id = $gatepass->id;
                        $findDoc->save();
                    }
                }
            }
            
            if(Auth::user()->hasRole('HOD')) {
                if($gatepass->typeInfo->name == 'Internal') {
                    $gatepass->status = 4;
                    $gatepass->second_approved_by = auth()->user()->id;
                    $gatepass->second_approval_datetime = now();
                    $gatepass->save();
                }

                if($gatepass->typeInfo->name == 'External') {
                    $gatepass->status = 2;
                    $gatepass->approved_by = auth()->user()->id;
                    $gatepass->approval_datetime = now();
                    $gatepass->save();
                }
            }
            
            DB::commit();
            return $gatepass;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendNotificationToHoD($gatepass)
    {
        $type = 'Gatepass Creation';
        $title = 'New Gatepass Created';
        $message = 'New gatepass has been created with gatepass no: '.$gatepass->gate_pass_no. ' and created by: '.auth()->user()->name. ' and waiting for your approval';

        $createdByUser = auth()->user();
        $departmentId = $createdByUser->department_id;

        $hodUsers = User::whereHas('department', function ($query) use ($departmentId) {
                        $query->where('id', $departmentId);
                    })->whereHas('userAssignedRoles', function ($query) {
                        $query->whereHas('roleInfo', function ($query) {
                            $query->where('name', 'HOD');
                        });
                    })->get();

        if($hodUsers->count() > 0) {
            Event::dispatch(new NotifyUser(
                $hodUsers,
                $type,
                $title, 
                $message, 
                null, // redirect url
                false, // send sms
                false, // send email
                true // send push
            ));
        }
    }

    public function sendApprovalNotification($gatepass, $status, $approveType = 'single')
    {
        $type = 'Gatepass Approval';
        $statusText = $status == 2 ? 'Approved' : 'Rejected';
        $title = $status == 2 ? 'Gatepass Approved' : 'Gatepass Rejected';
        $gatepasses = $approveType == 'bulk' ? implode(', ', $gatepass) : $gatepass->gate_pass_no;
        $message = 'Gatepass with gatepass no: ' . $gatepasses . ' has been ' . $statusText . ' by: ' . auth()->user()->name. ' and waiting for final approval';

        if ($status == 2) {
            $userDetails = User::whereHas('userAssignedRoles', function ($query) {
                $query->whereHas('roleInfo', function ($query) {
                    $query->where('name', 'GM')
                        ->orWhere('name', 'Production Head');
                });
            })->get();

        } elseif ($status == 3) {
            $userDetails = $gatepass->created_by ? User::whereId($gatepass->created_by)->get(): null;
        }

        // Send notification to the determined user IDs
        if ($userDetails->count() > 0){
            Event::dispatch(new NotifyUser(
                $userDetails,
                $type,
                $title,
                $message,
                null, // redirect url
                false, // send sms
                false, // send email
                true // send push
            ));
        }
    }

    public function sendFinalApprovalNotification($gatepass, $status, $approveType = 'single')
    {
        $type = 'Gatepass 2nd Approval';
        $statusText = $status == 4 ? 'Finally Approved' : 'Rejected';
        $title = $status == 4 ? 'Gatepass Finally Approved' : 'Gatepass Rejected';
        $gatepasses = $approveType == 'bulk' ? implode(', ', $gatepass) : $gatepass->gate_pass_no;
        $message = 'Gatepass with gatepass no: ' . $gatepasses . ' has been ' . $statusText . ' by: ' . auth()->user()->name;

        $userDetails = $gatepass->created_by ? User::whereId($gatepass->created_by)->get(): null;

        // Send notification to the determined user IDs
        if ($userDetails && $userDetails->count() > 0) {
            Event::dispatch(new NotifyUser(
                $userDetails,
                $type,
                $title,
                $message,
                null, // redirect url
                false, // send sms
                false, // send email
                true // send push
            ));
        }
    }
}
