<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('subscriptions.index');
    }

    public function subscribe($planId)
    {
        if ($this->planNotAvailable($planId)) {
            return redirect()->route('plans');
        }
        return view('subscriptions.form', compact('planId'));
    }

    protected function planNotAvailable($id)
    {
        $available = [
            'prod_IkvfqOyON7X39V',
            'prod_IkvbNHMMCMYQTM',
            'prod_IkvbNHMMCMYQTM',
        ];
        if (!in_array($id, $available)) { // in_arrayで$availableの中から$idを探す
            return true;
        }
        return false;
    }

    public function process(Request $request)
    {
        $planId = $request->get('plan_id');
        if ($this->planNotAvailable($planId)) {
            return redirect()->back()->withErrors('Plan is required');
        }
        Stripe::setApikey(env('STRIPE_API_SECRET'));
        $user = Auth::user();
        $user->newSubsctription('main', $planId)->create(
            $request->stripe_token,
            [
                'email' => $user->email,
                'metadata' => [
                    'name' => $user->name,
                ]
            ]
        );
        return redirect('invoices');
    }

    public function invoices()
    {
        $user = Auth::user();
        return view('subscriptions.invoices', compact('user'));
    }

    public function downloadInvoices($id)
    {
        return Auth::user()->downloadInvoice(
            $id,
            [
                'header' => 'We Dew Lawns',
                'vendor' => 'WeDewLawans',
                'product' => Auth::user()->stripe_plan,
                'street' => '123 Lawn Drive',
                'location' => 'Lawndale NC, 28076',
                'phone' => '703.555.1212',
                'url' => 'www.wedewlawns.com',
            ]
        );
    }

    public function swapPlans(Request $request)
    {
        $planId = $request->get('plan_id');
        if ($this->planNotAvailable($planId)) {
            return redirect()->back()->withErrors('Plan is required');
        }
        Auth::user()->subscription('main')->swap($planId);
        return redirect()->back()->withMessage('Plan changed!');
    }

    public function cancelPlan(Request $request)
    {
        $planId = $request->get('plan_id');
        Auth::user()->subscription('main')->cancel($planId);
        return redirect('invoices')->with('message', 'Your plan has been cancelled');
    }
}
