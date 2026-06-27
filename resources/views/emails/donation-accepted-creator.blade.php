<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SimplyWishes: Your Donation Has Been Accepted</title>
  </head>
  <body style="margin:0;padding:0;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ffffff;padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;border-collapse:collapse;">
            <tr>
              <td style="padding:8px 16px 24px 16px;font-size:16px;line-height:24px;">
                @php
                  $donorName = $donor->first_name ?: ($donor->name ?: 'there');
                  $acceptorName = trim(($acceptor->first_name ?? '') . ' ' . ($acceptor->last_name ?? ''));
                  $acceptorName = $acceptorName !== '' ? $acceptorName : ($acceptor->name ?? 'another user');
                @endphp
                <p style="margin:0 0 16px 0;">Dear {{ $donorName }},</p>
                <p style="margin:0 0 16px 0;">
                  Congratulations, your donation,
                  <a href="{{ $donationUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $donation->title ?: 'Untitled donation' }}]</a>,
                  has been accepted by {{ $acceptorName }}!
                </p>
                <p style="margin:0 0 16px 0;">
                  You have 14 days to fulfill this donation before it’s consider granted. If your donation is granted sooner than 14 days, you may mark it as
                  <a href="{{ $completeUrl }}" style="color:#1d4ed8;text-decoration:underline;">completed</a>,
                  however, this donation may no longer be updated or deleted. And, for whatever reason, if you cannot fulfill this donation, you can
                  <a href="{{ $createDonationUrl }}" style="color:#1d4ed8;text-decoration:underline;">create a new donation</a>!
                </p>
                <p style="margin:0 0 16px 0;">
                  Contact {{ $acceptorName }} directly for more details by
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">sending a message</a>
                  to their SimplyWishes inbox.
                </p>
                <p style="margin:0 0 16px 0;">
                  Finally, remember that any financial transaction arranged between you and the person who is granting your wish must happen outside of our website.
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
