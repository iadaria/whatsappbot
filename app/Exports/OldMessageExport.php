<?php

namespace App\Exports;

use App\OldMessage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OldMessageExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return OldMessage::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'bot_id',
            'chatId',
            'Текст отправленный пользователем',
            'Тип',
            'Имя отправителя',
            'Автор ChatId',
            'Chat time',
            'Команда от пользователя',
            'Ответ полученный пользователем',
            'Необходимо отправить на email',
            'Отправлен на email',
            'Дата получения',
            'Дата обновления',
        ];
    }
}
