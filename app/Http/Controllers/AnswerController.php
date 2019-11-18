<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\OldMessage;
use App\Exports\OldMessageExport;
use App\Message;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Log::channel('single')->info("Test log");

        $answers = Answer::with('user')->oldest('answer_id')->get();
        $lastID = $answers->last() != null ? $answers->last()->answer_id : 0;
        
        return view('answers.index', compact('answers', 'lastID'));
    }

    private function saveFile(string &$filename, string &$filepath, Request $request) {

        if ($request->hasFile('file')) {
            $fileInfo = $request->file('file');
            $filename = $fileInfo->getClientOriginalName();
            //$filepath = $request->file('filepath')->storeAs('doc', $filename, ['disk' => 'public']);
            $filecontent = file_get_contents(request()->file('file')->getRealPath());          
            file_put_contents($_SERVER['DOCUMENT_ROOT'] ."/storage/doc/$filename", $filecontent);
            $filepath = request()->root() ."/storage/doc/$filename";
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Answer $answer, Request $request)
    {
        $this->resetInput($request);

        $request->validate(
            $this->rules(null), $this->messages()
        );

        $filename = ""; $filepath = "";
       
        if ($request->hasFile('file')) {
            $this->saveFile($filename, $filepath, $request);
        }

        $request->merge([
            'user_id' => \Auth::id(),
            'body' => trim($request->body ?? null),
            'answers' => str_replace(" ", "", $request->answers ?? null),
            'was_sent_to_email' => 0,
            'need_send_to_email' => $request->need_send_to_email ?? 0,
            'filepath' => $filepath,
            'filename' => $filename,
        ]);

        //$test1 = $request->all();
        
        $answer->create($request->all());

        return back()->with('success', 
            "Ваше текстовое сообщение для пользователя успешно сохранено");
    }

         /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {   
        $this->resetInput($request);

        $rules = $this->rules($answer->id);
        if ($request->type == 'file') {
            $rules = array_merge($rules, [
                'file' => 'file'
            ]);
        }

        $request->validate(
            $rules,
            $this->messages()
        );

        $filename = ""; $filepath = "";

        if ($request->hasFile('file')) {
            $this->saveFile($filename, $filepath, $request);

            $answer->filepath = $filepath;
            $answer->filename = $filename;
            $answer->save();
        }

        $request->merge([
            'user_id' => \Auth::id(),
            'answers' => str_replace(" ", "", $request->answers ?? ''),
            'was_sent_to_email' => 0,
            'need_send_to_email' => $request->need_send_to_email ?? 0,
        ]);

        $answer->update(
            $request->all()
            //$request->except('filepath', 'filename')
        );

        return redirect()
            ->route('answers.index')
            ->with('success', 'Команда обновлена.');
    }

    private function resetInput(Request &$request) {
        switch($request->type) {
            case 'file':
                $request->merge([
                    'command' => null,
                    'body' => null,
                    'answers' => null,
                    'need_send_to_email' => 0
                ]);
                break;
            case 'location':
                $request->merge([
                    'command' => null,
                    'body' => null,
                    'answers' => null,
                    'need_send_to_email' => 0,
                    'filepath' => null,
                    'filename' => null
                ]);
                break;
            case 'text':
                $request->merge([
                    'filepath' => null,
                    'filename' => null,
                    'caption' => null, 
                    'lng' => null, 
                    'lat' => null, 
                    'address' => null,                 
                ]);
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //$id2 = $answer->id;
        $ID = $answer->answer_id;
        $answer->delete();

        return redirect('/home')
            ->with('success', "Команда c ID = $ID была удалена.");
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        $answers = Answer::with('user')->oldest('answer_id')->get();

        return view('answers.edit', compact('answers', 'answer'));
    }


    public function showmessages()
    {
        $messages = OldMessage//\DB::table('old_messages')
            ::latest()
            ->paginate(10);

        if ( isset($messages) && $messages->count() > 0 ) {
            
            foreach($messages as $curMessage)
            {
                $bodies = array();
                if ( !empty($curMessage->answers) ) {
                    $arrayAnswers = explode(',', $curMessage->answers);
                    //if ($curMessage->answers[0] == ',') $strAnswers = substr($strAnswers, 1);

                    $bodies = \App\Answer::whereIn('answer_id', $arrayAnswers)
                    ->orderBy( \DB::raw("FIELD(answer_id, $curMessage->answers)") )
                    ->latest()
                    ->get()
                    ->map(function($item) {
                        switch($item->type) {
                            case 'file':
                                return "Файл: $item->filepath Подпись под файлом: $item->caption";
                            case 'location':
                                return "Локация: $item->lat, $item->lng. Текст под сообщением: \"$item->address\"";
                            default:
                                return $item->body;
                        }
                    })
                    ->toArray();
                }
                $curMessage->answerBot = implode("\n", $bodies);//\Parsedown::instance()->text(implode("<br>", $bodies));
            }
        }

        return view('messages.index', compact('messages'));
    }

    public function newmessages()
    {
        $newmessages = Message//\DB::table('messages')
            ::latest()
            ->paginate(10);

        if ( isset($newmessages) && $newmessages->count() > 0 ) {
            
            foreach($newmessages as $curMessage)
            {
            
                $bodies = array();
                if ( !empty($curMessage->answers) ) {
                    $arrayAnswers = explode(',', $curMessage->answers ?? 'NULL');
                    //if ($curMessage->answers[0] == ',') $strAnswers = substr($strAnswers, 1);

                    $bodies = \App\Answer::whereIn('answer_id', $arrayAnswers)
                        ->orderBy( \DB::raw("FIELD(answer_id, $curMessage->answers)") )
                        ->latest()
                        ->get()
                        ->map(function($item) {
                            switch($item->type) {
                                case 'file':
                                    return "Файл: $item->filepath Подпись под файлом: $item->caption";
                                case 'location':
                                    return "Локация: $item->lat, $item->lng. Текст под сообщением: \"$item->address\"";
                                default:
                                    return $item->body;
                            }
                        })
                        ->toArray();
                        //->pluck('body', 'answer_id')         
                        //->toArray();
                }
                    
                $curMessage->answerBot = implode(" \n", $bodies);//\Parsedown::instance()->text(implode("<br>", $bodies));

            }
        }

        return view('messages.new', compact('newmessages'));
    }

    public function search(Request $request) 
    {
        $messages = OldMessage::latest();//\DB::table('old_messages');

        if ($request->has('message_id') && isset($request->message_id) ) {
            $messages->where('id', $request->message_id);
        }
        
        if ($request->has('chatId') && isset($request->chatId) ) {
            $messages->where('chatId', 'LIKE', "%$request->chatId%");
        }

        if ($request->has('created_at') && isset($request->created_at) ) {
            $messages->where('created_at', 'LIKE', "%$request->created_at%");
        }

        if ($request->has('type') && isset($request->type) ) {
            $messages->where('type', $request->type);
        }

        if ($request->has('senderName') && isset($request->senderName) ) {
            $messages->where('senderName', 'LIKE', "%$request->senderName%");
        }

        if ($request->has('command') && isset($request->command) ) {
            $messages->where('command', $request->command);
        }

        if ($request->has('body') && isset($request->body) ) {
            $messages->where('body', 'LIKE', "%$request->body%");
        }
        
        if ($request->has('answerBot') && isset($request->answerBot) ) {
            $arrayAnswers = explode( ',', str_replace(' ', '', $request->answerBot) );
            foreach($arrayAnswers as $answer) {
                $messages->where('answers', 'LIKE', "%$answer%");
            }
        }
 
        if ($request->has('was_sent_to_email') && isset($request->was_sent_to_email) ) {
            $messages->where('was_sent_to_email', $request->was_sent_to_email);
        }

        $messages = $messages->paginate(10);

        if ( isset($messages) && $messages->count() > 0 ) {
            foreach($messages as $curMessage)
            {
                $arrayAnswers = explode(',', $curMessage->answers);
                //if ($curMessage->answers[0] == ',') $strAnswers = substr($strAnswers, 1);
                $bodies = array();
                if ( !empty($curMessage->answers) ) {
                    $bodies = \App\Answer::whereIn('answer_id', $arrayAnswers)
                        ->orderBy( \DB::raw("FIELD(answer_id, $curMessage->answers)") )
                        ->latest()
                        ->get()
                        ->map(function($item) {
                            switch($item->type) {
                                case 'file':
                                    return "Файл: $item->filepath Подпись под файлом: $item->caption";
                                case 'location':
                                    return "Локация: $item->lat, $item->lng. Текст под сообщением: \"$item->address\"";
                                default:
                                    return $item->body;
                            }
                        })
                        ->toArray();
                }
                $curMessage->answerBot = implode("\n", $bodies);//\Parsedown::instance()->text(implode("<br>", $bodies));
            }
        }
        return view('messages.index')
            ->with('messages', $messages);
    }

    public function exportexcel()
    {
        return Excel::download( new OldMessageExport, 'old_messages.xlsx' );
    }

    public function show(Answer $answer)
    {
        
    }

    public function savemessages() {

        $newMessages = Message::all()->toArray(); //or get();

        if ( count($newMessages) > 0 ) {

            $arrayForInsert = array();
            array_filter($newMessages, function($newMessage) use(&$arrayForInsert) {
                unset($newMessage['id']);
                $arrayForInsert[] = $newMessage;
            });

            OldMessage::insert($arrayForInsert);

            Message::truncate();
        }
        return back()->with('success', "Все записи сохранены!");
    }

    public function delmessages() {

        if (OldMessage::all()->count() > 0) {
            OldMessage::truncate();
        }

        return back()->with('success', "Сообщений нет!");
    }

    public function delnewmessages() {

        if (Message::all()->count() > 0) {
            Message::truncate();
        }

        return back()->with('success', "Сообщений нет!");
    }

    public function rules(int $answerId = null) {
        return [
            'answer_id' => "required|integer|unique:answers,answer_id,$answerId",
            'command' => "unique:answers,command,$answerId|nullable",
            'body' => 'required_if:type,text',//|required_without_all:filepath,lat,lng',
            'lat' => 'required_if:type,location',//'required_without_all:body,filepath|required_if:type,location|numeric|nullable',
            'lng' => 'required_if:type,location',//'required_without_all:body,filepath|required_if:type,location|numeric|nullable',
            'file' => 'required_if:type,file|file',//'required_without_all:body,lat,lng|required_if:type,filepath|file',
        ];
    }
    public function messages() {
        return  [
            'answer_id.integer' => 'Введите целое значение',
            'answer_id.required' => 'Поле обязательное для заполнения',
            'answer_id.unique' => 'Команда с таким ID уже существует',
            'command.unique' => 'Такая команда уже существует',
            'body.required_if' => 'Поле обязательное для заполнения',
            'file.required_if' => 'Поле обязательное для заполнения',
            'lat.numeric' => 'Введите число',
            'lng.numeric' => 'Введите число',
            'lat.required_if' => 'Поле обязательное для заполнения',
            'lng.required_if' => 'Поле обязательное для заполнения',
        ];
    }
}
/* 'answer_id' => 'required|integer|unique:answers',
'command' => 'unique:answers,command|nullable',
'body' => 'required_without_all:filepath,lat,lng',
'lat' => 'required_without_all:body,filepath|numeric|nullable',
'lng' => 'required_without_all:body,filepath|numeric|nullable',
'filepath' => 'required_without_all:body,lat,lng|file',
],
[
'answer_id.integer' => 'Введите целое значение',
'answer_id.required' => 'Поле обязательное для заполнения',
'answer_id.unique' => 'Команда с таким ID уже существует',
'command.unique' => 'Такая команда уже существует',
'body.required_without_all' => 'Поле обязательное для заполнения',
'filepath.required_without_all' => 'Поле обязательное для заполнения',
'lat.numeric' => 'Введите число',
'lng.numeric' => 'Введите число',
'lat.required_without_all' => 'Поле обязательное для заполнения',
'lng.required_without_all' => 'Поле обязательное для заполнения',
] */