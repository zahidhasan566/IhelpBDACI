<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yamaha Customer Feedback</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/style.css">

</head>
<body>

<div class="container">
    <div class="heading">
        <div class="logo">
            <img class="img-responsive" style="float: right" src="<?php echo base_url()?>assets/images/logo.png" alt="logo">
        </div>
        <div class="title">
            <h3 class="">কাস্টমের প্রতিক্রিয়া </h3>
        </div>
    </div>
    <?php if (isset($invoice) && !empty($invoice) && isset($enable_to_feedback)) {
        ?>
        <form class="form cf" action="<?php echo base_url();?>CustomerFeedback/storeCustomerFeedback" method="post">
            <input type="hidden" name="invoice" value="<?php echo $invoice;?>">
            <input type="hidden" name="mobile_number" value="<?php echo isset($mobile_number) ?  $mobile_number : '';?>">
            <input type="hidden" name="verification_code" value="<?php echo isset($verification_code) ? $verification_code : '';?>">
            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id;?>">

            <?php if(isset($customer_feedback_question) && !empty($customer_feedback_question)) {
                foreach ($customer_feedback_question as $key => $q) {
                    if($q['QuestionType'] =='numeric') {
                        ?>
                        <section class="plan cf mt-2">
                            <p><?php echo $q['Question'];?></p>
                            <input type="radio" name="result[<?php echo $q['QuestionId'];?>]" id="<?php echo $q['QuestionId'];?>5" value="5"><label class="free-label w-5 col" for="<?php echo $q['QuestionId'];?>5">5</label>
                            <input type="radio" name="result[<?php echo $q['QuestionId'];?>]" id="<?php echo $q['QuestionId'];?>4" value="4" <?php echo ($key == 0) ? 'checked': '' ?> ><label class="basic-label w-5 col" for="<?php echo $q['QuestionId'];?>4">4</label>
                            <input type="radio" name="result[<?php echo $q['QuestionId'];?>]" id="<?php echo $q['QuestionId'];?>3" value="3"><label class="premium-label w-5 col" for="<?php echo $q['QuestionId'];?>3">3</label>
                            <input type="radio" name="result[<?php echo $q['QuestionId'];?>]" id="<?php echo $q['QuestionId'];?>2" value="2"><label class="premium-label w-5 col" for="<?php echo $q['QuestionId'];?>2">2</label>
                            <input type="radio" name="result[<?php echo $q['QuestionId'];?>]" id="<?php echo $q['QuestionId'];?>1" value="1"><label class="premium-label w-5 col" for="<?php echo $q['QuestionId'];?>1">1</label>
                        </section>
                        <section class="plan cf"><span style="width: 75%;float: left">সম্পূর্ণভাবে</span> <span class="pull-right" style="margin-right: 5px">সন্তুষ্ট নয় </span></section>
                        <?php

                    } elseif($q['QuestionType'] =='text') {
                        ?>
                        <section class="plan cf mt-2">
                            <p><?php echo $q['Question'];?></p>
                            <div>
                                <input type="text" class="form-control opinion-textbox" name="result[<?php echo $q['QuestionId'];?>]" value="">
                            </div>
                        </section>

                        <?php
                    }
                }
            }?>
            <input class="submit" type="submit" name="customer_feedback_submit" value="Submit">
        </form>
        <?php
    } elseif (isset($message) && $message !='') {
        ?>
            <h4 class="text text-center <?php echo (isset($success) && $success == 1) ? 'text-success': 'text-danger' ?>"><?php echo $message;?></h4>
        <?php
    }
    ?>

</div>

</body>
</html>
