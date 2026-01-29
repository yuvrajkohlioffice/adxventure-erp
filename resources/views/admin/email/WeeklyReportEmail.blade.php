<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <p>
        I hope this message finds you well. Please find below the Weekly Work
        report for the <b>{{ $weekly->project->name }}</b> project on <b>{{ $weekly->created_at->format('Y-m-d') }}</b>.
    </p>

    <h2>Weekly Report Summary</h2>
    <table>
        <tr>
            <th>S.No</th>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>1.</td>
            <td>Rank</td>
            <td>{{ $weekly->rank }}</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Followers</td>
            <td>{{ $weekly->followers }}</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Reel Created</td>
            <td>{{ $weekly->reel_create }}</td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Reel Posted</td>
            <td>{{ $weekly->reel_post }}</td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Banner</td>
            <td>{{ $weekly->banner }}</td>
        </tr>
        <tr>
            <td>6.</td>
            <td>Media</td>
            <td>
                @php
                    $medias = explode(',', $weekly->media); // Split the string into an array
                @endphp
                {{ implode('   ', $medias) }}

            </td>
        </tr>
        <tr>
            <td>7.</td>
            <td>Blog</td>
            <td>
                @php
                    $blogs = explode(',', $weekly->blog); // Split the string into an array
                @endphp
                {{ implode('    ', $blogs) }} <!-- Join array elements with <br> -->
            </td>
        </tr>
        <tr>
            <td>8.</td>
            <td>Story</td>
            <td>
                @php
                    $stories = explode(',', $weekly->story); // Split the string into an array
                @endphp 
                {{ implode('    ', $stories) }} <!-- Join array elements with <br> -->
            </td>
        </tr>
        <tr>
            <td>9.</td>
            <td>Bookmarking</td>
            <td>
                {{$weekly->book_marking}} <!-- Join array elements with <br> -->
            </td>
        </tr>
        <tr>
            <td>10.</td>
            <td>Profile</td>
            <td>{{ $weekly->profile }}</td>
        </tr>
        <tr>
            <td>11.</td>
            <td>Classified</td>
            <td>{{ $weekly->classified }}</td>
        </tr>
        <tr>
            <td>12.</td>
            <td>Directory Submission</td>
            <td>{{ $weekly->directory_submission }}</td>
        </tr>
        <tr>
            <td>13.</td>
            <td>Article Submission</td>
            <td>{{ $weekly->artical }}</td>
        </tr>
        <tr>
            <td>14.</td>
            <td>Quora Answering</td>
            <td>{{ $weekly->quora }}</td>
        </tr>
        <tr>
            <td>15.</td>
            <td>Total Backlinks</td>
            <td>{{ $weekly->total_backlinks }}</td>
        </tr>
        <tr>
            <td>16.</td>
            <td>Attachement</td>
            <td>https://tms.adxventure.com/{{ $weekly->report }}</td>
        </tr>
    </table>
    <p>If you want a more detailed report, please visit your panel where you can see all tasks with detailed reports and attachments.</p>
    <a href="{{ url('login') }}">Panel Login here...</a>
    <br>
    @if(isset($weekly->remark))
        <p>{{ $weekly->remark }}</p>
    @endif
    <p>
        Best regards,<br>
        {{ ucfirst(auth()->user()->name) }}
    </p>
</body>
</html>
