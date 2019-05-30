<!DOCTYPE html>
<html>
    <head>
        <title>Admin Approval Email</title>
        <style type="text/css">
            table, tr, td {
                border: none;
            }
        </style>
    </head>

    <body>
        <h2>Hi Admin,</h2>

        We've got a new registration on the site <a href="{{ url('/') }}">Prognica Labs</a>, and the details are,
        <table border="0">
            <tr>
                <th>Name: </th>
                <td>{{$user->profile->name}}</td>
            </tr>
            <tr>
                <th>Email Address:</th>
                <td>{{$user['email']}}</td>
            </tr>
            <tr>
                <th>Mobile Number:</th>
                <td>{{ $user->profile->mobile }}</td>
            </tr>
            <tr>
                <th>Organization:</th>
                <td>{{$user->profile->organisation->organisation_name}}</td>
            </tr>
        </table>
        <br/>
        
    </body>

</html>