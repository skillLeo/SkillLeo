{{-- resources/views/emails/client-invitation.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Invitation</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f4f5f7;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f5f7; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0052CC 0%, #0747A6 100%); padding: 40px 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600;">
                                🎉 You've Been Invited!
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; font-size: 16px; color: #172B4D; line-height: 1.6;">
                                Hi <strong>{{ $clientName }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 20px; font-size: 16px; color: #172B4D; line-height: 1.6;">
                                <strong>{{ $inviterName }}</strong> has invited you to collaborate as a client on {{ config('app.name') }}.
                            </p>
                            
                            @if($invitation->message)
                            <div style="background-color: #F4F5F7; border-left: 4px solid #0052CC; padding: 16px 20px; margin: 24px 0; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #5E6C84; font-style: italic; line-height: 1.6;">
                                    "{{ $invitation->message }}"
                                </p>
                            </div>
                            @endif
                            
                            <p style="margin: 24px 0 20px; font-size: 16px; color: #172B4D; line-height: 1.6;">
                                As a client, you'll be able to:
                            </p>
                            
                            <ul style="margin: 0 0 24px; padding-left: 20px; color: #172B4D; line-height: 1.8;">
                                <li style="margin-bottom: 8px;">📊 Track project progress in real-time</li>
                                <li style="margin-bottom: 8px;">✅ Review and approve deliverables</li>
                                <li style="margin-bottom: 8px;">💬 Communicate directly with your team</li>
                                <li style="margin-bottom: 8px;">📁 Access all project files and documents</li>
                                <li style="margin-bottom: 8px;">💰 View invoices and payment status</li>
                            </ul>
                            
                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $acceptUrl }}" 
                                           style="display: inline-block; padding: 16px 48px; background-color: #0052CC; color: #ffffff; text-decoration: none; border-radius: 4px; font-size: 16px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,82,204,0.2);">
                                            Accept Invitation & Create Account
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 24px 0 0; font-size: 14px; color: #5E6C84; line-height: 1.6;">
                                This invitation will expire in <strong>7 days</strong> ({{ $expiresAt }}).
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #F4F5F7; border-top: 1px solid #DFE1E6;">
                            <p style="margin: 0 0 8px; font-size: 12px; color: #5E6C84; text-align: center;">
                                If you have any questions, please contact {{ $inviterName }} at 
                                <a href="mailto:{{ $inviterEmail }}" style="color: #0052CC; text-decoration: none;">{{ $inviterEmail }}</a>
                            </p>
                            
                            <p style="margin: 12px 0 0; font-size: 11px; color: #8993A4; text-align: center;">
                                If you didn't expect this invitation, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
                <!-- Footer Note -->
                <p style="margin: 24px 0 0; font-size: 12px; color: #8993A4; text-align: center;">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>