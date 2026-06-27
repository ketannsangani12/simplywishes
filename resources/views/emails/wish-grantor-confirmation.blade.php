<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SimplyWishes: You Accepted a Wish</title>
  </head>
  <body style="margin:0;padding:0;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ffffff;padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;border-collapse:collapse;">
            <tr>
              <td style="padding:8px 16px 24px 16px;font-size:16px;line-height:24px;">
                @php
                  $grantorName = $grantor->first_name ?: ($grantor->name ?: 'there');
                  $creatorName = trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? ''));
                  $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'the wish creator');
                  $isFinancial = (int) $wish->non_pay_option !== 1;
                  $deliveryType = match ($wish->way_of_wish) {
                      'online_order' => 'Online Order',
                      'drop_off_pickup' => 'Drop Off / Pickup',
                      'mail' => 'Mail',
                      null, '' => 'Not specified',
                      default => \Illuminate\Support\Str::headline((string) $wish->way_of_wish),
                  };
                  $receiveWishInfo = $deliveryType;
                  if (!empty($wish->description_of_way)) {
                      $receiveWishInfo .= ': ' . $wish->description_of_way;
                  }
                  $expectedDate = $wish->expected_date
                      ? \Illuminate\Support\Carbon::parse($wish->expected_date)->format('F d, Y')
                      : 'Not specified';
                @endphp
                <p style="margin:0 0 16px 0;">Dear {{ $grantorName }},</p>
                <p style="margin:0 0 16px 0;">
                  Thank you for accepting to grant the wish, name of wish
                  <a href="{{ $wishUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $wish->wish_title ?: 'Untitled wish' }}]</a>!
                  The following information has been provided by
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">[{{ $creatorName }}]</a>:
                </p>
                <p style="margin:0 0 8px 0;">Date they would like their wish to be granted by: {{ $expectedDate }}</p>
                @if($isFinancial)
                  <p style="margin:0 0 8px 0;">Method of receiving financial assistance: {{ $wish->financial_assistance ? \Illuminate\Support\Str::headline((string) $wish->financial_assistance) : 'Not specified' }}</p>
                  <p style="margin:0 0 8px 0;">Email or username associated with account: {{ $wish->show_mail ?: 'Not specified' }}</p>
                  <p style="margin:0 0 16px 0;">Amount requested (USD): {{ $wish->expected_cost !== null ? number_format((float) $wish->expected_cost, 0) : 'Not specified' }}</p>
                @else
                  <p style="margin:0 0 16px 0;">How they would you like to receive this Wish: {{ $receiveWishInfo }}</p>
                @endif
                <p style="margin:0 0 16px 0;">
                  You will have 14 days to fulfill this wish before it’s consider granted. If you fulfill this wish sooner than 14 days,
                  {{ $creatorName }} may mark it as fulfilled. For more information, please contact {{ $creatorName }} directly by
                  <a href="{{ $inboxUrl }}" style="color:#1d4ed8;text-decoration:underline;">sending a message</a>
                  to their SimplyWishes inbox.
                </p>
                <p style="margin:0 0 16px 0;">
                  Remember that any financial transaction arranged between you and the person who is granting your wish must happen outside of our website.
                </p>
                <p style="margin:0 0 16px 0;">
                  Continue to wish, dream, and connect with us at
                  <a href="{{ $loginUrl }}" style="color:#1d4ed8;text-decoration:underline;">SimplyWishes.com</a>
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
