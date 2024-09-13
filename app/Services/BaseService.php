<?php

namespace App\Services;

use App\Enum\ErrorCodes;
use App\Enum\General;
use App\Helpers\Helper;
use App\Helpers\Paginator;
use App\Models\User\Definitions\UserConst;
use App\Models\User\Definitions\UserDefs;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Array_;

abstract class BaseService
{
    /**
     * Main repository
     * @var BaseRepository $repository
     */
    protected BaseRepository $repository;

    /**
     * Handle dynamic method calls into the service.
     *
     * @param  mixed  $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(mixed $method, array $parameters)
    {
        return $this->repository->$method(...$parameters);
    }

    /**
     * Build common list
     *
     * @param array $condition
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function list(array $condition = [], array $relations = []): LengthAwarePaginator
    {
        $request = request()->all();
        $cacheKey = md5(request()->path() . http_build_query($request));

        return Cache::remember($cacheKey, 3600, function () use ($relations, $request) {
            $pageParams = Paginator::getPageSize();
            $condition = $this->buildCondition($request);
            if (isset($request['name_vi'])) {
                $condition[] = ['name_vi', 'like', '%' . $request['name_vi'] . '%'];
            }
            if (isset($request['name_en'])) {
                $condition[] = ['name_en', 'like', '%' . $request['name_en'] . '%'];
            }
            try {
                return $this->repository->query()
                    ->where($condition)
                    ->with($relations)
                    ->orderBy('id', General::SORT_DESC)
                    ->paginate($pageParams['limit']);
            } catch (Exception $exception) {
                Log::channel('fatal')->error(
                    'Message: ' . $exception->getMessage()
                    . ' File: ' . $exception->getFile()
                    . ' Line: ' . $exception->getLine()
                );

                return new LengthAwarePaginator([], 0, $pageParams['limit'], $pageParams['page']);
            }
        });
    }

    /**
     * Create new resource
     *
     * @param array $request
     * @return mixed|null
     */
    public function store(array $request = []): mixed
    {
        $request['created_by'] = auth()->user()->id ?? null;
        if ($result = $this->repository->create($request)) {
            return $result;
        }
        Log::channel('fatal')->error(
            'Message: Unable to create a new resource due to an error.'
            . ' Request: ' . json_encode($request)
        );

        return null;
    }

    /**
     * Show resource detail
     *
     * @param string $id
     * @return mixed|null
     */
    public function show(string $id): mixed
    {
        $result = $this->repository->find($id);
        if (!$result) {
            return null;
        }

        return $result;
    }

    /**
     * Update an existing resource
     *
     * @param string $id
     * @param array $request
     * @return false|mixed
     */
    public function update(string $id, array $request = []): mixed
    {
        if ($result = $this->repository->update($id, $request)) :
            return $result;
        endif;
        Log::channel('fatal')->error(
            'Message: Unable to update a resource due to an error.'
            . ' Resource ID: ' . $id
            . ' Request: ' . json_encode($request)
        );

        return false;
    }

    /**
     * Soft delete an existing resource
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        if ($result = $this->repository->softDelete($id)) :
            return $result;
        endif;
        Log::channel('fatal')->error(
            'Message: Unable to delete a resource due to an error.'
            . ' Resource ID: ' . $id
        );

        return false;
    }

    /**
     * Build Condition
     *
     * @param array $params
     * @return array|int[]
     */
    protected function buildCondition(array $params = []): array
    {
        $condition = collect([
            isset($params['created_at_after'])
                ? ['created_at', '>=', Helper::validDate($params['created_at_after'])]
                : null,
            isset($params['created_at_before'])
                ? ['created_at', '<=', Helper::validDate($params['created_at_before'])]
                : null,
            isset($params['id_after'])
                ? ['id', '>', $params['id_after']]
                : null,
        ])->filter()->toArray();

        if (
            auth()->check()
            && auth()->user()->role <= UserConst::ROLE_MANAGER
            && isset($params['status'])
        ) {
            $condition['status'] = $params['status'];
        }

        if (!auth()->check() || auth()->user()->role > UserConst::ROLE_MANAGER) {
            $condition['status'] = UserConst::STATUS_ACTIVE;
        }

        return $condition;
    }
}
