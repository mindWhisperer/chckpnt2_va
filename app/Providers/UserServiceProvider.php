<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserServiceProvider
{
    private $table;

    public function __construct()
    {
        $this->table = DB::table('users');
    }

    public function getAll(): array
    {
        return $this->table->get()->toArray();
    }

    public function getById(int $id): ?object
    {
        return $this->table->where('id', $id)->first();
    }

    public function create(array $data): bool
    {
        $data['password'] = Hash::make($data['password']);
        return $this->table->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->table->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->table->where('id', $id)->delete();

    }

    public function getByEmail(string $email): ?object
    {
        return $this->table->where('email', $email)->first();
    }

    public function getByName(string $username): ?object
    {
        return $this->table->where('name', $username)->first();
    }
}
