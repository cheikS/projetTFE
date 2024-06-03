<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Facades\DB;


class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Tronquer la table
        DB::table('courses')->truncate();

        // Insérer des données dans la table artists
        // Vos instructions d'insertion de données ici

        // Réactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Course::create([
            'title' => 'Introduction to Programming',
            'description' => 'Learn the basics of programming with this introductory course.',
            'instructor_id' => 3,
            'duration' => 40,
            'level' => 'Beginner',
            'language' => 'English',
            'price' => 49.99,
            'category' => 'Computer Science',
            
        ]);

        Course::create([
            'title' => 'Advanced Laravel',
            'description' => 'Deep dive into advanced features of the Laravel framework.',
            'instructor_id' => 3,
            'duration' => 60,
            'level' => 'Advanced',
            'language' => 'English',
            'price' => 99.99,
            'category' => 'Computer Science',
        ]);

        Course::create([
            'title' => 'Web Development with JavaScript',
            'description' => 'Master web development with JavaScript and modern frameworks.',
            'instructor_id' => 3,
            'duration' => 50,
            'level' => 'Intermediate',
            'language' => 'French',
            'price' => 79.99,
            'category' => 'Computer Science',
        ]);

        Course::create([
            'title' => 'Data Science with Python',
            'description' => 'Learn data science techniques using Python.',
            'instructor_id' => 3,
            'duration' => 70,
            'level' => 'Intermediate',
            'language' => 'English',
            'price' => 89.99,
            'category' => 'Computer Science',
        ]);

        Course::create([
            'title' => 'Machine Learning Basics',
            'description' => 'An introductory course on machine learning concepts and applications.',
            'instructor_id' => 3,
            'duration' => 45,
            'level' => 'Beginner',
            'language' => 'French',
            'price' => 59.99,
            'category' => 'Computer Science',
        ]);
    }
}
