<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

class BookServiceProvider
{
    private $table;

    public function __construct()
    {
        $this->table = DB::table('books');
    }

    public function create(array $data): bool
    {
        return $this->table->insert($data);
    }

    public function read($id)
    {
        return $this->table->where('id', $id)->first();
    }

    public function readAll(): \Illuminate\Support\Collection
    {
        return $this->table->get();
    }

    public function update($id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }
        return $this->table->where('id', $id)->update($data);
    }

    public function delete($id): int
    {
        return $this->table->delete(['id' => $id]);
    }

    public function lastThree(): \Illuminate\Support\Collection
    {
        return $this->table->orderBy('created_at', 'desc')->take(3)->get();
    }

    public function getNewest()
    {
        return $this->table->orderBy('created_at', 'desc')->first();
    }

}
