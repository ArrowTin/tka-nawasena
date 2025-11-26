<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class DataTableBuilder
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * MULTI SEARCH COLUMN (dari datatables -> filters:[{column, value}])
     */
    public function multiSearch(array $filters = [])
    {
        foreach ($filters as $f) {

            if (!isset($f['column']) || !isset($f['value'])) {
                continue;
            }

            $value = trim($f['value']);
            if ($value === "") continue;

            $column = $f['column'];

            // Filtering relasi: subjectTypes.name
            if (str_contains($column, '.')) {

                [$relation, $field] = explode('.', $column);

                $this->query->whereHas($relation, function ($q) use ($field, $value) {
                    $q->where($field, 'LIKE', "%{$value}%");
                });

            } else {
                // Filtering kolom biasa
                $this->query->where($column, 'LIKE', "%{$value}%");
            }
        }

        return $this;
    }


    public function search(array $columns, ?string $keyword)
    {
        if (!$keyword) return $this;

        $valid = array_filter($columns, function ($col) {
            return is_string($col) && !is_numeric($col);
        });

        if (empty($valid)) return $this;

        $this->query->where(function ($q) use ($valid, $keyword) {
            foreach ($valid as $col) {
                $q->orWhere($col, 'LIKE', "%{$keyword}%");
            }
        });

        return $this;
    }


    /**
     * SEARCH RELATION SECARA GLOBAL
     */
    public function searchRelation(string $relation, array $columns, ?string $keyword)
    {
        if (!$keyword) return $this;

        $valid = array_filter($columns, function ($col) {
            return is_string($col) && !is_numeric($col);
        });

        if (empty($valid)) return $this;

        $this->query->whereHas($relation, function ($q) use ($valid, $keyword) {
            foreach ($valid as $col) {
                $q->orWhere($col, 'LIKE', "%{$keyword}%");
            }
        });

        return $this;
    }


    /**
     * SORT RELASI & NON RELASI
     * contoh: subjectTypes.name
     */
    public function sort(?string $column, string $direction = 'asc')
{
    if (!$column || is_numeric($column)) {
        return $this;
    }

    // Jika kolom relasi, contoh: subjectTypes.name
    if (str_contains($column, '.')) {

        [$relationName, $field] = explode('.', $column);

        $model = $this->query->getModel();

        if (!method_exists($model, $relationName)) {
            return $this;
        }

        $relation = $model->$relationName();
        $related = $relation->getRelated();
        $relatedTable = $related->getTable();
        $parentTable = $model->getTable();

        /**
         * ===============
         * BELONGS TO
         * ===============
         */
        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {

            $foreignKey = $relation->getForeignKeyName();
            $ownerKey   = $relation->getOwnerKeyName();

            $this->query
                ->leftJoin($relatedTable, "{$relatedTable}.{$ownerKey}", "{$parentTable}.{$foreignKey}")
                ->select("{$parentTable}.*")
                ->selectRaw("{$relatedTable}.{$field} AS sort_value")
                ->orderBy('sort_value', $direction);

            return $this;
        }

        /**
         * ===============
         * HAS ONE / MANY
         * ===============
         */
        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne ||
            $relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany ||
            $relation instanceof \Illuminate\Database\Eloquent\Relations\MorphMany) {

            $foreignKey = $relation->getForeignKeyName();
            $localKey   = $relation->getLocalKeyName();

            $this->query
                ->leftJoin($relatedTable, "{$relatedTable}.{$foreignKey}", "{$parentTable}.{$localKey}")
                ->select("{$parentTable}.*")
                ->selectRaw("{$relatedTable}.{$field} AS sort_value")
                ->orderBy("sort_value", $direction)
                ->distinct();

            return $this;
        }

        /**
         * ================================
         * BELONGS TO MANY (PIVOT TABLE)
         * ================================
         */
        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany ||
            $relation instanceof \Illuminate\Database\Eloquent\Relations\MorphToMany) {

            $pivotTable      = $relation->getTable(); // pivot
            $foreignPivotKey = $relation->getForeignPivotKeyName();
            $relatedPivotKey = $relation->getRelatedPivotKeyName();
            $parentKey       = $relation->getParentKeyName();
            $relatedKey      = $relation->getRelatedKeyName();

            $this->query
                ->leftJoin("{$pivotTable} as pivot", "pivot.{$foreignPivotKey}", "{$parentTable}.{$parentKey}")
                ->leftJoin("{$relatedTable} as rel", "rel.{$relatedKey}", "pivot.{$relatedPivotKey}")
                ->select("{$parentTable}.*")
                ->selectRaw("rel.{$field} AS sort_value")   // **FIX UTAMA**
                ->orderBy("sort_value", $direction)
                ->distinct();

            return $this;
        }
    }

    // Sorting kolom biasa
    $this->query->orderBy($column, $direction);

    return $this;
}






    /**
     * APPLY PAGINATION SESUAI DATATABLES
     */
    public function apply(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, ['*'], 'page', $page);
    }
}
