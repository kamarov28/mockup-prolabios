<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndonesianTranslationTest extends TestCase
{
    public function test_indonesian_validation_messages_are_translated(): void
    {
        app()->setLocale('id');

        $validator = Validator::make([
            'email_field' => 'not-an-email',
            'regex_field' => 'abc',
        ], [
            'required_field' => 'required',
            'email_field' => 'email',
            'regex_field' => 'regex:/^[0-9]+$/',
        ]);

        $this->assertTrue($validator->fails());

        $errors = $validator->errors();
        $this->assertStringNotContainsString('validation.required', $errors->first('required_field'));
        $this->assertStringNotContainsString('validation.email', $errors->first('email_field'));
        $this->assertStringNotContainsString('validation.regex', $errors->first('regex_field'));

        $this->assertStringContainsString('wajib diisi', $errors->first('required_field'));
        $this->assertStringContainsString('Format regex field tidak valid', $errors->first('regex_field'));
    }
}
