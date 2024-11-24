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
            $users = BaseUser::paginate(10);
            return json_send(JsonResponse::HTTP_OK, $users);
        } catch (Exception $ex) {
            log_message($ex);
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }


    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'login_id' => 'required|string|min:1',  // Adjust the validation rule as needed
                'password' => 'required|string|min:1', // Adjust the validation rule as needed
                'user_name' => 'required|string|min:1', // Adjust the validation rule as needed
            ]);

            if ($validator->fails()) {
                $errorMessages = $validator->errors()->all();
                return json_send(JsonResponse::HTTP_OK, $errorMessages, 'error');
            }
            return json_send(JsonResponse::HTTP_OK, $request->login_id);
            DB::beginTransaction();
        } catch (Exception $ex) {
            log_message($ex);
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);

            DB::rollBack();
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }

    public function update(Request $request, int $id) {
        DB::beginTransaction();
        try {

            DB::commit();
        } catch (Exception $ex) {
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);

            DB::rollBack();
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }


    public function destroy($id) {
        try {
            DB::beginTransaction();

            // Commit the transaction
            DB::commit();
        } catch (Exception $ex) {
            // Log errors
            log_message('Error occurred during tenant deletion: ', ['exception' => $ex->getMessage()]);

            // Rollback transaction in case of error
            DB::rollBack();
        }
    }
}
