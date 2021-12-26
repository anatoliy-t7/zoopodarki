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
                            <td colspan="3">
                              <h2 style="margin: 0; text-align: center; font-size: 24px; line-height: 24px;">
                                Мы приняли Ваш заказ
                              </h2>
                              <p style="padding-top: 10px; font-size: 16px; line-height: 24px;">
                                Уважаемый покупатель, Ваш заказ в обработке
                              </p>
                              <p style="padding-top: 2px; padding-bottom: 2px; font-size: 16px; line-height: 24px;">
                                Заказ {{ $order->order_number }} от {{
                              simpleDate($order->created_at) }}
                              </p>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3">
                              <div style="margin-top: 3px; margin-bottom: 3px; text-align: left; font-size: 14px;">
                                Телефон:
                                <span style="font-weight: 700;">{{ $order->contact['phone'] }}</span>
                              </div>
                              <div style="margin-top: 3px; margin-bottom: 3px; text-align: left; font-size: 14px;">
                                Адрес доставки:
                                <span style="font-weight: 700;">{{ $order->address }}</span>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3">
                              <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                  <th align="left" style="width: 16.666667%;">
                                    <p>Товар</p>
                                  </th>
                                  <th align="left" style="width: 50%;"></th>
                                  <th align="left" style="width: 16.666667%;">
                                    <p>Кол-во</p>
                                  </th>
                                  <th align="right" style="width: 16.666667%; text-align: right;">
                                    <p>Цена</p>
                                  </th>
                                </tr>
                                @foreach ($order->items as $item)
                                <tr>
                                  <td style="border-bottom: 1px solid #e5e7eb; width: 16.666667%; padding-right: 10px; padding-bottom: 10px;">
                                    <img src="{{env('APP_URL') . $item->product1c->product->getFirstMediaUrl('product-images', 'thumb')}}" alt="{{ $item->name }}" style="border: 0; max-width: 100%; vertical-align: middle; line-height: 100%; height: 100%; width: 14px; object-fit: fill;">
                                  </td>
                                  <td style="border-bottom: 1px solid #e5e7eb; width: 50%; padding-bottom: 10px; font-size: 12px;">
                                    <div>{{$item->name}}</div>
                                  </td>
                                  <td align="left" style="border-bottom: 1px solid #e5e7eb; width: 16.666667%; padding-bottom: 10px; text-align: left; font-size: 16px;">
                                    {{$item->quantity}}
                                  </td>
                                  <td align="right" style="border-bottom: 1px solid #e5e7eb; width: 16.666667%; padding-bottom: 10px; text-align: right; font-size: 16px;">
                                    {{$item->amount - $item->discount}}
                                  </td>
                                </tr>
                                @endforeach
                                <tr>
                                  <td style="width: 16.666667%; padding-top: 10px;"></td>
                                  <td style="width: 50%; padding-top: 10px;"></td>
                                  <td style="width: 16.666667%; padding-top: 10px;">
                                    <p align="left" style="margin: 0; text-align: left; font-size: 16px; font-weight: 700; line-height: 24px;">
                                      Всего
                                    </p>
                                  </td>
                                  <td style="width: 16.666667%; padding-top: 10px;">
                                    <p align="right" style="margin: 0; text-align: right; font-size: 16px; font-weight: 700; line-height: 24px;">
                                      {{$order->amount}}
                                    </p>
                                  </td>
                                </tr>
                              </table>
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
                        <p style="margin: 0; padding-bottom: 4px; text-align: left; font-size: 14px; color: #6b7280;">8 (931) 239-98-83</p>
                        <p style="margin: 0; padding-bottom: 4px; text-align: left; font-size: 14px; color: #6b7280;">8 (812) 459-07-20</p>
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
              <tr>
                <td class="sm-px-24" style="border-radius: 4px; padding-left: 48px; padding-right: 48px; padding-top: 24px; text-align: left;">
                  <p style="margin: 0; text-align: center; font-size: 14px; color: #6b7280;">
                    Благодарим за покупку!
                  </p>
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