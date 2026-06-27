<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SimplyWishes: Your Wish Has Been Accepted</title>
  </head>
  <body style="margin:0;padding:0;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ffffff;padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;border-collapse:collapse;">
            <tr>
              <td style="padding:8px 16px 24px 16px;font-size:16px;line-height:24px;">
                @php
                  $creatorName = $creator->first_name ?: ($creator->name ?: 'there');
                  $grantorName = trim(($grantor->first_name ?? '') . ' ' . ($grantor->last_name ?? ''));
                  $grantorName = $grantorName !== '' ? $grantorName : ($grantor->name ?? 'Another user');
                @endphp
                <p style="margin:0 0 16px 0;">Dear {{ $creatorName }},</p>
                <p style="margin:0 0 16px 0;">
                  Congratulations, your wish,
                  <a href="{{ $wishUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $wish->wish_title ?: 'Untitled wish' }}]</a>,
                  has been accepted by
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $grantorName }}]</a>!
                  They have 14 days to fulfill this wish before it’s consider granted. If your wish is granted sooner than 14 days, you may mark it as
                  <a href="{{ $wishUrl }}" style="color:#1d4ed8;text-decoration:underline;">fulfilled</a>,
                  however, this wish may no longer be updated or deleted. And, for whatever reason, if your wish is not fulfilled, you can post it again
                  <a href="{{ $postWishUrl }}" style="color:#1d4ed8;text-decoration:underline;">Post Wish</a>!
                </p>
                <p style="margin:0 0 16px 0;">
                  Contact
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $grantorName }}]</a>
                  directly for more details by sending a message
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $grantorName }}]</a>
                  to their SimplyWishes inbox.
                </p>
                <p style="margin:0 0 16px 0;">
                  Finally, remember that any financial transaction arranged between you and the person who is granting your wish must happen outside of our website.
                </p>
                <p style="margin:0 0 16px 0;">
                  Continue to wish, dream, and connect with us at
                  <a href="{{ $loginUrl }}" style="color:#1d4ed8;text-decoration:underline;">SimplyWishes.com</a>.
                </p>
                <p style="margin:24px 0 4px 0;">All the best,</p>
                <p style="margin:0;">SimplyWishes Team</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
