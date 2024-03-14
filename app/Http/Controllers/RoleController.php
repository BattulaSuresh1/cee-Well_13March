<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\RoleHasPermission;
// use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;
use Validator;
use Session;

class RoleController extends Controller
{
    function __construct()
    {

    }

    public function _index()
    {

        $data['menu'] = 'role';
        return view('admin.role.index', $data);
    }

    public function loadData(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Role::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Role::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();

        // Fetch records
        $records = Role::orderBy($columnName, $columnSortOrder)
            ->where('roles.name', 'like', '%' . $searchValue . '%')
            ->select('roles.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        foreach ($records as $key => $record) {
            $id = Crypt::encryptString($record->id);
            $data[$key]['name'] = $record->name;
            $data[$key]['actions'] = '<a href="' . url("role/edit/$id") . '"><i class="material-icons">mode_edit</i> </a>';
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data
        ];

        echo json_encode($response);
        exit;
    }

    // public function create(){
    //     $data['menu'] = 'role';

    //     $data['permissions']  = Permission::orderBy('permission_group')->get();

    //     $data['groups'] = Permission::select(['permission_group'])->groupby('permission_group')->get();

    //     return view('admin.role.add', $data);
    // }

    public function _store(Request $request)
    {
        $permissions = $request->permissions;
        if (!empty($permissions)) {

            $rules = ['name' => 'required|unique:roles'];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {

                $role = new Role();
                $role->name = $request->name;
                $role->save();

                $roleId = $role->id;

                foreach ($permissions as $key => $permission) {
                    $newPermissionRole = new RoleHasPermission();
                    $newPermissionRole->permission_id = $permission;
                    $newPermissionRole->role_id = $roleId;
                    $newPermissionRole->save();
                }

                Session::flash('success', __('Successfully Saved'));

                return redirect('role/list');

            }
        } else {
            Session::flash('danger', __('Please give at least one permission'));
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['menu'] = 'role';

        $id = Crypt::decryptString($id);

        $data['role'] = Role::find($id);

        if ($id == 1 && strtolower($data['role']->name) === 'admin') {
            Session::flash('fail', __('Admin role is not editable.'));
            return redirect()->intended('role/list');
        }

        if (empty($data['role'])) {
            Session::flash('fail', __('Role does not exist.'));
            return redirect('role/list');
        }

        $data['permissions'] = Permission::orderBy('permission_group')->get();
        $data['groups'] = Permission::select(['permission_group'])->groupby('permission_group')->get();

        $data['activePermissions'] = RoleHasPermission::select(['permission_id'])->where(["role_id" => $id])->get();
        return view('admin.role.add', $data);
    }

    public function _update(Request $request)
    {
        $permissions = $request->permissions;

        if (!empty($permissions)) {

            $data = [
                'type' => 'fail',
                'message' => __('Something went wrong, please try again.')
            ];

            $rules = [
                'name' => 'required|unique:roles,name,' . $request->id,
            ];

            // dd($request->id);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $role['name'] = $request->name;

                //update
                $roleToUpdate = Role::find($request->id);
                $roleToUpdate->name = $role['name'];
                $roleToUpdate->save();

                $stored_permissions = RoleHasPermission::where('role_id', $request->id)->pluck('permission_id')->toArray();

                $permission = isset($permissions) ? $permissions : [];

                if (!empty($stored_permissions)) {
                    foreach ($stored_permissions as $key => $value) {
                        if (!in_array($value, $permission)) {
                            $permissionRoleToDelete = RoleHasPermission::where(['permission_id' => $value, 'role_id' => $request->id]);
                            $permissionRoleToDelete->delete();
                        }

                    }
                }
                if (!empty($permission)) {
                    foreach ($permission as $key => $value) {
                        if (!in_array($value, $stored_permissions)) {

                            $newPermissionRoleToInsert = new RoleHasPermission();
                            $newPermissionRoleToInsert->permission_id = $value;
                            $newPermissionRoleToInsert->role_id = $request->id;
                            $newPermissionRoleToInsert->save();
                        }
                    }
                }
                $data = [
                    'type' => 'success',
                    'message' => __('Successfully updated')
                ];
            }

            Session::flash($data['type'], $data['message']);
            return redirect('role/list');

        } else {
            Session::flash('fail', __('Please give at least one permission'));
            return redirect()->back();
        }
    }

    public function index(Request $request)
    {

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        // $search = $request->searchKey ?? "";
        $search = $request->filter ?? "";

        $query = Role::orderBy('id', 'DESC')->where('status', '1');

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $data = $query->paginate($pageSize, $columns, $pageName, $page);

        return response()->json($data);
    }

    public function create()
    {

        $data['permissions'] = Permission::all()->groupBy('permission_group')
            ->map(function ($item, $key) {
                return [
                    'group_name' => $key,
                    'permissions' => $item
                ];
            })->values();
        ;

        return response()->json($data);
    }

    public function store(StoreRoleRequest $request)
    {
        try {

            $role = new Role();
            $role->name = $request->name;
            $role->save();

            $roleId = $role->id;

            $permissionGroups = $request->permissionGroups;

            foreach ($permissionGroups as $key => $permissionGroup) {
                $permissions = $permissionGroup['permissions'];
                foreach ($permissions as $key => $permission) {
                    if ($permission['give_permission']) {
                        $newPermissionRole = new RoleHasPermission();
                        $newPermissionRole->permission_id = $permission['id'];
                        $newPermissionRole->role_id = $roleId;
                        $newPermissionRole->save();
                    }
                }
            }


            $res = [
                'success' => true,
                'message' => 'Role created successfully.'
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }

    }

    public function show($id)
    {
        try {

            $data['role'] = Role::find($id);
            $data['activePermissions'] = RoleHasPermission::select(['permission_id'])->where(["role_id" => $id])->get();
            $data['permissions'] = Permission::all()->groupBy('permission_group')
                ->map(function ($item, $key) {
                    return [
                        'group_name' => $key,
                        'permissions' => $item
                    ];
                })->values();
            ;

            $res = [
                'success' => true,
                'message' => 'Role details.',
                'data' => $data
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    public function update(StoreRoleRequest $request, $id)
    {
        try {

            $role = Role::find($id);

            $role->name = $request->name;

            $role->save();

            $permissionGroups = $request->permissionGroups;
            $permission = [];

            foreach ($permissionGroups as $key => $permissionGroup) {
                $permissions = $permissionGroup['permissions'];
                foreach ($permissions as $key => $p) {
                    if ($p['give_permission']) {
                        array_push($permission, $p['id']);
                    }
                }
            }


            $stored_permissions = RoleHasPermission::where('role_id', $id)->pluck('permission_id')->toArray();

            // $permission = isset($permissions) ? $permissions : [];

            if (!empty($stored_permissions)) {
                foreach ($stored_permissions as $key => $value) {
                    if (!in_array($value, $permission)) {
                        $permissionRoleToDelete = RoleHasPermission::where(['permission_id' => $value, 'role_id' => $request->id]);
                        $permissionRoleToDelete->delete();
                    }

                }
            }

            if (!empty($permission)) {
                foreach ($permission as $key => $value) {
                    if (!in_array($value, $stored_permissions)) {
                        $newPermissionRoleToInsert = new RoleHasPermission();
                        $newPermissionRoleToInsert->permission_id = $value;
                        $newPermissionRoleToInsert->role_id = $id;
                        $newPermissionRoleToInsert->save();
                    }
                }
            }

            $res = [
                'success' => true,
                'message' => 'Role updated successfully.'
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }

    }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $role = Role::where('id', $id)->where('status', '1')->first();

            if (!empty($role)) {

                $role->status = '0';
                $role->save();

                RoleHasPermission::where(['status' => '1', 'role_id' => $id])
                    ->update([
                        'status' => '0'
                    ]);

                $res = [
                    'success' => true,
                    'message' => 'Role deleted successfully.',
                    'data' => $role
                ];
            } else {
                $res = [
                    'success' => false,
                    'data' => 'Role details not found.',
                    'message' => $id
                ];
            }

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }

    }

    public function getPermissionsByRoleId($id)
    {
        try {

            $permissions = RoleHasPermission::where(['status' => '1', 'role_id' => $id])->get();

            $res = [
                'success' => true,
                'message' => 'Permissions details.',
                'data' => $permissions
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

}