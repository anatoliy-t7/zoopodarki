<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

  <head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
      <xml><o:OfficeDocumentSettings
          ><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings
        ></xml>
      <style>
        td,
        th,
        div,
        p,
        a,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
          font-family: "Segoe UI", sans-serif;
          mso-line-height-rule: exactly;
        }
      </style>
    <![endif]-->
    <title>Изменения вашего отзыва ZooPodarki</title>
    <style>
      @media (max-width: 600px) {
        .sm-inline-block {
          display: inline-block !important;
        }

        .sm-h-32 {
          height: 32px !important;
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

        .sm-w-full {
          width: 100% !important;
        }
      }
    </style>
  </head>

  <body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #f9fafb;">
    <div style="display: none;">
      Изменения вашего отзыва ZooPodarki&#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &zwnj;
      &#160;&#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
      &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &zwnj;
      &#160;&#847; &#847; &#847; &#847; &#847;
    </div>
    <div role="article" aria-roledescription="email" aria-label="Изменения вашего отзыва ZooPodarki" lang="en">
      <table style="font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif; width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
          <td align="center" class="sm-px-16" style="background-color: #f9fafb;">
            <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td class="sm-px-24" style="padding-left: 48px; padding-right: 48px; text-align: left;">
                  <h3 style="font-weight: 700; font-size: 16px; padding-top: 12px; padding-bottom: 12px; text-align: center;">
                    <a href="{{ env('APP_URL') }}" style="color: #9ca3af; text-decoration: none;">ZooPodarki</a>
                  </h3>
                </td>
              </tr>
              <tr>
                <td class="sm-px-0" style="padding-left: 24px; padding-right: 24px; text-align: left; width: 100%;">
                  <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td class="sm-w-full sm-inline-block" style="padding-bottom: 10px; width: 100%;">
                        <table style="background-color: #ffffff; border-radius: 0.75rem; padding: 24px; width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td colspan="3">
                              <h2 style="font-size: 24px; line-height: 24px; margin: 0; text-align: center;">
                                Ваш отзыв был опубликован
                              </h2>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3">
                              <div style="font-size: 14px; margin-top: 3px; margin-bottom: 3px; padding-top: 24px; text-align: left;">
                                <p>
                                  Ваш отзыв на товар
                                  <a href="{{$productLink}}" target="_blank">{{ $productName }}</a>
                                  был опубликован.
                                </p>
                                <b>"{!!$review->body!!}"</b>
                              </div>
                            </td>
                          </tr>
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