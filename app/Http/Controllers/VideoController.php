<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Video;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    

    // Méthode pour afficher le formulaire d'édition d'une vidéo
    public function edit(Course $course, Video $video)
    {
        // Vérifier que la vidéo appartient au cours
        if ($video->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }
    
        $video->load('quiz.questions.choices'); // Charger les questions et choix associés au quiz
    
        return Inertia::render('Instructor/EditVideo', [
            'course' => $course,
            'video' => $video,
        ]);
    }
    
    // Méthode pour mettre à jour les données d'une vidéo
    public function update(Request $request, $courseId, $videoId)
    {
        $video = Video::findOrFail($videoId);

        // Mise à jour des informations de la vidéo
        $video->update($request->only('title', 'description', 'url'));

        // Traiter les données du quiz
        if ($request->has('questions')) {
            // Supprimer l'ancien quiz (si existant)
            if ($video->quiz) {
                $video->quiz->questions()->delete();
                $video->quiz->delete();
            }

            // Créer un nouveau quiz
            // Créer un nouveau quiz avec un titre
                    $quiz = Quiz::create([
                        'video_id' => $video->id,
                        'title' => $request->input('quiz_title', 'Quiz for ' . $video->title), // Utilise le titre du quiz fourni ou un titre par défaut basé sur le titre de la vidéo
                    ]);


            foreach ($request->input('questions') as $questionData) {
                $question = $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                ]);

                foreach ($questionData['choices'] as $index => $choiceData) {
                    $question->choices()->create([
                        'choice_text' => $choiceData['choice_text'],
                        'is_correct' => $index == $questionData['correct_choice'],
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Video and quiz updated successfully!');
    }
    

   
    public function destroy(Course $course, Video $video)
    {
        // Vérifier que la vidéo appartient au cours
        if ($video->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $video->delete();

        return redirect()->route('manage-videos', $course->id)->with('message', 'Video deleted successfully.');
    }

    public function show($videoId)
    {
        $video = Video::with('quiz.questions.choices')->findOrFail($videoId);
    
        return Inertia::render('VideoViewer', [
            'video' => $video,
        ]);
    }
    

    
}
