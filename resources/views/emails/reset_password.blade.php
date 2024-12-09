<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Your Password</h1>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    
    <p>Please click the button below to reset your password:</p>
    
    <a href="{{ $resetLink }}#reset-password" style="background-color: #4CAF50; color: white; padding: 14px 20px; text-decoration: none; border-radius: 4px;">
        Reset Password
    </a>
    
    <p>If you did not request a password reset, no further action is required.</p>
    
    <p>This password reset link will expire in 60 minutes.</p>
    
    <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
    <p>{{ $resetLink }}#reset-password</p>
</body>
</html>