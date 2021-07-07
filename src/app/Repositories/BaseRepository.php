<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    public const FETCH_TYPE_PAGINATE   = 1;
    public const FETCH_TYPE_COLLECT    = 2;
    public const PAGINATION_ITEM_PER_PAGE = 12;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model::all();
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    /**
     * @param array $data
     *
     * @param int|string $id
     *
     * @return bool
     */
    public function update(array $data, $id): bool
    {
        return $this->model::findOrFail($id)->update($data);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function updateOrCreate(array $data)
    {
        return $this->model::updateOrCreate($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function destroy(int $id): bool
    {
        return $this->model::findOrFail($id)->delete();
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function find($id): Model
    {
        return $this->model::findOrFail($id);
    }
    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param array $attributes
     * @param array $with
     * @param array|string[] $columns
     * @param int|null $fetchType
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|Collection|Model
     */
    public function findByAttributes(array $attributes, ?int $fetchType = null, array $with = [], array $columns = array("*"))
    {
        $query = $this->model->query();

        foreach ($attributes as $field => $value) {
            $query = $query->where($field, $value);
        }

        if (!empty($with)) {
            foreach ($with as $relation) {
                $query = $query->with($relation);
            }
        }

        if ($fetchType === self::FETCH_TYPE_PAGINATE) {
            return $query->paginate(self::PAGINATION_ITEM_PER_PAGE);
        } elseif ($fetchType === self::FETCH_TYPE_COLLECT) {
            return $query->get();
        }

        return $query->first($columns);
    }
}
