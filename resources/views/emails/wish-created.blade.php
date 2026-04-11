<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Wish Has Been Created Successfully</title>
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
                  Congratulations! Your wish <strong>{{ $wish->wish_title }}</strong>, has been successfully created.
                </p>
                <p style="margin:0 0 16px 0;">
                  Please remember that any financial transaction arranged between you and the person who is granting your wish must happen outside of our website.
                  You can log in to {{ $appUrl }} to update or delete your wish any time before it has been accepted by a grantor.
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
