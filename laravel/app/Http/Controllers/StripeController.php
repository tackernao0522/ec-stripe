<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function handleInvoicePaymentFailed($payload)
    {
        $billable = $this->getBillable(
            $payload['data']['object']['customer']
        );

        if ($billable) {
            \Mail::send(
                'emails.failed_charge',
                compact('billable'),
                function ($message) {
                    $message->to(env('MAIL_FROM'), env('MAIL_NAME'));
                }
            );
        }

        return new Response('Webhook Handled', 200);
    }
}
