<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $subject }}</title>

  </head>



  <body>



    <p>

      I hope this message finds you well. Please find below the daily task

      report for the <b> {{$project->name}} </b> project on <b> date {{ $date }} </b>.

    </p>



    <h2>Daily Task Report - {{ $date }}</h2>



    <table style="border-collapse: collapse; width: 100%">

      <tr>

        <th

          style="

            border: 1px solid #dddddd;

            text-align: left;

            padding: 8px;

            background-color: #f2f2f2;

          "

        >

          S.No

        </th>

        <th

          style="

            border: 1px solid #dddddd;

            text-align: left;

            padding: 8px;

            background-color: #f2f2f2;

          "

        >

          Task Name

        </th>

       

        <th

          style="

            border: 1px solid #dddddd;

            text-align: left;

            padding: 8px;

            background-color: #f2f2f2;

          "

        >

          Status

        </th>

      </tr>



      @if(count($data) > 0) @foreach($data as $kk => $dd)

      <tr>

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          {{ ++$kk }}.

        </td>

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          {{$dd->name}}

        </td>

        @if($dd->report)

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          Done

        </td>

        @else

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          Pending

        </td>

        @endif

      </tr>

      @endforeach @endif

    </table>



    @if(false)

    <h2>Summary:</h2>

    <ul>

      <li>

        <strong>21 Bookmarking Days:</strong> This task remains pending. We are

        currently compiling the necessary resources and expect to initiate the

        bookmarking process by early next week.

      </li>

      <li>

        <strong>Banner Design:</strong> The design is still in the ideation

        phase. I am awaiting further input on the preferred themes and

        dimensions to ensure the banner aligns with our project objectives.

      </li>

    </ul>



    <h2>Next Steps:</h2>

    <ul>

      <li>

        To expedite the completion of the "21 Bookmarking Days," I will

        coordinate with the content team to finalize the materials by this

        weekend.

      </li>

      <li>

        For the "Banner Design," I will follow up on the pending queries

        regarding design specifics by tomorrow and aim to present initial drafts

        by the middle of next week.

      </li>

    </ul>

    @endif

    

    <p>If you want more detailed report than please visit your panel you can see all task with detailed report with attachements.</p>

    <a href="{{ url('login') }}">Panel Login here...</a>

    <br>

    

    

    @if(isset($weekly->remark))

      <p>{{ $weekly->remark}}</p>

    @endif

    <!--<p>-->

    <!--  I remain committed to advancing our project milestones and will ensure to-->

    <!--  keep you updated on our progress. Should you have any immediate queries or-->

    <!--  require further details on any of the tasks, please feel free to reach-->

    <!--  out.-->

    <!--</p>-->

    <!--<p>Thank you for your continued support and guidance.</p>-->



    <p>

      Best regards,<br />

      {{ ucfirst(auth()->user()->name) }}

    </p>

  </body>

</html>

