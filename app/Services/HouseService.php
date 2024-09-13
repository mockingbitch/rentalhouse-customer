<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enum\General;
use App\Core\Files\FileManager;
use App\Models\House\Definitions\HouseConst;
use App\Contracts\Repositories\HouseRepositoryInterface;
use Exception;

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
     * Created by PhongTranNTQ
     *
     * @param array $request
     * @return mixed
     */
    public function storeHouse(array $request = []): mixed
    {
        try {
            $request['lessor_id'] = auth()->user()->id;
            if ($request['thumbnail']) {
                $request['thumbnail'] = FileManager::storeFile(
                    $request['thumbnail'],
                    HouseConst::FILE_PATH
                );
            }
            if (isset($request['method']) && $request['method'] == General::REQUEST_METHOD_DRAFT) {
                $request['status'] = HouseConst::STATUS_DRAFT;
            }

            return $this->houseRepository->create($request);
        } catch (Exception $exception) {
            Log::channel('fatal')->error(
                'Message: ' . $exception->getMessage()
                . ' File: ' . $exception->getFile()
                . ' Line: ' . $exception->getLine()
            );

            return false;
        }
    }

    /**
     * Update house by ID
     * Created by PhongTranNTQ
     *
     * @param int $id
     * @param array $request
     * @return mixed
     */
    public function updateHouse(int $id, array $request = []): mixed
    {
        try {
            if (!$house = $this->houseRepository->find($id)) {
                Log::channel('warning')->error(
                    'Message: Resource not found ' . $id
                );
                return false;
            }
//            FileManager::removeFile(
//                $house->thumbnail,
//                HouseDefs::FILE_PATH
//            );
            $request['thumbnail'] = FileManager::storeFile(
                $request['thumbnail'],
                HouseConst::FILE_PATH
            );

            return $this->houseRepository->update($id, $request);
        } catch (Exception $exception) {
            Log::channel('fatal')->error(
                'Message: ' . $exception->getMessage()
                . ' File: ' . $exception->getFile()
                . ' Line: ' . $exception->getLine()
            );

            return false;
        }
    }
}
