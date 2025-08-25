<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService implements BaseServiceInterface
{
    // Chạy trong transaction
    protected function tx(Closure $callback)
    {
        return DB::transaction($callback);
    }

    // Chuẩn hoá try/catch -> ServiceException
    protected function safe(Closure $callback)
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            Log::error('[Service Error] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw new ServiceException($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    // Phân trang nhanh cho Builder
    protected function paginate(Builder $query, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    // Tìm bản ghi hoặc ném ServiceException (thay vì 404 HTTP)
    protected function findOrFail(Model|string $model, $id): Model
    {
        /** @var Model $m */
        $m = is_string($model) ? app($model) : $model;
        $found = $m->newQuery()->find($id);
        if (!$found) {
            throw new ServiceException('Không tìm thấy bản ghi.');
        }
        return $found;
    }

    // Cập nhật nhanh
    protected function updateById(Model|string $model, $id, array $data): Model
    {
        $record = $this->findOrFail($model, $id);
        $record->update($data);
        return $record->refresh();
    }

    // Tạo nhanh
    protected function createOne(Model|string $model, array $data): Model
    {
        /** @var Model $m */
        $m = is_string($model) ? app($model) : $model;
        return $m->newQuery()->create($data);
    }
}