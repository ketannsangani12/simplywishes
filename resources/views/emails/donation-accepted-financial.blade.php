<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SimplyWishes: You Accepted a Donation</title>
  </head>
  <body style="margin:0;padding:0;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ffffff;padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;border-collapse:collapse;">
            <tr>
              <td style="padding:8px 16px 24px 16px;font-size:16px;line-height:24px;">
                @php
                  $acceptorName = $acceptor->first_name ?: ($acceptor->name ?: 'there');
                  $donorName = trim(($donor->first_name ?? '') . ' ' . ($donor->last_name ?? ''));
                  $donorName = $donorName !== '' ? $donorName : ($donor->name ?? 'the donor');
                  $donationMethod = $donation->financial_assistance
                      ? \Illuminate\Support\Str::headline((string) $donation->financial_assistance)
                      : 'Not specified';
                  $donationAmount = $donation->expected_cost !== null
                      ? number_format((float) $donation->expected_cost, 0)
                      : 'Not specified';
                @endphp
                <p style="margin:0 0 16px 0;">Dear {{ $acceptorName }},</p>
                <p style="margin:0 0 16px 0;">
                  You have accepted the donation, name of donation
                  <a href="{{ $donationUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $donation->title ?: 'Untitled donation' }}]</a>!
                  The following information has been provided by
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">{{ $donorName }}</a>:
                </p>
                <p style="margin:0 0 8px 0;">Method of giving financial assistance: {{ $donationMethod }}</p>
                <p style="margin:0 0 16px 0;">Amount being donated (USD): {{ $donationAmount }}</p>
                <p style="margin:0 0 16px 0;">
                  The donor will have 14 days to fulfill this donation before it’s consider granted. You can mark this donation as completed any time before then.
                  For more information, please contact {{ $donorName }} directly by
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">sending a message</a>
                  to their SimplyWishes inbox.
                </p>
                <p style="margin:0 0 16px 0;">
                  Remember that any financial transaction arranged between you and the person who is giving the donation must happen outside of our website.
                </p>
                <p style="margin:0 0 16px 0;">
                  Continue to wish, dream, and connect with us at
                  <a href="{{ $loginUrl }}" style="color:#1d4ed8;text-decoration:underline;">SimplyWishes.com</a>
                </p>
                <p style="margin:24px 0 4px 0;">All the best,</p>
                <p style="margin:0;">SimplyWishes Team.</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
