

<!DOCTYPE html>
   <head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Offer-letter</title>
      <style type="text/css">
         * {
         margin: 0;
         padding: 0;
         text-indent: 0;
         }
         .s1 {
         color: black;
         font-family: Calibri, sans-serif;
         font-style: normal;
         font-weight: normal;
         text-decoration: none;
         font-size: 11pt;
         }
         h1 {
         color: black;
         font-family: Arial, sans-serif;
         font-style: normal;
         font-weight: bold;
         text-decoration: none;
         font-size: 11.5pt;
         }
         p {
         color: black;
         font-family: Arial, sans-serif;
         font-style: normal;
         font-weight: normal;
         text-decoration: none;
         font-size: 11.5pt;
         margin: 0pt;
         }
         .s2 {
         color: black;
         font-family: Arial, sans-serif;
         font-style: normal;
         font-weight: bold;
         text-decoration: underline;
         font-size: 11.5pt;
         }
         h2 {
         color: black;
         font-family: Calibri, sans-serif;
         font-style: normal;
         font-weight: bold;
         text-decoration: underline;
         font-size: 11pt;
         }
         .s3 {
         color: black;
         font-family: Arial, sans-serif;
         font-style: normal;
         font-weight: normal;
         text-decoration: underline;
         font-size: 11.5pt;
         }
         li {
         display: block;
         }
         #l1 {
         padding-left: 0pt;
         counter-reset: c1 1;
         }
         #l1>li>*:first-child:before {
         counter-increment: c1;
         content: counter(c1, decimal)". ";
         color: black;
         font-family: Calibri, sans-serif;
         font-style: normal;
         font-weight: bold;
         text-decoration: underline;
         font-size: 11pt;
         }
         #l1>li:first-child>*:first-child:before {
         counter-increment: c1 0;
         }
      </style>
   </head>
   <body>
      <p class="s1" style="padding-top: 1pt;padding-left: 195pt;text-indent: 0pt;text-align: left;">AdxVenture | Private and Confidential</p>
      <a href="<?php echo e(url('/')); ?>" class="text-center">
            <img src="<?php echo e(url('/')); ?>/logo.png" alt="Logo" width="300px"/>
      </a>
      <p style="padding-left: 197pt;text-indent: 0pt;text-align: left;">
      <p style="padding-top: 7pt;text-indent: 0pt;text-align: left;"><br /></p>
      <h1 style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Offer:<?php echo e($data['role']); ?> </h1>
      <h1 style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Date:  <?php echo e(\Carbon\Carbon::parse($data['created_at'])->format('d-m-Y')); ?></h1>
      <h1 style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Mr.<?php echo e(ucfirst($data['name'])); ?></h1>
      <h1 style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Sub: Letter of Intent and Terms of Employment</h1>
      <p style="padding-top: 1pt;text-indent: 0pt;text-align: left;"><br /></p>
      <p style="padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">Hello</p>
      <h1 style="padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;"><?php echo e(ucfirst($data['name'])); ?></h1>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Thank you for exploring career opportunities with AdxVenture. You have successfully completed our initial selection process and we are pleased to offer you the employment. <b>This offer is based on your profile, and performance in the selection process. You have been selected for the position of <?php echo e($data['role']); ?> at Adxventure - Dehradun.</b></p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <?php if($data['before_ctc'] == null): ?>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Your gross salary including all benefits will be Rs. <?php echo e($data['ctc']); ?>/- per month,</p>
      <?php else: ?>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Your gross salary including all benefits will be Rs. <?php echo e($data['before_ctc']); ?>/- per month, for first <?php echo e($data['before_ctc_period']); ?> months after that your salary will be <?php echo e($data['after_ctc']); ?>/- per month, for next <?php echo e($data['after_ctc_period']); ?> months. </p>
      <?php endif; ?>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Kindly confirm your acceptance of this offer by acknowledges the email and we will communicate your date of joining after successful background check over your documents. If you do not accept the offer letter within 7 days, this offer is liable to lapse at the discretion of AdxVenture.</p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p style="padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">On joining and successful completion of joining formalities, you will be issued a Letter of Appointment</p>
      <p class="s1" style="padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">By Adxventure.</p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p class="s2" style="text-indent: 0pt;text-align: center;">Terms of Employment</p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <ol id="l1">
         <li data-list-text="1.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Relevant Experience:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">We hire you without any experience, but you have knowledge in Laravel Development.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="2.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Probation Period:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">You will be on probation for 3 Months. Your confirmation will be communicated to you in written. AdxVenture reserves the right to terminate your employment in case your performance, behaviour and / or conduct during the probation period is found unsatisfactory.</p>
            <p style="padding-top: 13pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">(A). Your salary will be depending upon your performance.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="3.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Working Hours:&nbsp;</h2>
            <p style="padding-left: 8pt;text-indent: -3pt;text-align: left;">Your working hours will be 9:00 AM to 6:00 PM from Monday to Saturday, but you may be required to work in shifts and / or in extended working hours, as permitted by law.</p>
            <p style="padding-left: 5pt;text-indent: 3pt;text-align: left;">You may be required to work beyond your existing working hours depending upon the business requirements exigencies from time to time, without any extra remuneration / compensation.</p>
         </li>
         <li data-list-text="4.">
            <h2 style="padding-top: 2pt;padding-left: 13pt;text-indent: -8pt;text-align: left;"> Mobility:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">AdxVenture reserves the right to transfer / utilize your services at any of its offices, work sites, or associated or affiliated companies in India, or outside India, on the terms and conditions as applicable to you at the time of transfer.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="5.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Increments and Promotions:</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Your performance and contribution to the company will be an important consideration for salary increments and promotions. Salary increments and promotions will be based on AdxVenture’s Compensation and Promotion Policy.</p>
            <p style="padding-top: 13pt;text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="6.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Alternative Employment:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">As a full-time employee of AdxVenture, you are not permitted to undertake any other business, assume any public office, honorary or remunerative, without the written permission of AdxVenture.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="7.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Confidentiality Agreement:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">As part of the joining formalities, you are required to sign a confidentiality agreement, which aims to protect the intellectual property rights and business information of AdxVenture and its clients.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="8.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> AdxVenture Code of Conduct:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">You are required to sign the AdxVenture Code of Conduct and follow the same in your day to day conduct as an employee of AdxVenture.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="9.">
            <h2 style="padding-left: 13pt;text-indent: -8pt;text-align: left;"> Notice Period:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">During your employment with AdxVenture, including probation / training, either you or AdxVenture can terminate the appointment by giving 90 calendar days’ written notice or you can leave the organisation after product launch.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="10.">
            <h2 style="padding-left: 19pt;text-indent: -14pt;text-align: left;"> Background Check:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">You will be required to send the mentioned document to AdxVenture for a background check.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="11.">
            <h2 style="padding-left: 19pt;text-indent: -14pt;text-align: left;"> Joining Documents:</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">For a convenient joining process, the joining documents as mentioned in email should be submitted within 5 days of Offer Acceptance after due verification against originals. You can email to documents at the required id.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="12.">
            <h2 style="padding-left: 19pt;text-indent: -14pt;text-align: left;"> Letter of Appointment:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">You will be issued a letter of appointment at the time of your joining and completing joining formalities as per AdxVenture’s policy.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="13.">
            <h2 style="padding-left: 19pt;text-indent: -14pt;text-align: left;"> Terms and Conditions:&nbsp;</h2>
            <p style="padding-left: 8pt;text-indent: -3pt;text-align: left;">The above terms and conditions of employment are specific to your employment in India and there can be changes to the said terms and conditions in case of deputation on international assignments during the course of your employment.</p>
            <p style="text-indent: 0pt;text-align: left;"><br /></p>
         </li>
         <li data-list-text="14.">
            <h2 style="padding-left: 19pt;text-indent: -14pt;text-align: left;"> Rules and Regulations of the Company:&nbsp;</h2>
            <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Your appointment will be governed by the policies, rules, regulations, practices, processes and procedures of AdxVenture as applicable to you and the changes therein from time to time.</p>
         </li>
         <li data-list-text="15.">
            <h2 style="padding-top: 13pt;padding-left: 19pt;text-indent: -14pt;text-align: left;"> Compliance to all clauses:&nbsp;</h2>
         </li>
      </ol>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">You will be required to fulfil all the terms and conditions mentioned in this letter of offer. Any failure to fulfil any term and / or condition would entitle AdxVenture in withdrawing this offer letter at its sole discretion.</p>
      <p class="s2" style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Offer Letter Validity&nbsp;&nbsp;</p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">If you fail to accept the offer from AdxVenture within 7 days, it will be construed that you are not interested in this employment and this offer will be automatically withdrawn.</p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">We look forward to having you in our global team.&nbsp;&nbsp;</p>
      <p style="text-indent: 0pt;text-align: left;"><br /></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Yours Sincerely, AdxVenture,</p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Address: 29 Tagore Villa, Above Bank of Baroda, Connaught Place, Dehradun Uttarakhand Phone: +91-9410102425</p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Uttarakhand, India</p>
   </body>
</html><?php /**PATH /home/adxventure/lara_tms/resources/views/admin/candidates/mail.blade.php ENDPATH**/ ?>