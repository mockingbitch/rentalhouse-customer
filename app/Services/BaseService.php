<?php

namespace App\Services;

use App\Enum\ErrorCodes;
use App\Enum\General;
use App\Helpers\Helper;
use App\Helpers\Paginator;
use App\Models\User\Definitions\UserConstants;
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
        $cacheKey = md5('house_list' . http_build_query(request()->all()));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $request = request()->all();
        $pageParams = Paginator::getPageSize();
        try {
            $condition = array_merge($condition, $this->buildCondition($request));
            if (isset($request['name_vi'])) {
                $condition[] = ['name_vi', 'like', '%' . $request['name_vi'] . '%'];
            }
            if (isset($request['name_en'])) {
                $condition[] = ['name_vi', 'like', '%' . $request['name_en'] . '%'];
            }

            $data = $this->repository->query()
                ->where($condition)
                ->with($relations)
                ->orderBy('id', General::SORT_DESC)
                ->paginate($pageParams['limit']);
            Cache::put($cacheKey, $data, 3600);

            return $data;
        } catch (Exception $exception) {
            Log::channel('fatal')->error($exception->getMessage());
            return new LengthAwarePaginator(null, 0, $pageParams['limit'], $pageParams['page']);
        }
    }

    /**
     * Create new resource
     *
     * @param array $request
     * @return mixed
     * @throws ApiException
     * @throws Exception
     */
    public function store(array $request = []): mixed
    {
        $request['created_by'] = auth()->user()->id ?? null;
        if ($result = $this->repository->create($request)) :
            return $result;
        endif;
        Log::error('error', __('message.common.error.bad_request'));

        throw new ApiException(ErrorCodes::BAD_REQUEST);
    }

    /**
     * Show resource detail
     *
     * @param string $id
     * @return mixed
     * @throws ApiException
     */
    public function show(string $id): mixed
    {
        $result = $this->repository->find($id);
        if (!$result) :
            throw new ApiException(
                ErrorCodes::NOT_FOUND,
                __('message.error.not_found')
            );
        endif;

        return $result;
    }

    /**
     * Update resource
     *
     * @param string $id
     * @param array $request
     * @return false|mixed
     * @throws ApiException
     */
    public function update(string $id, array $request = []): mixed
    {
        if ($result = $this->repository->update($id, $request)) :
            return $result;
        endif;

        throw new ApiException(
            ErrorCodes::NOT_FOUND,
            __('message.error.not_found')
        );
    }

    /**
     * Soft delete resource
     *
     * @param int $id
     * @return bool
     * @throws ApiException
     */
    public function destroy(int $id): bool
    {
        if ($result = $this->repository->softDelete($id)) :
            return $result;
        endif;

        throw new ApiException(
            ErrorCodes::NOT_FOUND,
            __('message.error.not_found')
        );
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
            && auth()->user()->role <= UserConstants::ROLE_MANAGER
            && isset($params['status'])
        ) {
            $condition['status'] = $params['status'];
        }

        if (!auth()->check() || auth()->user()->role > UserConstants::ROLE_MANAGER) {
            $condition['status'] = UserConstants::STATUS_ACTIVE;
        }

        return $condition;
    }

    /**
     * Get Type master data
     * @param int $key
     * @param int|null $key_id
     * @return Model|Builder|Collection
     */
    public function getType(int $key, ?int $key_id): Model|Builder|Collection
    {
        $query = DB::table('types_mst')->where('key', $key);
        if ($key_id) :
            return $query->where('key_id', $key_id)->first();
        endif;

        return $query->get();
    }
}
