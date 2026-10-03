<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() { return view('manage.users.index', ['users' => User::orderBy('name')->paginate(20)]); }
    public function edit(User $user) { return view('manage.users.edit', compact('user')); }
    public function store(Request $request) { $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'nik' => ['required', 'string', 'max:40', 'unique:users'], 'role' => ['required', 'in:it,admin'], 'password' => ['required', 'string', 'min:8', 'confirmed']]); User::create($data); return back()->with('status', 'Pengguna ditambahkan.'); }
    public function update(Request $request, User $user) { $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'nik' => ['required', 'string', 'max:40', 'unique:users,nik,'.$user->id], 'role' => ['required', 'in:it,admin'], 'password' => ['nullable', 'string', 'min:8', 'confirmed']]); if (blank($data['password'])) unset($data['password']); $user->update($data); return back()->with('status', 'Pengguna diperbarui.'); }
    public function destroy(User $user) { abort_if($user->is(auth()->user()), 422, 'Akun sendiri tidak dapat dihapus.'); $user->delete(); return back()->with('status', 'Pengguna dihapus.'); }
}
