Welcome to Kinderbot!

Dear {{ $name }},

Your account has been created successfully. Below are your login credentials:

Email: {{ $email }}
Password: {{ $password }}
Role: {{ ucfirst($role) }}

⚠️ Important: Please change your password after your first login.

Login URL: {{ url('/login') }}

Login Instructions:
1. Go to {{ url('/login') }}
2. Enter your email: {{ $email }}
3. Enter the password provided above
4. You will be redirected to your dashboard

---
This is an automated message. Please do not reply to this email.
&copy; {{ date('Y') }} Kinderbot. All rights reserved.
