<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Video;
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

        return Inertia::render('Instructor/EditVideo', [
            'course' => $course,
            'video' => $video,
        ]);
    }

    // Méthode pour mettre à jour les données d'une vidéo
    public function update(Request $request, Course $course, Video $video)
    {
        // Vérifier que la vidéo appartient au cours
        if ($video->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        // Valider les données de la requête
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
        ]);

        // Mettre à jour la vidéo avec les nouvelles données
        $video->update($request->only('title', 'description', 'url'));

        return redirect()->route('instructor.courses.manage-videos', $course->id)->with('message', 'Video updated successfully.');
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
}
