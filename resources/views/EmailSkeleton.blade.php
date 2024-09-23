<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="utf-8">
    <title><?php echo $subject; ?></title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0;">

    <div
        style="background-color: #ffffff; max-width: 800px; margin: 0 auto; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-bottom: 20px; margin-top: 20px;">
        <img src="{{ $message->embed('public/logo.png') }}" alt="Votre Logo" style="display: block; margin: 0 auto 20px;">


        <?php echo $content; ?>
    </div>

</body>

</html>
