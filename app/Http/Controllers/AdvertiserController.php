<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Funding;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index(){
        return view('advertisers.index')->with(['campaigns'=> Campaign::all()]);
    }

    public function showCreateCampaign(Request $request){
        return view('advertisers.campaigns.create');
    }

    public function createCampaign(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'device' => 'required|string|max:255',
            'ad_title' => 'required|string|max:255',
            'ad_description' => 'required|string|max:255',
            'ad_format' => 'required|string|max:50',
            'ad_type' => 'required|string|max:50',
            'target_url' => 'required|string|max:255',
            'ad_width' => 'required|int',
            'ad_height' => 'required|int',
            'ad_category' => 'required|string|max:50',
            'geo_targeting' => 'required|string|max:50',
            'income_targeting' => 'required|string|max:50',
            'isp_targeting' => 'nullable|string|max:50',
            'ip_targeting' => 'nullable|string|max:50',
            'wifi_cellular_targeting' => 'required|string|max:50',
            'os_targeting' => 'required|string|max:50',
            'browser_targeting' => 'required|string|max:50',
            'browser_language_targeting' => 'string|max:50',
            'keyword_targeting' => 'nullable|string|max:255',
            'max_bid' => 'required|decimal:1,2|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'frequency_capping' => 'required|int|max:100',
            'daily_budget' => 'required|int|max:100000'
        ]);

        $keywordArray = null;
        if ($request->filled('keyword_targeting')) {
            $keywordArray = array_map('trim', explode(',', $request->keyword_targeting));
        }

        Campaign::create([
            'name' => $request->get('name'),
            'device' => $request->get('device'),
            'ad_title' => $request->get('ad_title'),
            'ad_description' => $request->get('ad_description'),
            'ad_format' => $request->get('ad_format'),
            'ad_type' => $request->get('ad_type'),
            'target_url' => $request->get('target_url'),
            'ad_width' => $request->get('ad_width'),
            'ad_height' => $request->get('ad_height'),
            'ad_category' => $request->get('ad_category'),
            'geo_targeting' => $request->get('geo_targeting'),
            'income_targeting' => $request->get('income_targeting'),
            'isp_targeting' => $request->get('isp_targeting'),
            'ip_targeting' => $request->get('ip_targeting'),
            'wifi_cellular_targeting' => $request->get('wifi_cellular_targeting'),
            'os_targeting' => $request->get('os_targeting'),
            'browser_targeting' => $request->get('browser_targeting'),
            'browser_language_targeting' => $request->get('browser_language_targeting'),
            'keyword_targeting' => $keywordArray,
            'max_bid' => $request->get('max_bid'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'frequency_capping' => $request->get('frequency_capping'),
            'daily_budget' => $request->get('daily_budget'),
            'user_id' => auth()->user()->id,
        ]);

        return redirect('/dashboard')->with('success', 'Campaign created successfully');
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:100000',
        ]);

        $user = auth()->user();
        $amount = $request->amount * 100; // Convert to cents for Stripe

        try {
            // Create payment intent
            $paymentIntent = $user->createSetupIntent();

            // Record the funding in database
            $funding = Funding::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_method' => 'stripe',
                'stripe_payment_intent' => $paymentIntent->id
            ]);

            return view('advertisers.payments.checkout', [
                'intent' => $paymentIntent,
                'amount' => $request->amount,
                'funding_id' => $funding->id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'funding_id' => 'required|exists:fundings,id',
            'amount' => 'required|numeric|min:10|max:100000',
        ]);

        $user = auth()->user();
        $amount = $request->amount;
        $funding = Funding::findOrFail($request->funding_id);

        try {
            // Charge the payment method
            $payment = $user->charge($amount * 100, $request->payment_method, [
                'description' => 'Account funding #' . $funding->id,
                'currency' => 'eur',
            ]);

            // Update funding status
            $funding->update([
                'status' => 'accepted',
                'stripe_payment_intent' => $payment->id
            ]);

            // Update user's balance
            $user->increment('balance', $amount);

            return redirect()->route('advertiser.payments')->with('success', 'Payment successful! Your account has been funded with €' . $amount);
        } catch (\Exception $e) {
            $funding->update(['status' => 'declined']);
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}
