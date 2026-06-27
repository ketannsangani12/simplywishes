<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SimplyWishes: The Wish You Granted Is Now Granted</title>
  </head>
  <body style="margin:0;padding:0;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ffffff;padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;border-collapse:collapse;">
            <tr>
              <td style="padding:8px 16px 24px 16px;font-size:16px;line-height:24px;">
                <p style="margin:0 0 16px 0;">Dear {{ $grantor->first_name ?: ($grantor->name ?: 'there') }},</p>
                <p style="margin:0 0 16px 0;">
                  Thank you for granting the title of wish
                  <a href="{{ $wishUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $wish->wish_title ?: 'Untitled wish' }}]</a>!
                  It is now considered granted.
                </p>
                <p style="margin:0 0 16px 0;">
                  You can check out our
                  <a href="{{ $happyStoriesUrl }}" style="color:#1d4ed8;text-decoration:underline;">Happy Stories</a>
                  and
                  <a href="{{ $forumUrl }}" style="color:#1d4ed8;text-decoration:underline;">Forum</a>
                  pages to see what others in our community have shared.
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
