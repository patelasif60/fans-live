<?php
namespace App\Http\Controllers\Api;

use App\Models\Quizzes;
use App\Models\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Quiz\GetQuizRequest;
use App\Http\Requests\Api\Quiz\SubmitQuizRequest;
use App\Http\Resources\Quiz\Quiz as QuizResource;
use App\Services\QuizService;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * @group Quiz
 *
 * APIs for Quiz.
 */
class QuizController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(QuizService $service)
    {
        $this->service = $service;
    }

	/**
     * Get Quizzes
     *
     * @bodyParam club_id int required An id of a club. Example: 1
     *
     *
     * @return mixed
    */
    public function getQuizzes(GetQuizRequest $request)
    {
        $user = JWTAuth::user();
        $consumerId = Consumer::where('user_id', $user->id)->first()->id;

        $quizzes = $this->service->getQuizzes(
            $request->club_id,
            $consumerId
        );

        return QuizResource::collection($quizzes);
    }

    /**
     * Submit Quiz
     *
     * @bodyParam quiz_id int required An id of a quiz. Example: 1
     *
     *
     * @return mixed
    */
    public function submitQuiz(SubmitQuizRequest $request)
    {
        $user = JWTAuth::user();
        $consumerId = Consumer::where('user_id', $user->id)->first()->id;

        $checkEntry = $this->service->checkUserQuiz(
            $consumerId,
            $request->quiz_id
        );

        if(isset($checkEntry)) {
            return response()->json([
                'message' => 'Quiz answer already submitted.'
            ], 400);
        }

        $quiz = $this->service->submitQuiz(
            $consumerId,
            $request->quiz_id
        );

        if ($quiz) {
            return response()->json([
                'message' => 'Quiz answer has been submitted successfully.'
            ]);
        }
    }
}