<?php

namespace App\Http\Controllers\Student;

use App\Events\WhatsAppMessageSent;
use App\Events\WhatsAppTyping;
use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaContact;
use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Models\SocialMedia\WhatsAppsNumber;
use App\Models\SocialMedia\WhatsappConversation;
use App\Models\SocialMedia\WhatsappMessage;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentCheckLog;
use App\Models\Student\StudentInService;
use App\Traits\BroadcastsSafely;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class StudentCheckLogController extends Controller
{
    use BroadcastsSafely;
    public function index(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/checkin', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
            'checkin' => StudentCheckLog::with(['student', 'user'])->orderBy('id', 'DESC')->where('student_id', $student->id)->paginate(15)
        ]);
    }

    public function store(Student $student, Request $request)
    {
        $created = StudentCheckLog::create([
            'student_id' => $student->id,
            'status' => 'Check IN',
            'user_id' => Auth::id(),
        ]);

        if ($created) {

            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student check in",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
            return back()->with('success', 'check in successfully.');
        } else {
            return back()->with('error', 'Unable to check in');
        }
    }

    public function checkOut(Student $student, Request $request)
    {
        $created = StudentCheckLog::create([
            'student_id' => $student->id,
            'status' => 'Check OUT',
            'user_id' => Auth::id(),
        ]);

        if ($created) {
            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student check out",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
            if (is_null($student->status)) {

                $student->update([
                    'status' => 1
                ]);

                return back()->with('message', 'check out successfully!');
            }
        } else {
            return back()->with('error', 'Unable to check out');
        }
    }

    public function chat(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/chat', [
            'student'        => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
        ]);
    }

    public function updateChatUrl(Student $student, Request $request)
    {
        $validated = $request->validate([
            'inbox_url' => 'nullable|url|max:2000',
        ]);
        $student->update(['inbox_url' => $validated['inbox_url']]);
        return back()->with('success', 'Messenger Inbox URL updated successfully.');
    }

    public function whatsapp(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/whatsapp', [
            'student'        => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
        ]);
    }

    public function updateWhatsappUrl(Student $student, Request $request)
    {
        $validated = $request->validate([
            'whatsapp_url' => 'nullable|url|max:2000',
        ]);
        $student->update(['whatsapp_url' => $validated['whatsapp_url']]);
        return back()->with('success', 'WhatsApp URL updated successfully.');
    }

    public function whatsappMessages(Student $student)
    {
        $conversation = WhatsAppConversation::where('student_id', $student->id)->first();
        $messages = collect();

        if ($conversation) {
            $messages = WhatsAppMessage::with('replyTo')
                ->where('conversation_id', $conversation->id)
                ->orderBy('message_time', 'asc')
                ->get();

            // Mark conversation as read when agent opens it
            if ($conversation->unread_count > 0 || !$conversation->is_read) {
                $conversation->update(['unread_count' => 0, 'is_read' => 1]);
                WhatsappMessage::where('conversation_id', $conversation->id)
                    ->where('direction', 'incoming')
                    ->whereNull('read_at')
                    ->update(['read_at' => now(), 'status' => 'read']);
            }
        }

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages,
            'unread' => $conversation?->unread_count ?? 0,
        ]);
    }

    public function uploadWhatsappMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:40960', // 40MB
        ]);

        $file = $request->file('file');
        $mime = $file->getClientMimeType();
        $size = $file->getSize();
        $originalName = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension() ?: 'bin';
        $name = uniqid('wa_') . '.' . $ext;
        $dir = 'whatsapp/' . date('Y/m');
        $destination = public_path($dir);

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $name);
        $absolute = $destination . '/' . $name;

        // Convert audio to mp3 so it plays in every browser (incl. Safari)
        if (str_starts_with((string) $mime, 'audio')) {
            $mp3 = $this->convertAudioToMp3($absolute);
            if ($mp3) {
                $absolute = $mp3;
                $name = basename($mp3);
                $mime = 'audio/mpeg';
                $size = filesize($mp3);
            }
        }

        return response()->json([
            'url' => asset($dir . '/' . $name),
            'mime' => $mime,
            'size' => $size,
            'name' => $originalName,
        ]);
    }

    private function ffmpegPath(): ?string
    {
        $configured = env('FFMPEG_PATH');
        if ($configured && is_executable($configured)) {
            return $configured;
        }

        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where ffmpeg' : 'which ffmpeg';
        exec($which . ' 2>nul', $out, $code);

        if ($code === 0 && !empty($out[0]) && is_executable($out[0])) {
            return $out[0];
        }

        return null;
    }

    private function convertAudioToMp3(string $path): ?string
    {
        $ffmpeg = $this->ffmpegPath();

        if (!$ffmpeg || !file_exists($path)) {
            return null;
        }

        $mp3 = preg_replace('/\.[^.]+$/', '.mp3', $path);
        if ($mp3 === $path) {
            $mp3 = $path . '.mp3';
        }

        $cmd = sprintf(
            '%s -y -i %s -ar 44100 -ac 1 -b:a 64k %s 2>&1',
            escapeshellarg($ffmpeg),
            escapeshellarg($path),
            escapeshellarg($mp3)
        );

        exec($cmd, $output, $code);

        if ($code === 0 && file_exists($mp3) && filesize($mp3) > 0) {
            @unlink($path);
            return $mp3;
        }

        return null;
    }

    public function whatsappTyping(Student $student, Request $request)
    {
        $typing = $request->boolean('typing', true);
        try {
            $this->safeBroadcast(new WhatsAppTyping($student->id, $typing));
        } catch (\Throwable $e) {
            // ignore
        }
        return response()->json(['success' => true]);
    }

    public function whatsappMarkRead(Student $student)
    {
        $conversation = WhatsappConversation::where('student_id', $student->id)->first();
        if ($conversation) {
            $conversation->update(['unread_count' => 0, 'is_read' => 1]);
            WhatsappMessage::where('conversation_id', $conversation->id)
                ->where('direction', 'incoming')
                ->whereNull('read_at')
                ->update(['read_at' => now(), 'status' => 'read']);
        }
        return response()->json(['success' => true]);
    }

    private function sendViaWhatsapp(array $data): array
    {
        $student = $data['student'];
        $setup = SocialMediaSetup::where('platform', 'whatsapp')->first();
        $phoneNumber = $setup ? WhatsAppsNumber::where('waba_id', $setup->whatsapp_business_account_id)->first() : null;

        if (!$setup || !$setup->access_token || !$phoneNumber || !$student->phone) {
            return ['ok' => false, 'json' => null];
        }

        $phone = '+' . ltrim($student->phone, '+');
        $payload = [
            "messaging_product" => "whatsapp",
            "to" => ltrim($phone, '+'),
            "type" => $data['api_type'],
            $data['api_type'] => $data['api_body'],
        ];

        $response = Http::withToken($setup->access_token)
            ->post("https://graph.facebook.com/v23.0/{$phoneNumber->phone_id}/messages", $payload);

        return ['ok' => true, 'json' => $response->json()];
    }

    public function sendWhatsappMessage(Student $student, Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:4000',
            'message_type' => 'required|string|in:text,image,document,audio,voice,file,video,location,sticker',
            'media_url' => 'nullable|string',
            'media_mime' => 'nullable|string',
            'media_size' => 'nullable|integer',
            'media_name' => 'nullable|string',
            'reply_to' => 'nullable|exists:whatsapp_messages,id',
        ]);

        $type = $request->message_type;
        $body = $request->message ?? '';

        if ($type === 'text' && !trim($body) && !$request->media_url) {
            return response()->json(['error' => 'Message cannot be empty.'], 422);
        }

        $phone = $student->phone ? '+' . ltrim($student->phone, '+') : null;
        if (!$phone) {
            return response()->json(['error' => 'Student has no phone number.'], 422);
        }

        $apiType = match ($type) {
            'image' => 'image',
            'audio', 'voice' => 'audio',
            'document', 'file', 'pdf' => 'document',
            'video' => 'video',
            default => 'text',
        };

        $apiBody = [];
        if ($apiType === 'text') {
            $apiBody = ['body' => $body];
        } else {
            $apiBody = ['link' => $request->media_url];
            if ($apiType === 'document') {
                $apiBody['filename'] = $request->media_name ?? 'file';
            }
            if ($type === 'image' && $body) {
                $apiBody['caption'] = $body;
            }
        }

        $result = $this->sendViaWhatsapp([
            'student' => $student,
            'api_type' => $apiType,
            'api_body' => $apiBody,
        ]);

        $json = $result['json'] ?? null;
        $metaId = $json['messages'][0]['id'] ?? null;

        $conversation = SocialMediaContact::updateOrCreate(
            ['student_id' => $student->id],
            [
                'phone' => $phone,
                'name' => trim($student->fname . ' ' . $student->lname),
                'last_message_at' => now(),
                'is_read' => 1,
            ]
        );

        $message = WhatsappMessage::create([
            'conversation_id' => $conversation->id,
            'meta_message_id' => $metaId,
            'direction' => 'outgoing',
            'status' => $result['ok'] ? 'sent' : 'failed',
            'message_type' => $type,
            'message' => $body,
            'media_url' => $request->media_url,
            'media_mime' => $request->media_mime,
            'media_size' => $request->media_size,
            'media_name' => $request->media_name,
            'reply_to' => $request->reply_to,
            'payload' => json_encode($json),
            'message_time' => now(),
        ]);

        $conversation->update(['last_message_at' => now()]);

        try {
            $this->safeBroadcast(new WhatsAppMessageSent($conversation, $message));
        } catch (\Throwable $e) {
            // Broadcasting is optional; ignore if not configured
        }

        return response()->json([
            'success' => true,
            'configured' => $result['ok'],
            'message' => $message,
        ]);
    }
}
