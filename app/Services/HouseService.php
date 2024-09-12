<?php

namespace App\Services;

use App\Contracts\Repositories\HouseRepositoryInterface;
use App\Core\Files\FileManager;
use App\Enum\ErrorCodes;
use App\Enum\General;
use App\Models\House\Definitions\HouseDefs;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class HouseService extends BaseService
{
    /**
     * Constructor
     *
     * @param HouseRepositoryInterface $houseRepository
     */
    public function __construct(
        protected HouseRepositoryInterface $houseRepository,
    )
    {
        $this->repository = $this->houseRepository;
    }

    /**
     * List House
     *
     * @return LengthAwarePaginator
     */
    public function listHouse(): LengthAwarePaginator
    {
        $request = request()->toArray();
        $condition = array_filter([
            'province_code' => $request['province_code'] ?? null,
            'district_code' => $request['district_code'] ?? null,
            'ward_code'     => $request['ward_code'] ?? null,
            'category_id'   => $request['category_id'] ?? null,
        ]);

        return $this->list($condition);
    }

    /**
     * Store new house
     *
     * @param array $request
     * @return mixed
     * @throws ApiException
     */
    public function storeHouse(array $request = []): mixed
    {
        try {
            $request['lessor_id'] = auth()->user()->id;
            if ($request['thumbnail']) {
                $request['thumbnail'] = FileManager::storeFile(
                    $request['thumbnail'],
                    HouseDefs::FILE_PATH
                );
            }
            if (isset($request['method']) && $request['method'] == General::REQUEST_METHOD_DRAFT) {
                $request['status'] = HouseDefs::STATUS_DRAFT;
            }

            return $this->houseRepository->create($request);
        } catch (Exception $exception) {
            throw new ApiException(ErrorCodes::BAD_REQUEST);
        }
    }

    /**
     * Update house by Id
     *
     * @param int $id
     * @param array $request
     * @return mixed
     * @throws ApiException
     */
    public function updateHouse(int $id, array $request = []): mixed
    {
        try {
            if (!$house = $this->houseRepository->find($id)) {
                throw new ApiException(
                    ErrorCodes::NOT_FOUND,
                    __('message.error.not_found')
                );
            }
//            FileManager::removeFile(
//                $house->thumbnail,
//                HouseDefs::FILE_PATH
//            );
            $request['thumbnail'] = FileManager::storeFile(
                $request['thumbnail'],
                HouseDefs::FILE_PATH
            );

            return $this->houseRepository->update($id, $request);
        } catch (Exception $exception) {
            throw new ApiException(ErrorCodes::BAD_REQUEST);
        }
    }

    public function getTop()
    {
        return 5;
    }
}
