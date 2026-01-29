<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($subject); ?></title>
  </head>
  <body>
    <p>I hope this message finds you well. Please find below the Task Complate Report for the <b> <?php echo e($project->name); ?></b>.</p>
    <h2>Task Complate Report </h2>
    <table style="border-collapse: collapse; width: 100%">
      <tr>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;background-color: #f2f2f2;"> S.No</th>
        <th style="border: 1px solid #dddddd; text-align: left;padding: 8px; background-color: #f2f2f2;"> Task Name</th>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;background-color: #f2f2f2;">Status</th>
      </tr>
      <tr>
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">1.</td>
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px"><?php echo e($data->name); ?></td>
        <?php if($data->report): ?>
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">Done</td>
        <?php else: ?>
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px">Pending</td>
        <?php endif; ?>
      </tr>
    </table>
    <p>If you want more detailed report than please visit your panel you can see all task with detailed report with attachements.</p>
    <a href="<?php echo e(url('login')); ?>">Panel Login here...</a>
    <br>
    <p>
      Best regards,<br />
      <?php echo e(ucfirst(auth()->user()->name)); ?>

    </p>
  </body>
</html>

<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/email/taskComplate.blade.php ENDPATH**/ ?>