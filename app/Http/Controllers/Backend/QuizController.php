<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Services\QuizService;
use App\Models\Quizzes;
use App\Models\QuizEndText;
use App\Models\QuizMultipleChoiceQuestions;
use App\Models\QuizFillInTheBlank;
use JavaScript;

class QuizController extends Controller
{

	/**
	 * The news service instance.
	 *
	 * @var service
	 */
	public function __construct(QuizService $service)
	{
		$this->service = $service;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizType = config('fanslive.QUIZ_TYPE');
        Javascript::put([
            'quizType' => json_encode($quizType),
            'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
        ]);
		return view('backend.quizzes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$quizType = config('fanslive.QUIZ_TYPE');
		$isTrue = config('fanslive.TRUE_FALSE');
		$quizStatus = config('fanslive.PUBLISH_STATUS');
		$players = Player::all();
		JavaScript::put([
			'players'        => $players,
		]);

		return view('backend.quizzes.create', compact('quizType','isTrue','quizStatus','players'));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param $club
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $club)
    {
		$clubId = getClubIdBySlug($club);
		$quiz = $this->service->create(
			$clubId,
			auth()->user(),
			$request->all()
		);

		if ($quiz) {
			flash('Quizzes created successfully')->success();
		} else {
			flash('Quizzes could not be created. Please try again.')->error();
		}

		return redirect()->route('backend.quizzes.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $club, Quizzes $quiz)
    {
        $clubId = getClubIdBySlug($club);
        $quizType = config('fanslive.QUIZ_TYPE');
        $isTrue = config('fanslive.TRUE_FALSE');
        $quizStatus = config('fanslive.PUBLISH_STATUS');
        $players = Player::all();
        $quizEndTexts = QuizEndText::where(['quiz_id' => $quiz->id])->get();

        $quizFillInTheBlanks = QuizFillInTheBlank::where(['quiz_id' => $quiz->id])->get();
        $quizMultipleChoiseQuestions = QuizMultipleChoiceQuestions::with(['quizMultipleChoiceQuestionAnswers' => function($q) {
            $q->select('quiz_multiple_choice_question_id', 'answer', 'is_correct');
        }])->where(['quiz_id' => $quiz->id])->get();
        $quizMultipleChoiseQuestionsJson = $this->service->createQuizMultipleChoiceQuestionAnswersJson($quiz->id);

        JavaScript::put([
            'players'        => $players,
            'quizMultipleChoiseQuestionsJson' => $quizMultipleChoiseQuestionsJson,
        ]);
        
        return view('backend.quizzes.edit', compact('quizType','isTrue','quizStatus','players','quiz','quizEndTexts','quizMultipleChoiseQuestions','quizMultipleChoiseQuestionsJson','quizFillInTheBlanks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $clubId, Quizzes $quiz)
    {
        $quizToUpdate = $this->service->update(
            auth()->user(),
            $quiz,
            $request->all()
        );

        if ($quizToUpdate) {
            flash('Quizzes updated successfully')->success();
        } else {
            flash('Quizzes could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.quizzes.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $clubId, Quizzes $quiz)
    {
        if ($quiz->delete()) {
            flash('Quizzes deleted successfully')->success();
        } else {
            flash('Quizzes could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.quizzes.index', ['club' => app()->request->route('club')]);
    }

	/**
	 * Get Quiz list data.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  $clubId
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getQuizData(Request $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$quizList = $this->service->getData(
			$clubId,
			$request->all()
		);
		return $quizList;
	}



}
