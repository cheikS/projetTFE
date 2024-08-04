<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Transaction;
use App\Models\Course;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function createCheckoutSession(Request $request, Course $course)
{
    Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $course->title,
                ],
                'unit_amount' => $course->price * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('payment.success', ['course' => $course->id]) . '?session_id={CHECKOUT_SESSION_ID}', // Notez l'ajout de ?session_id={CHECKOUT_SESSION_ID}
        'cancel_url' => route('payment.cancel', ['course' => $course->id]),
    ]);

    Transaction::create([
        'user_id' => Auth::id(),
        'course_id' => $course->id,
        'stripe_session_id' => $session->id,
        'status' => 'pending',
        'amount' => $course->price,
    ]);

    return response()->json(['id' => $session->id]);
}


    public function success(Request $request, Course $course)
    {
        // Récupérer l'ID de session Stripe depuis l'URL de retour
        $session_id = $request->input('session_id');
    
        // Récupérer la transaction correspondante
        $transaction = Transaction::where('stripe_session_id', $session_id)->first();
    
        if ($transaction) {
            // Mettre à jour le statut de la transaction après un paiement réussi
            $transaction->status = 'completed';
            $transaction->save();
    
            // Vérifier si l'utilisateur est déjà inscrit à ce cours
            $existingRegistration = Registration::where('user_id', $transaction->user_id)
                                                ->where('course_id', $course->id)
                                                ->first();
    
            if (!$existingRegistration) {
                // Enregistrer une nouvelle inscription pour l'utilisateur
                Registration::create([
                    'user_id' => $transaction->user_id,
                    'course_id' => $course->id,
                ]);
            }
    
            // Rediriger l'utilisateur vers le tableau de bord avec un message de succès
            return redirect()->route('dashboard')
                ->with('success', 'Payment successful. You are now registered for the course.');
        } else {
            return redirect()->route('courses.show', ['course' => $course->id])
                ->with('error', 'Transaction not found. Please contact support.');
        }
    }
    

    public function cancel(Request $request, Course $course)
    {
        // Rediriger l'utilisateur en cas d'annulation
        return redirect()->route('courses.show', ['course' => $course->id])
            ->with('error', 'Payment was cancelled.');
    }

    
}
