<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

  <head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <style>
      @media (max-width: 600px) {
        .sm-inline-block {
          display: inline-block !important;
        }

        .sm-w-full {
          width: 100% !important;
        }

        .sm-px-0 {
          padding-left: 0 !important;
          padding-right: 0 !important;
        }

        .sm-px-16 {
          padding-left: 16px !important;
          padding-right: 16px !important;
        }

        .sm-px-24 {
          padding-left: 24px !important;
          padding-right: 24px !important;
        }
      }
    </style>
  </head>

  <body style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased;">
    <div role="article" aria-roledescription="email" aria-label="" lang="en">
      <table style="width: 100%; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
          <td align="center" class="sm-px-16" style="background-color: #f9fafb;">
            <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td class="sm-px-24" style="padding-left: 48px; padding-right: 48px; text-align: left;">
                  <h3 style="padding-top: 12px; padding-bottom: 12px; text-align: center; font-size: 16px; font-weight: 700;">
                    <a href="{{ env('APP_URL') }}" style="color: #9ca3af; text-decoration: none;">ZOOподарки</a>
                  </h3>
                </td>
              </tr>
              <tr>
                <td class="sm-px-0" style="width: 100%; padding-left: 24px; padding-right: 24px; text-align: left;">
                  <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td class="sm-w-full sm-inline-block" style="width: 100%; padding-bottom: 10px;">
                        <table style="width: 100%; border-radius: 0.75rem; background-color: #ffffff; padding: 24px;" cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td colspan="3">
                              <h2 style="margin: 0; text-align: center; font-size: 24px; line-height: 24px;">
                                Ваш заказ доставлен
                              </h2>
                              <p style="padding-top: 10px; font-size: 16px; line-height: 24px;">
                                Уважаемый покупатель, Ваш заказ
                                <b>{{ $order->order_number }}</b> доставлен по
                                указанному Вами адресу.
                              </p>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3">
                              <p style="padding-top: 10px; text-align: center; font-size: 16px; line-height: 24px;">
                                Благодарим Вас за покупку!
                              </p>
                              <p style="padding-top: 32px; padding-bottom: 32px; text-align: center; font-size: 16px; line-height: 24px;">
                                <a href="{{ env('APP_URL') }}" style="font-size: 18px; font-weight: 700; color: #3b82f6;">Сделать новый заказ</a>
                              </p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="sm-px-24" style="border-radius: 4px; padding-left: 48px; padding-right: 48px; text-align: left; color: #6b7280;">
                  <table style="width: 100%; padding-top: 6px; padding-bottom: 32px;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr style="width: 100%;">
                      <td style="width: 41.666667%;">
                        <p style="margin: 0; padding-bottom: 4px; text-align: left; font-size: 14px; color: #6b7280;">
                          {{ config('constants.phone') }}
                        </p>
                        <p style="margin: 0; padding-bottom: 4px; text-align: left; font-size: 14px; color: #6b7280;">
                          {{ config('constants.phone2') }}
                        </p>
                        <p style="margin: 0; padding-bottom: 4px; text-align: left; font-size: 14px; color: #6b7280;">с 10.00 до 20.00</p>
                      </td>
                      <td style="width: 58.333333%;">
                        <a href="{{ route('site.contact') }}" style="margin: 0; cursor: pointer; text-align: center; font-size: 14px; color: #9ca3af;">
                          Адреса магазинов ZOOподарки
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>

</html>