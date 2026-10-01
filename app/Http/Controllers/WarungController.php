<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarungController extends Controller
{
    /**
     * Menampilkan daftar warung.
     */
    public function index(Request $request): View
    {
        $query = Warung::with('user')
            ->withCount('orders')
            ->latest('id');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status') === 'active'
            );
        }

        $warungs = $query
            ->paginate(10)
            ->withQueryString();

        return view('admin.warungs.index', compact('warungs'));
    }

    /**
     * Form tambah warung.
     */
    public function create(): View
    {
        return view('admin.warungs.create');
    }

    /**
     * Simpan warung baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:30',
                'unique:warungs,code',
            ],

            'owner_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => UserRole::WARUNG,
                'status' => true,
            ]);

            Warung::create([
                'user_id' => $user->id,
                'code' => $validated['code'],
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        return redirect()
            ->route('admin.warungs.index')
            ->with('success', 'Warung berhasil ditambahkan.');
    }

    /**
     * Form edit warung.
     */
    public function edit(Warung $warung): View
    {
        $warung->load('user');

        return view('admin.warungs.edit', compact('warung'));
    }

    /**
     * Update warung.
     */
    public function update(
        Request $request,
        Warung $warung
    ): RedirectResponse {
        $warung->load('user');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('warungs', 'code')
                    ->ignore($warung->id),
            ],

            'owner_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($warung->user_id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $warung
        ) {
            $warung->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);

            $userData = [
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $warung->user->update($userData);
        });

        return redirect()
            ->route('admin.warungs.index')
            ->with('success', 'Data warung berhasil diperbarui.');
    }

    /**
     * Hapus warung.
     */
    public function destroy(Warung $warung): RedirectResponse
    {
        /*
         * Jangan hapus warung yang sudah memiliki
         * riwayat kebutuhan/order.
         */
        if ($warung->orders()->exists()) {
            return redirect()
                ->route('admin.warungs.index')
                ->with(
                    'error',
                    'Warung tidak dapat dihapus karena sudah memiliki riwayat kebutuhan. Silakan nonaktifkan warung.'
                );
        }

        DB::transaction(function () use ($warung) {
            $user = $warung->user;

            $warung->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.warungs.index')
            ->with('success', 'Warung berhasil dihapus.');
    }
}