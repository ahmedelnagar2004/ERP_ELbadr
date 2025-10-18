<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\UserStatus;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-users')->only(['index', 'show']);
        $this->middleware('permission:create-users')->only(['create', 'store']);
        $this->middleware('permission:edit-users')->only(['edit', 'update']);
        $this->middleware('permission:delete-users')->only(['destroy']);
    }

    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $statusEnum = UserStatus::fromString($request->status);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => Hash::make($request->password),
            'status' => $statusEnum->value(),
        ]);

        if ($request->has('roles') && ! empty($request->roles)) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $statusEnum = UserStatus::fromString($request->status);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'status' => $statusEnum->value(),
        ]);

        if ($request->has('password') && $request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكن حذف المستخدم صاحب صلاحية Super Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
