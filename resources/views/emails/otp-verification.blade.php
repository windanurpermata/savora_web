<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi Savora</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@400;700&display=swap');
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F0E8; font-family: 'Lato', 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F5F0E8; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(59, 31, 13, 0.1);">
                    
                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="background-color: #3B1F0D; padding: 32px 24px; border-bottom: 3px solid #C8843A;">
                            <div style="font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 700; color: #F5F0E8; letter-spacing: 1px;">
                                Sav<span style="color: #C8843A;">ora</span>
                            </div>
                            <div style="font-family: 'Lato', sans-serif; font-size: 12px; color: #D4BFA0; text-transform: uppercase; letter-spacing: 2px; margin-top: 8px;">
                                Platform Resep Masakan Terlengkap
                            </div>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="background-color: #2A1508; padding: 40px 32px; color: #E8DDD0;">
                            
                            <h2 style="font-family: 'Playfair Display', Georgia, serif; font-size: 22px; color: #F5F0E8; margin-top: 0; margin-bottom: 20px; font-weight: 600; line-height: 1.3;">
                                Halo, {{ $name }}!
                            </h2>
                            
                            <p style="font-size: 14px; line-height: 1.6; margin-bottom: 30px; color: #E8DDD0;">
                                Terima kasih telah bergabung di <strong>Savora</strong>. Untuk menyelesaikan proses verifikasi akun Anda, silakan gunakan kode OTP di bawah ini:
                            </p>

                            <!-- OTP CODE BOX -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center" style="background-color: #3B1F0D; border: 2px dashed #C8843A; border-radius: 12px; padding: 24px;">
                                        <div style="font-size: 12px; color: #C8843A; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; font-family: 'Lato', sans-serif;">
                                            KODE OTP VERIFIKASI
                                        </div>
                                        <div style="font-size: 36px; font-weight: bold; color: #F5F0E8; letter-spacing: 8px; font-family: 'Courier New', Courier, monospace;">
                                            @php
                                                $formattedOtp = implode(' ', str_split($otp));
                                            @endphp
                                            {{ $formattedOtp }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- IMPORTANT NOTE -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="background-color: #3B1F0D; border-left: 4px solid #C8843A; padding: 16px; border-radius: 4px;">
                                        <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #B89070;">
                                            <strong>Penting:</strong> Kode OTP ini hanya berlaku selama <strong>15 menit</strong>. Untuk menjaga keamanan akun Anda, mohon untuk tidak menyebarkan kode ini kepada siapa pun.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; line-height: 1.5; margin: 0; color: #B89070; text-align: center;">
                                Jika Anda tidak merasa melakukan pendaftaran ini, silakan abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="background-color: #3B1F0D; padding: 20px 24px; color: #A0662A; font-size: 11px;">
                            &copy; {{ date('Y') }} Savora. Dibuat dengan &hearts; untuk seluruh keluarga Indonesia.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
