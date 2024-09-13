<?php

namespace App\Services;

use App\Core\Logger\Log;
use App\Core\File\FileService;
use App\Enum\General;
use App\Enum\RoomEnum;
use App\Enum\ErrorCodes;
use App\Helpers\Common;
use App\Helpers\ResponseHelper;
use App\Http\Entities\Tag\ShortTagEntity;
use App\Http\Entities\Room\RoomDetailEntity;
use App\Contracts\Repositories\TagRepositoryInterface;
use App\Contracts\Repositories\RoomRepositoryInterface;
use App\Exceptions\ApiException;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomService extends BaseService
{
    /**
     * Constructor
     *
     * @param RoomRepositoryInterface $roomRepository
     * @param TagRepositoryInterface $tagRepository
     */
    public function __construct(
        protected RoomRepositoryInterface   $roomRepository,
        protected TagRepositoryInterface    $tagRepository,
    )
    {
        $this->repository = $this->roomRepository;
    }

    /**
     * List House
     * Created by PhongTranNTQ
     *
     * @return LengthAwarePaginator
     */
    public function listRoom(): LengthAwarePaginator
    {
        $request = request()->toArray();
        $condition = array_filter([
            'category_id'   => $request['tag'] ?? null,
        ]);

        return $this->list($condition, ['house']);
    }

    /**
     * Store new house
     *
     * @param array $request
     * @return mixed
     * @throws ApiException
     * @throws Exception
     */
    public function storeRoom(array $request = []): mixed
    {
        try {
            $request['lessor_id'] = auth()->user()->id;
            $images = [];
            foreach ($request['images'] as $image) :
                $images[] = FileService::storeFile(
                    $image,
                    RoomDefs::FILE_PATH
                );
            endforeach;
            $request['images'] = $images;
            $request['detail'] = new RoomDetailEntity([
                'capacity'          => $request['capacity'] ?? null,
                'floor'             => $request['floor'] ?? null,
                'size'              => $request['size'] ?? null,
                'apartment_type'    => $request['apartment_type'] ?? null,
                'current_condition' => $request['current_condition'] ?? null,
                'more'              => $request['more'] ?? null,
            ]);
            $tags = [];
            foreach ($request['tags'] as $tag) :
                $tags[] = new ShortTagEntity($this->tagRepository->find($tag));
            endforeach;
            $request['tags'] = $tags;

            return $this->store($request);
        } catch (Exception $exception) {
            Log::error('error', $exception->getMessage());
            throw new ApiException(ErrorCodes::BAD_REQUEST);
        }
    }

    /**
     * Update house by id
     *
     * @param int $id
     * @param array $request
     * @return mixed
     * @throws ApiException
     * @throws Exception
     */
    public function updateRoom(int $id, array $request = []): mixed
    {
        try {
            if (!$room = $this->roomRepository->find($id)) :
                throw new ApiException(
                    ErrorCodes::NOT_FOUND,
                    __('message.error.not_found')
                );
            endif;
//            FileService::removeFile(
//                $house->thumbnail,
//                HouseDefs::FILE_PATH
//            );
//            $request['thumbnail'] = FileService::storeFile(
//                $request['thumbnail'],
//                HouseDefs::FILE_PATH
//            );
            return null;
        } catch (Exception $exception) {
            Log::error('error', $exception->getMessage());
            throw new ApiException(ErrorCodes::BAD_REQUEST);
        }
    }
}
