<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (PostTooLargeException $e, $request) {
            $message = 'Your uploaded files are too large for the server to accept in one submission. '
                . 'Please keep each document at or below 2MB and reduce the overall upload size, then try again.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            return redirect()
                ->back()
                ->withInput($request->except([
                    'proof_of_income',
                    'proof_of_government_assistance',
                    'nric_front',
                    'nric_back',
                    'academic_result',
                    'latest_academic_transcript',
                    'university_offer_letter',
                    'student_id_confirmation',
                    'applicant_photo',
                    'university_fee_statement',
                    'official_invoice',
                    'outstanding_balance_statement',
                    'payment_deadline_notice',
                    'additional_supporting_documents',
                    'supporting_files',
                    'academic_transcript',
                ]))
                ->with('error', $message);
        });
    }
}
