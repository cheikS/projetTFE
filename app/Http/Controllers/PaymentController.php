<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session; // Import correct de la classe Session de Stripe
use App\Models\Course;
use Inertia\Inertia;  // Ajout de l'importation correcte pour Inertia



class PaymentController extends Controller
{
    public function pay(Request $request, $courseId)
    {
        // Définir la clé secrète Stripe
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    
        $course = Course::findOrFail($courseId);
    
        // Création de la session de paiement
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $course->title,
                    ],
                    'unit_amount' => $course->price * 100,  // Le prix doit être en cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('courses.success', $course->id),
            'cancel_url' => route('courses.cancel', $course->id),
        ]);
    
        // Retourner la sessionId à la vue via Inertia
        return Inertia::location($checkoutSession->url);
    }
    public function cancel($courseId)
    {
        // Récupérer les informations du cours
        $course = Course::findOrFail($courseId);
    
        // Rediriger vers la page de détails du cours en utilisant la route nommée
        return redirect()->route('courses.show', ['course' => $course->id]);
    }
    


}
