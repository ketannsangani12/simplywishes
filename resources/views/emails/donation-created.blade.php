<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Thanks for Offering a Donation!</title>
  </head>
  <body style="margin:0;padding:0;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ffffff;padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;border-collapse:collapse;">
            <tr>
              <td style="padding:8px 16px 24px 16px;font-size:16px;line-height:24px;">
                <p style="margin:0 0 16px 0;">Hello {{ $user->first_name ?: ($user->name ?: 'there') }},</p>
                <p style="margin:0 0 16px 0;">
                  Congratulations! Your <strong>{{ $donation->title }}</strong> has been successfully created.
                </p>
                <p style="margin:0 0 16px 0;">
                  Please remember that any financial transaction arranged between you and the person who accepts your donation must happen outside our website.
                  You can log in to {{ $appUrl }} to update or delete your donation any time before it has been accepted by a wisher.
                </p>
                <p style="margin:0 0 16px 0;">
                  Once someone has accepted your donation, you will have 14 days to fulfill it before it's considered granted. If you fulfill this donation sooner than 14 days, the person who accepts your donation may mark it as fulfilled.
                </p>
                <p style="margin:0 0 16px 0;">
                  Continue to wish, dream, and connect with us at SimplyWishes.com.
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
