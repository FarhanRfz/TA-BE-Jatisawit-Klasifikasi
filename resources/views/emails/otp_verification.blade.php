@component('mail::message')
# Kode Verifikasi Anda

Halo,  
Terima kasih sudah mendaftar di **Puskesmas Jatisawit**.  
Gunakan kode OTP berikut untuk memverifikasi akun Anda:

## **{{ $otp }}**

Kode ini berlaku selama 10 menit.  
Jangan bagikan kode ini kepada siapa pun.

Terima kasih,  
**Puskesmas Jatisawit**
@endcomponent
