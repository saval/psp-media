<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tests</title>

    <style type="text/css">

        ::selection { background-color: #E13300; color: white; }
        ::-moz-selection { background-color: #E13300; color: white; }

        body {
            background-color: #fff;
            margin: 40px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 19px;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #body {
            margin: 0 15px 0 15px;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        #container {
            margin: 10px;
            border: 1px solid #D0D0D0;
            box-shadow: 0 0 8px #D0D0D0;
        }
        td.text-danger { color: red; }
        td.text-success { color: green; }
    </style>
</head>
<body>

<div id="container">
    <h1>Tests</p>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Test name</th>
            <th scope="col">Result</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $test_num = 1;
            $total_res = ['total' => 0, 'passed' => 0];
        ?>
        <?php
        foreach ($customers_model_tests_res as $test) :
            if ('Failed' == $test['Result']) :
                $res_class = 'text-danger';
            else :
                $res_class = 'text-success';
                $total_res['passed']++;
            endif;
            $total_res['total']++;
            ?>
        <tr>
            <th scope="row"><?=$test_num++;?></th>
            <td><?=$test['Test Name'];?></td>
            <td class="<?=$res_class;?>"><?=$test['Result'];?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <th align="right">Result</th>
            <th><?=sprintf("%d / %d", $total_res['passed'], $total_res['total']);?></th>
        </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
