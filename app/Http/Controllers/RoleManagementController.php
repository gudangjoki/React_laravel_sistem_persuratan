<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleManagementController extends Controller
{
    public function createNewRole(Request $request) {
        // Validasi input dari request
        $validate = $request->validate([
            'role_name' => 'required|string',
            'permission_id' => 'required|array'
        ]);
    
        try {
            // Buat role baru
            $role = new Role;
            $role->role_name = $validate['role_name'];
            
            // Simpan role ke dalam database terlebih dahulu
            $role->save();
        
            // Setelah role disimpan, dapatkan ID role dan attach permissions
            $role->permissions()->attach($validate['permission_id']);
        
            // Kembalikan response jika sukses
            return response()->json([
                'success' => true,
                'message' => 'Success create new role'
            ], 200);
        } catch (\Exception $e) {
            // Kembalikan response jika terjadi error
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function getRolesDropdown(Request $request) {
        $index = $request->query('index') ?? 0;
        $keyword = $request->query('keyword') ?? '';

        $query = Role::query();

        if ($keyword != '') {
            $query->where(function($q) use ($keyword) {
                $q->where('role_name', 'like', $keyword . '%');
            });
        }

        if ($index > 0) {
            $query->offset($index . '0')->limit(10);
        } else if ($index == 0) {
            $query->offset(0)->limit(10);
        } else {
            return response()->json([
                'success' => false,
                'error' => "index query param can't be less than zero or minus value"
            ], 400);
        }

        $keywords = $query->get();

        return response()->json(['index' => $index, 'data' => $keywords], 200);
    }

    public function getAllRoles() {
        $roles = Role::all();
        return response()->json(['success' => true, 'data' => $roles], 200);
    }


    public function editPermissionName(Request $request, int $permission_id) {
        $validate = $request->validate([
            'permission_name' => 'required|string',
        ]);

        Permission::where('id', $permission_id)->update([
            'permission_name' => $validate['permission_name']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'success edit permission name with id ' . $permission_id
        ], 200);
    }

    public function createNewPermission(Request $request) {
        $validate = $request->validate([
            'permission_name' => 'required|string'
        ]);

        $role = new Permission();
        $role->permission_name = $validate['permission_name'];

        return response()->json([
            'success' => true,
            'message' => 'success create new role'
        ], 200);
    }

    public function getAllPermissions() {
        $permissions = Permission::all();

        $permission = [
            'letter' => [],
            'user' => [],
            'role' => []
        ];
    
        $categories = ['letter', 'user', 'role'];

        try {
            foreach($permissions as $p) {
                $new_permission = new Permission();
                $new_permission->id = $p->id;
                $new_permission->permission_name = $p->permission_name;
                
                foreach ($categories as $category) {
                    if (strpos($p->permission_name, $category) !== false) {
                        array_push($permission[$category], $new_permission);
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $permission
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
