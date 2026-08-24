<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SupportReplyMail;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        $messages   = SupportMessage::latest()->paginate(20);
        $unreadCount = SupportMessage::where('is_read', false)->count();

        return view('admin.support.index', compact('messages', 'unreadCount'));
    }

    public function show(SupportMessage $message): View
    {
        if (! $message->is_read) {
            $message->markAsRead();
        }

        return view('admin.support.show', compact('message'));
    }

    public function reply(Request $request, SupportMessage $message): RedirectResponse
    {
        $request->validate([
            'reply' => 'required|string|min:5',
        ]);

    

        // بعث الإيميل للكليان
        Mail::to($message->email)
            ->send(new SupportReplyMail(
                clientName:      $message->name,
                clientSubject:   $message->subject,
                replyContent:    $request->reply,
                originalMessage: $message->message,
            ));

        // حفظ الرد فقاعدة البيانات
        $message->update([
            'reply'      => $request->reply,
            'replied_at' => now(),
        ]);

        return back()->with('success', "Réponse envoyée à {$message->email} ✅");
    }

    public function destroy(SupportMessage $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.support.index')->with('success', 'Message supprimé');
    }
}