<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Video;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function show($id)
    {
        $quiz = Quiz::with('questions.choices')->findOrFail($id);
        return Inertia::render('Quiz', ['quiz' => $quiz]);
    }

    public function submit(Request $request, $id)
{
    $quiz = Quiz::with('questions.choices')->findOrFail($id);
    $correctAnswers = [];
    $userResults = [];
    
    foreach ($quiz->questions as $question) {
        $userChoiceId = (int) $request->input("question_{$question->id}");
        $correctChoice = $question->choices()->where('is_correct', true)->first();
        
        // Sauvegarder la réponse correcte et la réponse de l'utilisateur
        $correctAnswers[$question->id] = $correctChoice->choice_text;

        $isCorrect = $userChoiceId === $correctChoice->id;
        $userResults[$question->id] = [
            'user_choice' => $userChoiceId,
            'is_correct' => $isCorrect,
        ];
    }

    return Inertia::render('Quiz', [
        'quiz' => $quiz,
        'correctAnswers' => $correctAnswers,
        'userResults' => $userResults,
    ]);
}

    
    
    

    public function update(Request $request, $courseId, $videoId)
    {
        $video = Video::findOrFail($videoId);
        $quiz = $video->quiz ?: new Quiz(['video_id' => $video->id]);
        $quiz->save();

        $quiz->questions()->delete();

        foreach ($request->input('questions', []) as $questionData) {
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text']
            ]);

            foreach ($questionData['choices'] as $index => $choiceData) {
                $question->choices()->create([
                    'choice_text' => $choiceData['choice_text'],
                    'is_correct' => $index == $questionData['correct_choice']
                ]);
            }
        }

        return redirect()->back()->with('success', 'Quiz updated successfully!');
    }
}
