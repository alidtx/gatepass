<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Modules\User\Entities\UserSource;
use Modules\User\Entities\UserAssignedRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 

class UserService
{

    /**
     * Create a new job.
     *
     * @param array $data
     * @return Gatepass
     */
    public function createUser(array $data): User
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            $user->password = Hash::make($data['password']);
            $user->user_source_id = $data['user_source_id'] ?? 1;
            $user->department_id = $data['department_id'];
            $user->user_status = $data['user_status'] ?? 1;
            $user->employee_id = $data['employee_id'] ?? null;
            $user->created_by = Auth::user()->id;
            $user->save();

            foreach($data['role_id'] as $role) {
                UserAssignedRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role
                ]);
                $user->assignRole($role);
            }
            
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateUser(array $data, $id): User
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? $user->phone;
            $user->user_source_id = $data['user_source_id'] ?? 1;
            $user->department_id = $data['department_id'];
            $user->user_status = $data['user_status'] ?? $user->user_status;
            $user->employee_id = $data['employee_id'] ?? $user->employee_id;
            $user->save();

            UserAssignedRole::where('user_id', $user->id)->delete();
            foreach($data['role_id'] as $role) {
                UserAssignedRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role
                ]);
                $user->assignRole($role);
            }
            
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}
