<?php

namespace App\Http\Controllers\Admin\Json;


use Exception;
use App\Models\Base\Tenant;
use App\Models\Tenant\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\Base\User as BaseUser;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Client_Validation;
use \App\Models\Tenant\Tenant as Client_Tenant;
use App\Http\Requests\Admin\Client_Edit_Validation;

class UserJson extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function get_all(Request $request) {
        try {
            DB::statement("SET search_path TO base_tenants");
            $users = BaseUser::with(['updatedBy:id,user_name'])  // Use 'with' to load the 'updatedBy' relationship
                ->select('id', 'login_id', 'user_name', 'update_user_id', 'updated_at');  // Adjust with actual columns you want to retrieve
            // Check if 'data' is provided in the request and search both login_id and user_name
            if ($request->has('data')) {
                $searchTerm = $request->input('data');
                $users->where(function ($q) use ($searchTerm) {
                    $q->where('login_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('user_name', 'like', '%' . $searchTerm . '%');
                });
            }
            // Paginate the results
            $users = $users->paginate(100);
            return json_send(JsonResponse::HTTP_OK, $users);
        } catch (Exception $ex) {
            log_message('Error occurred during user data: ', ['exception' => $ex->getMessage()]);
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }
    public function get_one($user_id) {
        try {
            DB::statement("SET search_path TO base_tenants");
            $user = BaseUser::with(['updatedBy:id,user_name'])  // Use 'with' to load the 'updatedBy' relationship
                ->select('id', 'login_id', 'user_name', 'update_user_id', 'updated_at')  // Adjust with actual columns you want to retrieve
                ->where('id', $user_id)
                ->first();
            return json_send(JsonResponse::HTTP_OK, $user);
        } catch (Exception $ex) {
            log_message('Error occurred during user data: ', ['exception' => $ex->getMessage()]);
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }


    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'login_id' => 'required|string|min:1|unique:users,login_id',  // Ensure the unique rule specifies the table and column
                'password' => 'required|string|min:1',  // Adjust as needed
                'user_name' => 'required|string|min:1',  // Adjust as needed
            ]);
            if ($validator->fails()) {
                $errorMessages = $validator->errors()->all();
                return json_send(JsonResponse::HTTP_OK, $errorMessages, 'error');
            }
            DB::statement("SET search_path TO base_tenants");
            DB::beginTransaction();
            $data = array_merge($validator->validated(), [
                'name' => $request->user_name,
            ]);
            $user = User::create($data);
            DB::commit();
            return json_send(JsonResponse::HTTP_OK, $user);
        } catch (Exception $ex) {
            DB::rollBack();
            log_message('Error occurred during user creation: ', ['exception' => $ex->getMessage()]);
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }

    public function update(Request $request, int $id) {
        try {
            $validator = Validator::make($request->all(), [
                'login_id' => 'required|string|min:1|unique:users,login_id',  // Ensure the unique rule specifies the table and column
                'password' => 'required|string|min:1',  // Adjust as needed
                'user_name' => 'required|string|min:1',  // Adjust as needed
            ]);
            if ($validator->fails()) {
                $errorMessages = $validator->errors()->all();
                return json_send(JsonResponse::HTTP_OK, $errorMessages, 'error');
            }
            DB::statement("SET search_path TO base_tenants");
            DB::beginTransaction();
            $data = array_merge($validator->validated(), [
                'name' => $request->user_name,
            ]);
            // Find the user by ID or any identifier (e.g., $request->user_id)
            $user = User::find($id);
            // Check if the user exists before updating
            if ($user) {
                $user->update($data);
            } else {
                return json_send(JsonResponse::HTTP_NOT_FOUND, ['error' => 'User not found']);
            }
            DB::commit();
            return json_send(JsonResponse::HTTP_OK, $user);
        } catch (Exception $ex) {
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);
            DB::rollBack();
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }


    public function destroy(Request $request) {
        try {
            DB::beginTransaction();
            $user = User::destroy($request->ids);
            // Commit the transaction
            DB::commit();
            return json_send(JsonResponse::HTTP_OK, $user);
        } catch (Exception $ex) {
            // Log errors
            log_message('Error occurred during tenant deletion: ', ['exception' => $ex->getMessage()]);
            // Rollback transaction in case of error
            DB::rollBack();
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        }
    }
}