{{-- resources/views/emails/layout.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('email-title', 'Urbexium')</title>
</head>

<body style="margin:0; padding:0; background-color:#0f1117; font-family:Arial, Helvetica, sans-serif; color:#e2e8f0;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f1117; margin:0; padding:0;">
    <tr>
        <td align="center" style="padding:32px 12px;">

            <table width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px; background-color:#1a1d27; border:1px solid #2d3148; border-radius:12px; overflow:hidden;">

                {{-- Header --}}
                <tr>
                    <td align="center" style="background-color:#101820; padding:34px 24px; border-bottom:1px solid #2d3148;">

                        <table cellpadding="0" cellspacing="0" border="0" align="center">
                            <tr>
                                <td align="center" valign="middle" width="48" height="48" style="width:48px; height:48px; background-color:#4ade80; border-radius:10px; color:#0f1117; font-size:24px; font-weight:bold; line-height:48px; text-align:center;">
                                    U
                                </td>
                            </tr>
                        </table>

                        <div style="margin-top:14px; font-size:24px; line-height:28px; font-weight:bold; color:#ffffff;">
                            Urbex<span style="color:#4ade80;">ium</span>
                        </div>

                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:32px 32px 28px 32px;">
                        @yield('email-body')
                    </td>
                </tr>

            </table>

            {{-- Footer --}}
            <table width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px;">
                <tr>
                    <td align="center" style="padding:22px 12px 0 12px; font-size:12px; line-height:20px; color:#6b7280;">
                        <p style="margin:0 0 8px 0; color:#6b7280;">
                            © {{ date('Y') }} Urbexium — Explora lo inexplorado
                        </p>

                        <p style="margin:0; color:#6b7280;">
                            <a href="{{ config('app.url') }}/legal/aviso-legal" style="color:#4ade80; text-decoration:none;">Aviso Legal</a>
                            &nbsp;·&nbsp;
                            <a href="{{ config('app.url') }}/legal/privacidad" style="color:#4ade80; text-decoration:none;">Privacidad</a>
                            &nbsp;·&nbsp;
                            <a href="{{ config('app.url') }}/legal/cookies" style="color:#4ade80; text-decoration:none;">Cookies</a>
                            &nbsp;·&nbsp;
                            <a href="{{ config('app.url') }}/contacto" style="color:#4ade80; text-decoration:none;">Contacto</a>
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>
