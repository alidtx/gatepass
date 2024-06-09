<?php

namespace Modules\GatepassReceive\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\GatepassReceive\Entities\InternalReceive;
use Modules\GatepassReceive\Entities\InternalReceiveItem;
use App\Events\NotifyUser;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class InternalReceiveService
{

    /**
     * Create a new job.
     *
     * @param array $data
     * @return Receive Item
     */
    public function createInternalReceive(array $data): InternalReceive
    {
        DB::beginTransaction();
        try {
            $internalReceive = InternalReceive::create([
                'to_location_id' => $data['to_location_id'],
                'gatepass_check_id' => $data['gatepass_check_id'],
                'received_by' => auth()->user()->id,
                'received_date_time' => $data['received_date_time'],
                'status' => isset($data['item_details']) && count($data['item_details']) > 0 ?
                            (array_sum(array_column($data['item_details'], 'difference')) == 0 ? 0 
                            : (array_sum(array_column($data['item_details'], 'difference')) < 0 ? 1 : 2)) : 0,
                'tag' => "Incoming",
            ]);

            $mismatchedItems = [];
            if (isset($data['item_details']) && count($data['item_details']) > 0) {
                foreach($data['item_details'] as $item) {
                    if ($item['difference'] < 0 || $item['difference'] > 0) {
                        $item['difference'] = $item['difference'];
                        $mismatchedItems[] = $item;
                    }

                    InternalReceiveItem::create([
                        'internal_receive_id' => $internalReceive->id,
                        'item_id' => $item['item_id'],
                        'received_qty' => $item['received_qty']
                    ]);
                }
            }

            // send Qty Mismatch notification to GM and Production Head user
            if (!empty($mismatchedItems)) {
                // send notification for mismatched items
                $this->sendMismatchNotification($internalReceive->gatepassCheckInfo->gatepassInfo, $mismatchedItems);
            }

            DB::commit();
            return $internalReceive;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }


    public function update(array $data, $id): InternalReceive
    {
        DB::beginTransaction();
        try {
            
            $internalReceive = InternalReceive::findOrFail($id);
            $internalReceive->to_location_id = $data['to_location_id'];
            $internalReceive->gatepass_check_id = $data['gatepass_check_id'];
            $internalReceive->received_by = auth()->user()->id;
            $internalReceive->received_date_time = $data['received_date_time'];

            // calculate differences total and set status based on total differences
            $sumOfDifference = isset($data['item_details']) && count($data['item_details']) > 0 ? 
                                array_sum(array_column($data['item_details'], 'difference')): 0;
            $internalReceive->status = $sumOfDifference == 0 ? 0 : ($sumOfDifference < 0 ? 1 : 2);

            // save the item
            $internalReceive->save();

            // Update or create related InternalReceiveItem instances
            if (isset($data['item_details']) && count($data['item_details']) > 0) {
                foreach ($data['item_details'] as $item) {
                    InternalReceiveItem::updateOrCreate(
                        ['internal_receive_id' => $id, 'item_id' => $item['item_id']],
                        ['internal_receive_id' => $internalReceive->id, 'item_id' => $item['item_id'], 'received_qty' => $item['received_qty']]
                    );
                }
            }

            DB::commit();
            return $internalReceive;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendMismatchNotification($gatepass, $mismatchedItems)
    {
        $type = 'Internal Receive Mismatch';
        $title = 'Internal Receive Quantity Mismatch';
        $gatepass = $gatepass->gate_pass_no;
        $message = 'Gatepass: ' . $gatepass . ' has mismatched items. Please check the details.';

        $userDetails = User::whereHas('userAssignedRoles', function ($query) {
            $query->whereHas('roleInfo', function ($query) {
                $query->where('name', 'GM')
                    ->orWhere('name', 'Production Head');
            });
        })->get();

        // Send notification to the determined user IDs
        if ($userDetails->count() > 0) {
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
