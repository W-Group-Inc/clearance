<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Clearance Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <p>Dear {{$employee->first_name}} {{$employee->last_name}},</p>

    <p>Your exit clearance status has been updated to <strong>{{$statusUpdate->status}}</strong>.</p>

    @if($statusUpdate->remarks)
        <p><strong>Remarks:</strong> {{$statusUpdate->remarks}}</p>
    @endif

    <p>The supporting document is attached to this email for your reference.</p>

    <p>If you have any questions or concerns, please contact HR.</p>

    <p>Thank you,<br>W Exit</p>
</body>
</html>
