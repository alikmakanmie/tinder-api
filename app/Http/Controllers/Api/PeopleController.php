<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Swipe;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Tinder API Documentation",
 *     version="1.0.0",
 *     description="API for Tinder-like dating application",
 *     @OA\Contact(
 *         email="support@tinder-api.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 */
class PeopleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/people/recommended",
     *     summary="Get list of recommended people",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function recommended(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentUserId = $request->input('user_id', 1);
        
        $swipedUserIds = Swipe::where('user_id', $currentUserId)
            ->pluck('target_user_id')
            ->toArray();
        
        $people = User::with('pictures')
            ->where('id', '!=', $currentUserId)
            ->whereNotIn('id', $swipedUserIds)
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $people
        ]);
    }
    
    /**
     * @OA\Post(
     *     path="/people/{id}/like",
     *     summary="Like a person",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Target user ID to like",
     *         required=true,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person liked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person liked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Already swiped this person"
     *     )
     * )
     */
    public function like(Request $request, $id)
    {
        $currentUserId = $request->input('user_id', 1);
        
        try {
            $swipe = Swipe::create([
                'user_id' => $currentUserId,
                'target_user_id' => $id,
                'action' => 'like'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Person liked successfully',
                'data' => $swipe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Already swiped this person'
            ], 400);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/people/{id}/dislike",
     *     summary="Dislike a person",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Target user ID to dislike",
     *         required=true,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person disliked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person disliked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Already swiped this person"
     *     )
     * )
     */
    public function dislike(Request $request, $id)
    {
        $currentUserId = $request->input('user_id', 1);
        
        try {
            $swipe = Swipe::create([
                'user_id' => $currentUserId,
                'target_user_id' => $id,
                'action' => 'dislike'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Person disliked successfully',
                'data' => $swipe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Already swiped this person'
            ], 400);
        }
    }
    
    /**
     * @OA\Get(
     *     path="/people/liked",
     *     summary="Get list of liked people",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function liked(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentUserId = $request->input('user_id', 1);
        
        $likedPeople = Swipe::where('user_id', $currentUserId)
            ->where('action', 'like')
            ->with(['targetUser.pictures'])
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $likedPeople
        ]);
    }
}
