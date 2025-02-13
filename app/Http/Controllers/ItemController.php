<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\DeleteItemRequest;
use App\Http\Requests\Items\ShowItemRequest;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function __construct(protected ItemRepositoryInterface $itemRepositoryInterface)
    {
        $this->itemRepositoryInterface = $itemRepositoryInterface;
    }

    public function index() {
        try {

            $items = $this->itemRepositoryInterface->all(['*'], [], true);
            return ItemResource::collection($items);

        } catch(\Exception $error) {
            Log::error('ItemController - (index) : ' . $error->getMessage() . "\n Trace : " . json_encode($error->getTrace()));
            return response()->json(['errors' => 'Something wrong !'], 500);
        }
    }

    public function show(int $id, ShowItemRequest $request) {
        try {

            $items = $this->itemRepositoryInterface->find($id);
            return new ItemResource($items);

        } catch(\Exception $error) {
            Log::error('ItemController - (show) : ' . $error->getMessage() . "\n Trace : " . json_encode($error->getTrace()));
            return response()->json(['errors' => 'Something wrong !'], 500);
        }
    }

    public function store(StoreItemRequest $request) {
        try {

            $validated_data = $request->validated();
            $this->itemRepositoryInterface->create($validated_data);   
            return response()->json(['message' => 'This item has been stored successfully.'], 201);

        } catch(\Exception $error) {
            Log::error('ItemController - (store) : ' . $error->getMessage() . "\n Trace : " . json_encode($error->getTrace()));
            return response()->json(['errors' => 'Something wrong !'], 500);
        }
    }

    public function update(int $id, UpdateItemRequest $request) {
        try {

            $validated_data = $request->validated();
            $this->itemRepositoryInterface->update($id, $validated_data);
            return response()->json(['message' => 'This item has been updated successfully.'], 200);

        } catch(\Exception $error) {
            Log::error('ItemController - (update) : ' . $error->getMessage() . "\n Trace : " . json_encode($error->getTrace()));
            return response()->json(['errors' => 'Something wrong !'], 500);
        }
    }

    public function destroy(int $id, DeleteItemRequest $request) {
        try {

            $this->itemRepositoryInterface->delete($id);
            return response()->json(['message' => 'This item has been deleted successfully.'], 200);

        } catch(\Exception $error) {
            Log::error('ItemController - (destroy) : ' . $error->getMessage() . "\n Trace : " . json_encode($error->getTrace()));
            return response()->json(['errors' => 'Something wrong !'], 500);
        }
    }

}
