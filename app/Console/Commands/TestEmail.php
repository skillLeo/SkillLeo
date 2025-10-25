<?php
// app/Console/Commands/TestEmail.php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Models\ClientInvitation;
use App\Mail\ClientInvitationMail;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test email sending';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing email to: ' . $email);
        
        try {
            // Create a dummy invitation
            $invitation = ClientInvitation::create([
                'inviter_id' => 1,
                'email' => $email,
                'name' => 'Test Client',
                'message' => 'This is a test invitation',
                'token' => Str::random(64),
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);
            
            Mail::to($email)->send(new ClientInvitationMail($invitation));
            
            $this->info('✅ Email sent successfully!');
            
            // Clean up
            $invitation->delete();
            
        } catch (\Throwable $e) {
            $this->error('❌ Email failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}