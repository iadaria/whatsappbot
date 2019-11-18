<head>
    <style>
        .my-table td {
            border: 1px;
        }
        td, th {
            border: 1px solid b;
        }
        td, th {
            padding: 2rem;
            padding-left: 3rem;
            align: justify;
        }
    </style>
</head>
<body>
    <h3>Сообщения от пользователя</h3>
        <table>
            <thead>
                <tr>
                    <th style="border: 1px solid besque;">Клиент отправил</th>
                    <th style="border: 1px solid gray;">Ответ бота</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $messagesToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td align="center" style="border: 1px solid gray;"><?php echo $curMessage['body_html']; ?></td>
                        <td style="border: 1px solid gray;"><?php echo $curMessage['show']; ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
</body>
<?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/emails/message.blade.php ENDPATH**/ ?>