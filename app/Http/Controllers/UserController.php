<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::with('division');

            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }
            if ($request->filled('division_id')) {
                $query->where('division_id', $request->division_id);
            }
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            $users = $query->orderBy('name')->get();

            $stats = [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'admins' => User::where('role', 'admin')->count(),
            ];

            $divisions = Division::orderBy('name')->get();

            return view('users.index', compact('users', 'stats', 'divisions'));
        } catch (Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load users');
        }
    }

    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        return view('users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:ppc,operator,supervisor produksi,admin,foreman',
            'division_id' => 'nullable|exists:divisions,id',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);
            $validated['is_active'] = $request->has('is_active');

            User::create($validated);

            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create user');
        }
    }

    public function show(User $user)
    {
        $user->load('division');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $divisions = Division::orderBy('name')->get();
        return view('users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:ppc,operator,supervisor produksi,admin,foreman',
            'division_id' => 'nullable|exists:divisions,id',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $validated['is_active'] = $request->has('is_active');

            $user->update($validated);

            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update user');
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->id == auth()->id()) {
                return redirect()->back()->with('error', 'Cannot delete your own account');
            }

            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            if ($user->id == auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Cannot deactivate your own account'], 400);
            }

            $user->update(['is_active' => !$user->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'User status updated',
                'is_active' => $user->is_active
            ]);
        } catch (Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }
}
