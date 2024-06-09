<?php

namespace Modules\GatepassReceive\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\GatepassReceive\Entities\ExternalReceive;
use Modules\GatepassReceive\Entities\ExternalReceiveItem;
use Modules\GatepassReceive\Entities\ExternalReceiveDocument;
use App\Events\NotifyUser;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class ExternalReceiveService
{
    /**
     * Create a new job.
     *
     * @param array $data
     * @return Receive Item
     */
    public function createExternalReceive(array $data): ExternalReceive
    {
        DB::beginTransaction();
        try {
            
            $externalReceive = ExternalReceive::create([
                'to_location_id' => $data['to_location_id'],
                'received_by' => auth()->user()->id,
                'receive_date_time' => $data['receive_date_time'],
                'party' => $data['party'],
                'receive_no' => $data['receive_no'],
                'gatepass_no_from_party' => $data['gatepass_no'] ?? null,
                'challan_no' => $data['challan_no'] ?? null,
                'to_person' => $data['to_person'] ?? null,
                'to_department_id' => $data['to_department_id'] ?? null,
                'status' => isset($data['item_details']) && count($data['item_details']) > 0 ?
                            (array_sum(array_column($data['item_details'], 'difference')) == 0 ? 0 
                            : (array_sum(array_column($data['item_details'], 'difference')) < 0 ? 1 : 2)) : 0,
                'tag' => "Incoming",
                'note' => $data['note'] ?? '',
            ]);

            if(isset($data['item_details']) && count($data['item_details']) > 0) {
                foreach($data['item_details'] as $item) {
                    ExternalReceiveItem::create([
                        'external_receive_id' => $externalReceive->id,
                        'item_id' => $item['item_id'],
                        'item_description' => $item['item_description'] ?? null,
                        'unit_id' => $item['unit_id'] ?? null,
                        'document_qty' => $item['document_qty'],
                        'received_qty' => $item['received_qty']
                    ]);
                }
            }

            if(isset($data['document']) && count($data['document']) > 0) {
                foreach($data['document'] as $document) {
                    $findDoc = ExternalReceiveDocument::find($document['id']);
                    if($findDoc) {
                        $findDoc->external_receive_id = $externalReceive->id;
                        $findDoc->save();
                    }
                }
            }

            if($externalReceive->to_department_id) {
                $this->sendNotificationToDepartmentHoD($externalReceive);
            }

            
            DB::commit();
            return $externalReceive;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateExternalReceive(array $data, $id): ExternalReceive
    {
        DB::beginTransaction();
        try {
            
            $externalReceive = ExternalReceive::findOrFail($id);
            $externalReceive->to_location_id = $data['to_location_id'];
            $externalReceive->receive_date_time = $data['receive_date_time'];
            $externalReceive->party = $data['party'];
            $externalReceive->receive_no = $data['receive_no'];
            $externalReceive->gatepass_no_from_party = $data['gatepass_no'] ?? null;
            $externalReceive->challan_no = $data['challan_no'] ?? null;
            $externalReceive->to_person = $data['to_person'] ?? null;
            $externalReceive->to_department_id = $data['to_department_id'] ?? null;
            $externalReceive->note = $data['note'] ?? '';

            // calculate differences total and set status based on total differences
            $sumOfDifference = isset($data['item_details']) && count($data['item_details']) > 0 ?
                                (array_sum(array_column($data['item_details'], 'difference')) == 0 ? 0 
                                : (array_sum(array_column($data['item_details'], 'difference')) < 0 ? 1 : 2)) : 0;
            $externalReceive->status = $sumOfDifference == 0 ? 0 : ($sumOfDifference < 0 ? 1 : 2);

            // save the item
            $externalReceive->save();

            // Update or create related InternalReceiveItem instances
            if (isset($data['item_details']) && count($data['item_details']) > 0) {
                foreach ($data['item_details'] as $item) {
                    ExternalReceiveItem::updateOrCreate(
                        [
                            'external_receive_id' => $id, 
                            'item_id' => $item['item_id']
                        ],
                        [
                            'external_receive_id' => $externalReceive->id, 
                            'item_id' => $item['item_id'], 
                            'item_description' => $item['item_description'] ?? null, 
                            'unit_id' => $item['unit_id'] ?? null, 
                            'document_qty' => $item['document_qty'], 
                            'received_qty' => $item['received_qty']
                        ]
                    );
                }
            }

            if(isset($data['document']) && count($data['document']) > 0) {
                foreach($data['document'] as $document) {
                    $findDoc = ExternalReceiveDocument::find($document['id']);
                    if($findDoc) {
                        ExternalReceiveDocument::where('external_receive_id', $id)->delete();
                        $findDoc->external_receive_id = $externalReceive->id;
                        $findDoc->save();
                    }
                }
            }

            DB::commit();
            return $externalReceive;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function generateReceiveNo($data)
    {
        $autoIncrementID = ExternalReceive::withTrashed()->whereMonth('receive_date_time', '=', date('m', strtotime($data)))->count();
        // Retrieve the auto-incremented ID
        $incrementalNo = str_pad((integer)$autoIncrementID + 1, 5, '0', STR_PAD_LEFT);
        // receive no as per business logic
        $receiveNo = date('y-m', strtotime($data)).'-'.$incrementalNo;
        return $receiveNo; 
    }

    public function sendNotificationToDepartmentHoD($data)
    {
        try {
            $type = 'External Receive';
            $title = 'New External Gatepass Received';
            $message = 'New external gatepass has been received with receive no: '.$data->receive_no. ' and received by: '.auth()->user()->name;

            $hodUsers = User::whereHas('department', function ($query) use ($data) {
                            $query->where('id', $data->to_department_id);
                        })->whereHas('userAssignedRoles', function ($query) {
                            $query->whereHas('roleInfo', function ($query) {
                                $query->where('name', 'HOD');
                            });
                        })->pluck('id')->toArray();

            if(count($hodUsers) > 0) {
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
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
