<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterBroadcastJob;
use App\Models\NewsletterBroadcast;
use App\Models\NewsletterSubscriber;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNewsletterController extends Controller
{
    public function index(Request $request)
    {
        $sectors = Sector::orderBy('name')->get();
        $sectorFilter = $request->input('sector');

        $subscribersQuery = NewsletterSubscriber::with('sectors')->latest();
        if ($sectorFilter) {
            $subscribersQuery->whereHas('sectors', fn ($q) => $q->where('sectors.id', $sectorFilter));
        }
        $subscribers = $subscribersQuery->paginate(20)->withQueryString();

        $broadcasts = NewsletterBroadcast::with(['targetSector', 'sender'])->latest()->paginate(10, ['*'], 'broadcasts_page');

        $totalSubscribers = NewsletterSubscriber::where('is_active', true)->count();
        $totalBroadcasts = NewsletterBroadcast::count();

        return view('admin.newsletter.index', compact(
            'sectors',
            'subscribers',
            'broadcasts',
            'sectorFilter',
            'totalSubscribers',
            'totalBroadcasts'
        ));
    }

    public function createBroadcast()
    {
        $sectors = Sector::orderBy('name')->get();

        return view('admin.newsletter.create', compact('sectors'));
    }

    public function sendBroadcast(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'target_sector_id' => ['nullable', 'string', 'exists:sectors,id'],
            'type' => ['required', 'string', 'in:announcement,product_release,regulatory_update,event'],
            'content' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subjek email wajib diisi.',
            'content.required' => 'Isi pesan broadcast wajib diisi.',
        ]);

        $broadcast = NewsletterBroadcast::create([
            'subject' => trim($validated['subject']),
            'target_sector_id' => $validated['target_sector_id'] ?: null,
            'type' => $validated['type'],
            'content' => HtmlSanitizer::clean($validated['content']),
            'sent_by_user_id' => Auth::id(),
            'recipients_count' => 0,
        ]);

        // Dispatch background queue job for safe email sending
        SendNewsletterBroadcastJob::dispatch($broadcast);

        $sectorLabel = $broadcast->targetSector?->name ?? 'Seluruh Sektor';

        return redirect()->route('admin.newsletter.index')->with('success', "Pesan siaran '{$broadcast->subject}' sedang dikirim ke pelanggan sektor {$sectorLabel} via background queue.");
    }
}
