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
                @foreach ($messagesToShow as $curMessage)
                    <tr>
                        <td align="center" style="border: 1px solid gray;">{!! $curMessage['body_html'] !!}</td>
                        <td style="border: 1px solid gray;">{!! $curMessage['show'] !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>
