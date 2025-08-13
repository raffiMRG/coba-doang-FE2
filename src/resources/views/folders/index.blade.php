<!DOCTYPE html>
<html>

<head>
    <title>Folder List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>

<body>

    <h1>Folder List</h1>

    @if ($error)
        <p style="color: red;">{{ $error }}</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Folder Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($folders as $folder)
                    <tr>
                        <td>{{ $folder['id'] }}</td>
                        <td>{{ $folder['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>

</html>
