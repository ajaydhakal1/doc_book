<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- Container -->
                <table width="600" cellspacing="0" cellpadding="0" border="0"
                    style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4f46e5; padding: 20px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">Appointment Confirmed</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 20px; color: #333333;">
                            <p style="font-size: 16px; margin: 0;">
                                Dear {{ $appointment->patient->user->name }},
                            </p>
                            <p style="font-size: 16px; margin: 16px 0;">
                                Your appointment has been successfully scheduled with Dr.
                                <strong>{{ $appointment->doctor->user->name }}</strong>.
                            </p>
                            <p style="font-size: 16px; margin: 16px 0;">
                                <strong>Date:</strong> {{ $appointment->date }}<br>
                                <strong>Time:</strong>
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}<br>
                                <strong>Disease/Concern:</strong> {{ $appointment->disease }}
                            </p>
                            <p style="font-size: 16px; margin: 16px 0;">
                                Please ensure to arrive 10 minutes early for your appointment.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f4f4f4; padding: 20px; text-align: center;">
                            <p style="font-size: 14px; color: #666666; margin: 0;">
                                If you have any questions, feel free to contact us at
                                <a href="mailto:support@example.com" style="color: #4f46e5;">support@example.com</a>.
                            </p>
                            <p style="font-size: 14px; color: #666666; margin: 0;">
                                Thank you for choosing our services!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
a