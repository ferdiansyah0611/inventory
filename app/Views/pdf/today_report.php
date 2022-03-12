<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <style>
        .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
        }
        .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        }
        .invoice-box table td {
        padding: 5px;
        vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
        text-align: right;
        }
        .invoice-box table tr.top table td {
        padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
        }
        .invoice-box table tr.information table td {
        padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        }
        .invoice-box table tr.details td {
        padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
        border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
        }
        @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
        }
        .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
        }
        }
        /** RTL **/
        .invoice-box.rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }
        .invoice-box.rtl table {
        text-align: right;
        }
        .invoice-box.rtl table tr td:nth-child(2) {
        text-align: left;
        }
        </style>
    </head>
    <body>
        <div class="invoice-box">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title">
                                    <img src="https://www.sparksuite.com/images/logo.png" style="width: 100%; max-width: 300px" />
                                </td>
                                <td>
                                    Report's Today : <?= $data['created_at'] ?><br/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="information">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td>
                                    Fairy Tech, Inc.<br />
                                    South Tangerang<br />
                                    Banten, 15229
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="heading">
                    <td>Order</td>
                    <td>Price</td>
                </tr>
                <?php foreach ($list as $key => $value): ?>
                <tr class="item">
                    <td>
                        <?= $value->customer_name ?> | <?= $value->products_name ?> (<?= $value->quantity ?> item)
                    </td>
                    <td>$<?= number_format($value->price_total, 0) ?></td>
                </tr>
                <?php endforeach ?>
                <tr>
                    <td></td>
                    <td style="font-weight: bold;">Total: $<?= number_format($price_total, 0) ?></td>
                </tr>
                <?php if (isset($spend)): ?>
                    <tr class="heading">
                        <td>Spend</td>
                        <td>Price</td>
                    </tr>
                    <?php foreach ($spend as $key => $value): ?>
                    <tr class="item">
                        <td>
                            <?= $value['name'] ?>
                        </td>
                        <td>$<?= number_format($value['total'], 0) ?></td>
                    </tr>
                    <?php endforeach ?>
                    <tr>
                        <td></td>
                        <td style="font-weight: bold;">Total: $<?= number_format($totalspend, 0) ?></td>
                    </tr>
                    <tr class="heading">
                        <td>Net Profit</td>
                        <td style="font-weight: bold;">$<?= number_format($netprofit, 0) ?></td>
                    </tr>
                    <tr class="heading">
                        <td>Net Profit Margin</td>
                        <td style="font-weight: bold;">$<?= number_format($netprofitmargin, 0) ?></td>
                    </tr>
                <?php endif ?>
            </table>
        </div>
    </body>
</html>