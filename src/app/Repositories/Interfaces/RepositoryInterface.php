<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    /**
     * @return Collection
     */
    public function all();

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data);

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id);

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id);

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id);
}
