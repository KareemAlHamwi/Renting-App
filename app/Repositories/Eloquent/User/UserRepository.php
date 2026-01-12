<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface {
    public function index(array $filters = []) {
        $q            = trim(string: (string)($filters['q'] ?? ''));
        $verification = $filters['verification'] ?? null;
        $role         = $filters['role'] ?? null;
        $perPage      = (int)($filters['per_page'] ?? 10);

        $query = User::with('person')
            ->orderByDesc('id');

        if ($q !== '') {
            $query->where(function (Builder $sub) use ($q) {
                $sub->where('username', 'like', "%{$q}%")
                    ->orWhere('phone_number', 'like', "%{$q}%")
                    ->orWhereHas('person', function (Builder $p) use ($q) {
                        $p->where('first_name', 'like', "%{$q}%")
                            ->orWhere('last_name', 'like', "%{$q}%");
                    });
            });
        }

        if ($verification === 'verified') {
            $query->whereNotNull('verified_at');
        } elseif ($verification === 'pending') {
            $query->whereNull('verified_at');
        }

        if ($role === 'user') {
            $query->where('role', 0);
        } elseif ($role === 'admin') {
            $query->where('role', 1);
        }

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findByPhone(string $phone) {
        return User::with('person')
            ->where('phone_number', $phone)
            ->first();
    }

    public function findByUsername(string $username) {
        return User::with('person')
            ->where('username', $username)
            ->first();
    }

    public function store(array $data) {
        $user = User::create($data);
        return $user->load('person');
    }

    public function update(User $user, array $data) {
        $user = User::findOrFail($user->id);
        $user->update($data);

        return $user->load('person');
    }

    public function updatePhone(User $user, string $phone) {
        $user = User::findOrFail($user->id);

        $user->phone_number = $phone;

        if ($user->isDirty('phone_number')) {
            $user->verified_at = null;
        }

        $user->save();

        return $user->load('person');
    }

    public function updatePassword(User $user, string $password) {
        $user = User::findOrFail($user->id);
        $user->update(['password' => $password]);

        return $user->load('person');
    }

    public function markAsVerified(User $user) {
        $user = User::findOrFail($user->id);
        $user->forceFill(['verified_at' => now()])->save();

        return $user->load('person');
    }

    public function destroy(User $user) {
        $user = User::findOrFail($user->id);
        $user->delete();

        return true;
    }

    public function deactivate(User $user) {
        $user->forceFill(['deactivated_at' => now()])->save();
    }

    public function activate(User $user) {
        $user->forceFill(['deactivated_at' => null])->save();
    }

    public function isActivated(User $user) {
        return is_null($user->deactivated_at);
    }

    public function isVerified(User $user): bool {
        return User::query()
            ->whereKey($user->getKey())
            ->whereNotNull('verified_at')
            ->exists();
    }
}
