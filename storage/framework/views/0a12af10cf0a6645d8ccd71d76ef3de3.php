<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo e($subject); ?></title>

  </head>



  <body>



    <p>

      I hope this message finds you well. Please find below the daily task

      report for the <b> <?php echo e($project->name); ?> </b> project on <b> date <?php echo e($date); ?> </b>.

    </p>



    <h2>Daily Task Report - <?php echo e($date); ?></h2>



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



      <?php if(count($data) > 0): ?> <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kk => $dd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

      <tr>

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          <?php echo e(++$kk); ?>.

        </td>

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          <?php echo e($dd->name); ?>


        </td>

        <?php if($dd->report): ?>

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          Done

        </td>

        <?php else: ?>

        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">

          Pending

        </td>

        <?php endif; ?>

      </tr>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>

    </table>



    <?php if(false): ?>

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

    <?php endif; ?>

    

    <p>If you want more detailed report than please visit your panel you can see all task with detailed report with attachements.</p>

    <a href="<?php echo e(url('login')); ?>">Panel Login here...</a>

    <br>

    

    

    <?php if(isset($weekly->remark)): ?>

      <p><?php echo e($weekly->remark); ?></p>

    <?php endif; ?>

    <!--<p>-->

    <!--  I remain committed to advancing our project milestones and will ensure to-->

    <!--  keep you updated on our progress. Should you have any immediate queries or-->

    <!--  require further details on any of the tasks, please feel free to reach-->

    <!--  out.-->

    <!--</p>-->

    <!--<p>Thank you for your continued support and guidance.</p>-->



    <p>

      Best regards,<br />

      <?php echo e(ucfirst(auth()->user()->name)); ?>


    </p>

  </body>

</html>

<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/email/ReportEmail.blade.php ENDPATH**/ ?>