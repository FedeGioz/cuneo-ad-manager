<?php

namespace App\Http\Controllers;

use App\Models\Funding;
use Exception;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function index(){
        $payments = Funding::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('advertisers.payments.list')->with(['fundings'=> $payments]);
    }

    public function checkout(Request $request){
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => auth()->user()->email,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Balance Top-up',
                        ],
                        'unit_amount' => $request->input('amount')*100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('advertisers.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('advertisers.payment.failed'),
            ]);

            $funding = new Funding();
            $funding->user()->associate($request->user());
            $funding->amount = $request->get('amount');
            $funding->session_id = $session->id;
            $funding->save();


            return redirect($session->url, 303);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to create payment session: ' . $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');
        $checkoutSession = Session::retrieve($sessionId);
        if ($checkoutSession->payment_status == 'paid') {
            $order = Funding::where('session_id', $sessionId)->first();
            if($order->status == 'unpaid'){
                $order->update(['status' => 'paid']);
                auth()->user()->balance += $order->amount;
                auth()->user()->save();
                return redirect('/dashboard');
            }
        }
    }

    public function failed(Request $request){
        return redirect('/advertisers/topup');
    }
}

