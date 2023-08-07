<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);

        $attachment->delete();
        return back()->with('success', __('layout.attachment_deleted'));
    }
}
