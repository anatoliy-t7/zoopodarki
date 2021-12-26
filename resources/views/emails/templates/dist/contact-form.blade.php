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

        .sm-h-32 {
          height: 32px !important;
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
                            <td>
                              <h2 style="margin: 0; text-align: center; font-size: 18px; line-height: 24px;">
                                Вы получили новое сообщение от пользователя
                                интернет-магазина
                              </h2>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <p align="left" style="text-align: left; font-size: 16px; font-weight: 700;">
                                Сообщение:
                              </p>
                              <p align="left" style="text-align: left; font-size: 16px;">
                                {{ $data['content'] }}
                              </p>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <p style="text-align: left;">
                                <span style="text-align: left; font-size: 16px; font-weight: 700;">
                                Имя:
                              </span>
                                <span style="text-align: left; font-size: 16px;">
                                {{ $data['name'] }}
                                </span>
                              </p>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <p style="text-align: left;">
                                <span style="text-align: left; font-size: 16px; font-weight: 700;">
                                Email:
                              </span>
                                <span style="text-align: left; font-size: 16px;">
                                {{ $data['email'] }}
                                </span>
                              </p>
                            </td>
                          </tr>
                          @if($data['phone'])
                          <tr>
                            <td>
                              <p style="text-align: left;">
                                <span style="text-align: left; font-size: 16px; font-weight: 700;">
                                Телефон:
                              </span>
                                <span style="text-align: left; font-size: 16px;">
                                {{ $data['phone'] }}
                                </span>
                              </p>
                            </td>
                          </tr>
                          @endif
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="sm-h-32" style="height: 48px;"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>

</html>