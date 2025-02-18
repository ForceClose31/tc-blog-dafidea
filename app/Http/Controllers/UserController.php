<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'role' => $validated['role'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->assignRole($role);

                $this->assignDefaultPermissions($user, $role->name);
            }

            return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error saving user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan pengguna.');
        }
    }

    private function assignDefaultPermissions($user, $roleName)
    {
        $permissions = [];
        switch ($roleName) {
            case 'Admin':
                $permissions = [
                    'dashboard',
                    'create',
                    'edit',
                    'delete',
                ];
                break;
        }

        if (!empty($permissions)) {
            $user->givePermissionTo($permissions);
        }
    }
}
