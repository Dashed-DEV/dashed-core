<?php

namespace Qubiqx\Qcommerce\Mail;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Qubiqx\QcommerceCore\Models\Form;
use Illuminate\Queue\SerializesModels;
use Qubiqx\QcommerceCore\Models\FormInput;
use Qubiqx\QcommerceCore\Models\Translation;
use Qubiqx\QcommerceCore\Models\Customsetting;

class FormSubmitConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Form $form, FormInput $formInput)
    {
        $this->form = $form;
        $this->formInput = $formInput;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('qcommerce-core::emails.confirm-form-submit')
            ->from(Customsetting::get('site_from_email'), Customsetting::get('company_name'))->subject(Translation::get('form-confirmation-'.Str::slug($this->form->name).'-email-subject', 'forms', 'We received your form submit!'))
            ->with([
                'form' => $this->form,
                'formInput' => $this->formInput,
            ]);
    }
}