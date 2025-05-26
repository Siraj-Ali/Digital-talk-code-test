<?php

namespace App\Http\Controllers\Api;

use App\DTOs\TranslationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\TranslationRequest;
use App\Http\Resources\TranslationResource;
use App\Services\Interfaces\TranslationServiceInterface;
use App\Trait\HttpResponse;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TranslationController extends Controller
{
    use HttpResponse; 
    /**
     * Constructor with dependency injection
     */
    public function __construct(
        private readonly TranslationServiceInterface $translationService
    ) {}

    /**
     * Get all translations record with optional filtering
     *
     * Get(
     *     Parameter:
     *         name="key",
     *         in="query",
     *         description="Filter by translation key (partial match)",
     *         required=false,
     *         Schema(type="string")
     *     
     *     Parameter:
     *         name="value",
     *         in="query",
     *         description="Filter by translation value (partial match)",
     *         required=false,
     *         Schema(type="string")
     *     
     *     Parameter:
     *         name="locale_id",
     *         in="query",
     *         description="Filter by locale ID",
     *         required=false,
     *         Schema(type="integer")
     *     
     *     Parameter:
     *         name="device_type",
     *         in="query",
     *         description="Filter by device type",
     *         required=false,
     *         Schema(type="string", enum={"mobile", "tablet", "desktop"})
     *     
     *     Parameter
     *         name="group",
     *         in="query",
     *         description="Filter by translation group",
     *         required=false,
     *         Schema(type="string")
     *     
     *     Parameter:
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         Schema(type="integer", default=15, minimum=1, maximum=100)
     *     
     *     Parameter:
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         Schema(type="integer", default=1, minimum=1)
     *     
     *     Response:
     *         response=200,
     *         description="List of translations",
     *    
     *     Response:
     *         response=401,
     *         description="Unauthorized"
     * 
     *     Response:
     *         response=403,
     *         description="Forbidden",
     *  
     */
    public function index(Request $request)
    {
        try {
            
            $filters = $request->only(['key', 'value', 'locale_id', 'device_type', 'group']);
            $filters['per_page'] = $request->input('per_page', 15);

            $translations = $this->translationService->searchTranslations($filters);

            return $this->successResponse(TranslationResource::collection($translations), 'Translations data', 200);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return $this->errorResponse('Server error', 500);

        }
    
    }

    /**
     * Create a new translation record
     *      Post(
        *     path="/api/translations",
         *     summary="Create a new translation",
         *     description="Add a new translation to the system",
         *     operationId="createTranslation",
         *     tags={"Translations"},
         *     security={{"bearerAuth":{}}},
         *     @OA\RequestBody(
         *         required=true,
         *         description="Translation data",
         *         @OA\JsonContent(ref="#/components/schemas/TranslationRequest")
         *  
     *     Response:
     *         response=200,
     *         description="Translation created successfully",
     *         
     *     Response:
     *         response=401,
     *         description="Unauthorized",
     *     
     *     Response:
     *         response=400,
     *         description="Duplicate translation"
     * 
     *     Response:
     *         response=500,
     *         description="Server error",
     *    )
     */
    public function store(TranslationRequest $request)
    {
        // return response()->json(['request:' => $request->all()]);
        try {
            
            $dto = TranslationDTO::fromRequest($request);

            $translation = $this->translationService->createTranslation($dto);
            return $this->successResponse(new TranslationResource($translation), 'Translation added successfully.', 200);

        } catch (UniqueConstraintViolationException $e) {

            Log::error($e->getMessage());
            return $this->errorResponse('translation key already exists, Please try again with different key.', 400);

        } catch (\Exception $e) {

            Log::error('Something went wrong at TranslationController@store ' . $e->getMessage());
            return $this->errorResponse('Server Error', 500);

        }
    }

    /**
     * Get a specific translation record by ID
     *   Get(
     *     path="/api/translations/{id}",
     *     summary="Get a specific translation",
     *     description="Retrieve details for a single translation by its ID",
     *     operationId="getTranslation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     Response:
     *         response=200,
     *         description="Translation details"
     *       
     *     Response:
     *         response=401,
     *         description="Unauthorized",
     *     
     *     Response:
     *         response=404,
     *         description="Translation not found",
     *      
     *     Response:
     *         response=500,
     *         description="Server error",
     *    
     */
    public function show(int $id)
    {
        try {

            $translation = $this->translationService->getTranslation((int) $id);
            return response()->json(new TranslationResource($translation));
            return $this->successResponse(new TranslationResource($translation), 'Translations detail', 200);

        } catch (\Exception $e) {

            Log::error('Something went wrong at TranslationController@show ' . $e->getMessage());
            return $this->errorResponse('Server error', 500);

        }
    }

    /**
     * Update an existing translation record
     *     Put(
     *     path="/api/translations/{id}",
     *     summary="Update a translation",
     *     description="Update an existing translation",
     *     operationId="updateTranslation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     RequestBody:
     *         required=true,
     *         description="Translation data",
     *    
     *     Response:
     *         response=200,
     *         description="Translation updated successfully",
     * 
     *      Response:
     *         response=400,
     *         description="Duplicate translation"
     * 
     *     Response:
     *         response=401,
     *         description="Unauthorized",
     * 
     *     Response:
     *         response=404,
     *         description="Translation not found"
     * 
     *     Response:
     *         response=400,
     *         description="Duplicate translation",
     * 
     *     Response:
     *         response=500,
     *         description="Server error",
     */
    public function update(TranslationRequest $request, int $id)
    {
        try {
            $dto = TranslationDTO::fromRequest($request, $id);

            $translation = $this->translationService->updateTranslation((int) $id, $dto);
            return $this->successResponse(new TranslationResource($translation), 'Translation updated successfully.', 200);
        } catch (UniqueConstraintViolationException $e) {

            Log::error($e->getMessage());
            return $this->errorResponse('translation key already exists, Please try again with different key.', 400);

        } catch (\Exception $e) {
            Log::error('Something went wrong at TranslationController@update ' . $e->getMessage());
            return $this->errorResponse('Server Error', 500);
        }
    }

    /**
     * Delete a translation record
     *     Delete(
     *     path="/api/translations/{id}",
     *     summary="Delete a translation",
     *     description="Remove a translation from the system",
     *     operationId="deleteTranslation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     Response:
     *         response=200,
     *         description="Translation deleted successfully",
     * 
     *     Response:
     *         response=401,
     *         description="Unauthorized"
     * 
     *     Response:
     *         response=404,
     *         description="Translation not found"
     * 
     *     Response:
     *         response=500,
     *         description="Server error"
     */
    public function destroy(int $id)
    {
        try {
            
            $this->translationService->deleteTranslation((int) $id);
            return $this->successResponse(null, 'Translation deleted successfully', 200);

        } catch (\Exception $e) {
            
            Log::error('Something went wrong at TranslationController@destroy ' . $e->getMessage());
            return $this->errorResponse('Server Error', 500);
            
        }
    }

    /**
     * Get translations record by locale code
     *     Get(
     *     path="/api/translations/locale/{locale}",
     *     summary="Get translations by locale",
     *     description="Retrieve all translations for a specific locale code with optional device type filtering",
     *     operationId="getTranslationsByLocale",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         required=true,
     *         description="Locale code (e.g., en, es, fr)",
     *         @OA\Schema(type="string")
     *     ),
     *     Parameter:
     *         name="device_type",
     *         in="query",
     *         required=false,
     *         description="Device type filter",
     *    
     *     Response:
     *         response=200,
     *         description="Translations for the specified locale"
     * 
     *     Response:
     *         response=401,
     *         description="Unauthorized"
     * 
     *     Response:
     *         response=500,
     *         description="Server error"
     * 
     */
    public function getByLocale(string $locale, Request $request)
    {
        try {

            $deviceType = $request->input('device_type');
            $translations = $this->translationService->getTranslationsByLocale($locale, $deviceType);
            return $this->successResponse($translations, 'Translation data', 200);
            
        } catch (\Exception $e) {
            
            Log::error('Something went wrong at TranslationController@getByLocale ' . $e->getMessage());
            return $this->errorResponse('Server Error', 500);

        }
    }

}
