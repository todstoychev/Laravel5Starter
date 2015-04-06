<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <table>
            <tr>
                <th>Username: </th>
                <td>{{ $admin_mail_data['username'] }}</td>
            </tr>
            <tr>
                <th>Email: </th>
                <td>{{ $admin_mail_data['email'] }}</td>
            </tr>
            <tr>
                <th>Registered at: </th>
                <td>{{ date('d/m/Y - h:i:s', strtotime($admin_mail_data['created_at'])) }}</td>
            </tr>
        </table>
    </body>
</html>