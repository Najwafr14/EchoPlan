<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documents;
use App\Models\Event;
use App\Models\Division;
use App\Models\EventDivMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $eventIds = EventDivMember::where('user_id', $userId)
            ->join('divisions', 'event_divmembers.division_id', '=', 'divisions.division_id')
            ->pluck('divisions.event_id')
            ->unique();
        
        $documents = Documents::whereIn('event_id', $eventIds)
            ->with(['event', 'uploader', 'division'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $userEvents = Event::whereIn('event_id', $eventIds)->get();
        
        $divisions = Division::whereIn('event_id', $eventIds)
            ->with('divisionType')
            ->get();
        
        return view('user.document.index', compact('documents', 'userEvents', 'divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'division_id' => 'nullable|exists:divisions,division_id',
            'entity_type' => 'nullable|in:Budget,Sponsor,Venue,Vendor,Talent,Item,None',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . str_replace(' ', '_', $validated['document_name']) . '.' . $extension;
            $path = $file->storeAs('documents', $filename, 'public');

            $documentType = $this->getDocumentType($extension);

            Documents::create([
                'event_id' => $validated['event_id'],
                'document_name' => $validated['document_name'],
                'document_type' => $documentType,
                'uploaded_by' => Auth::id(),
                'path' => $path,
                'division_id' => $validated['division_id'] ?? null,
                'entity_type' => $validated['entity_type'] === 'None' ? null : $validated['entity_type'],
                'entity_id' => null,
            ]);

            return redirect()->route('document.index')
                ->with('success', 'Document uploaded successfully! 📁');
        }

        return back()->with('error', 'Failed to upload document.');
    }

    public function download(Documents $document)
    {
        $userId = Auth::id();
        $hasAccess = EventDivMember::where('user_id', $userId)
            ->whereHas('division', function($q) use ($document) {
                $q->where('event_id', $document->event_id);
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this document.');
        }

        $filePath = storage_path('app/public/' . $document->path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $document->document_name . '.' . $document->getFileExtension());
    }

    public function destroy(Documents $document)
    {
        if ($document->uploaded_by !== Auth::id()) {
            abort(403, 'You can only delete your own documents.');
        }

        if (Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }

        $document->delete();

        return redirect()->route('document.index')
            ->with('success', 'Document deleted successfully!');
    }

    private function getDocumentType($extension)
    {
        $extension = strtolower($extension);
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            return 'Image';
        } elseif ($extension === 'pdf') {
            return 'PDF';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            return 'Word';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            return 'Excel';
        } elseif (in_array($extension, ['ppt', 'pptx'])) {
            return 'Power Point';
        }
        return 'Other';
    }
}